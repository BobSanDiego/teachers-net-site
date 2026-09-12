locals {
  account_id       = "553830187994"
  region           = "us-west-2"
  media_bucket     = "tnet-c3-media-553830187994-us-west-2"
  state_bucket     = "tnet-c3-media-state-553830187994-us-west-2"
  processing_queue = "tnet-c3-media-processing"
  processing_dlq   = "tnet-c3-media-processing-dlq"
  processor_name   = "tnet-c3-media-processor"
  media_host       = "media.teachers.net"
  distribution_id  = "EXVHOH58DVJUJ"
  oac_id           = "E3GATUZLP66CJT"
  processor_image  = "553830187994.dkr.ecr.us-west-2.amazonaws.com/tnet-c3-media-processor@sha256:390a38fa43578de7c0ec6ca24450299dc9c30907b39ac876fc5fd9639d66ec76"
}

resource "aws_s3_bucket" "media" {
  bucket = local.media_bucket
}

resource "aws_s3_bucket_versioning" "media" {
  bucket = aws_s3_bucket.media.id

  versioning_configuration {
    status = "Enabled"
  }
}

resource "aws_s3_bucket_server_side_encryption_configuration" "media" {
  bucket = aws_s3_bucket.media.id

  rule {
    apply_server_side_encryption_by_default {
      sse_algorithm = "AES256"
    }
  }
}

resource "aws_s3_bucket_public_access_block" "media" {
  bucket                  = aws_s3_bucket.media.id
  block_public_acls       = true
  block_public_policy     = true
  ignore_public_acls      = true
  restrict_public_buckets = true
}

resource "aws_s3_bucket_lifecycle_configuration" "media" {
  bucket = aws_s3_bucket.media.id

  rule {
    id     = "expire-quarantine-raw"
    status = "Enabled"

    filter {
      prefix = "quarantine/"
    }

    expiration {
      days = 1
    }

    abort_incomplete_multipart_upload {
      days_after_initiation = 1
    }
  }

  rule {
    id     = "expire-noncurrent-media-versions-30d"
    status = "Enabled"

    noncurrent_version_expiration {
      noncurrent_days           = 30
      newer_noncurrent_versions = 1
    }
  }

  transition_default_minimum_object_size = "all_storage_classes_128K"
}

resource "aws_s3_bucket_notification" "media" {
  bucket = aws_s3_bucket.media.id

  queue {
    id            = "c3-media-quarantine-processing"
    queue_arn     = aws_sqs_queue.processing.arn
    events        = ["s3:ObjectCreated:*"]
    filter_prefix = "quarantine/"
  }
}

resource "aws_s3_bucket_policy" "media" {
  bucket = aws_s3_bucket.media.id

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [{
      Sid       = "AllowCloudFrontC3MediaReadyOnly"
      Effect    = "Allow"
      Principal = { Service = "cloudfront.amazonaws.com" }
      Action    = "s3:GetObject"
      Resource  = "arn:aws:s3:::${local.media_bucket}/ready/*"
      Condition = {
        StringEquals = {
          "AWS:SourceArn" = "arn:aws:cloudfront::${local.account_id}:distribution/${local.distribution_id}"
        }
      }
    }]
  })
}

resource "aws_sqs_queue" "processing" {
  name                       = local.processing_queue
  delay_seconds              = 0
  max_message_size           = 1048576
  message_retention_seconds  = 345600
  receive_wait_time_seconds  = 0
  visibility_timeout_seconds = 90
  sqs_managed_sse_enabled    = true

  redrive_policy = jsonencode({
    deadLetterTargetArn = "arn:aws:sqs:${local.region}:${local.account_id}:${local.processing_dlq}"
    maxReceiveCount     = 3
  })
}

resource "aws_sqs_queue" "dlq" {
  name                       = local.processing_dlq
  delay_seconds              = 0
  max_message_size           = 1048576
  message_retention_seconds  = 1209600
  receive_wait_time_seconds  = 0
  visibility_timeout_seconds = 30
  sqs_managed_sse_enabled    = true

  redrive_allow_policy = jsonencode({
    redrivePermission = "byQueue"
    sourceQueueArns   = ["arn:aws:sqs:${local.region}:${local.account_id}:${local.processing_queue}"]
  })
}

resource "aws_sqs_queue_policy" "processing" {
  queue_url = aws_sqs_queue.processing.id

  policy = jsonencode({
    Version = "2012-10-17"
    Id      = "__default_policy_ID"
    Statement = [
      {
        Sid       = "__owner_statement"
        Effect    = "Allow"
        Principal = { AWS = "arn:aws:iam::${local.account_id}:root" }
        Action    = "SQS:*"
        Resource  = aws_sqs_queue.processing.arn
      },
      {
        Sid       = "AllowC3MediaBucketNotifications"
        Effect    = "Allow"
        Principal = { Service = "s3.amazonaws.com" }
        Action    = "sqs:SendMessage"
        Resource  = aws_sqs_queue.processing.arn
        Condition = {
          StringEquals = { "aws:SourceAccount" = local.account_id }
          ArnLike      = { "aws:SourceArn" = "arn:aws:s3:::${local.media_bucket}" }
        }
      }
    ]
  })
}

resource "aws_sqs_queue_policy" "dlq" {
  queue_url = aws_sqs_queue.dlq.id

  policy = jsonencode({
    Version = "2012-10-17"
    Id      = "__default_policy_ID"
    Statement = [{
      Sid       = "__owner_statement"
      Effect    = "Allow"
      Principal = { AWS = "arn:aws:iam::${local.account_id}:root" }
      Action    = "SQS:*"
      Resource  = aws_sqs_queue.dlq.arn
    }]
  })
}

resource "aws_ecr_repository" "processor" {
  name                 = local.processor_name
  image_tag_mutability = "IMMUTABLE"

  image_scanning_configuration {
    scan_on_push = false
  }

  encryption_configuration {
    encryption_type = "AES256"
  }
}

resource "aws_ecr_repository_policy" "processor" {
  repository = aws_ecr_repository.processor.name

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [{
      Sid       = "AllowC3LambdaImageRetrieval"
      Effect    = "Allow"
      Principal = { Service = "lambda.amazonaws.com" }
      Action    = ["ecr:BatchGetImage", "ecr:GetDownloadUrlForLayer"]
      Condition = {
        ArnLike = {
          "aws:sourceARN" = "arn:aws:lambda:${local.region}:${local.account_id}:function:${local.processor_name}"
        }
      }
    }]
  })
}

resource "aws_iam_policy" "processor_boundary" {
  name = "TNetC3MediaProcessorBoundary"

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid      = "AllowReadQuarantineOnly"
        Effect   = "Allow"
        Action   = ["s3:GetObject", "s3:GetObjectVersion", "s3:DeleteObject"]
        Resource = "arn:aws:s3:::${local.media_bucket}/quarantine/*"
      },
      {
        Sid      = "ReadReadyObjectsForPostWriteVerification"
        Effect   = "Allow"
        Action   = "s3:GetObject"
        Resource = "arn:aws:s3:::${local.media_bucket}/ready/*"
      },
      {
        Sid      = "AllowWriteReadyOnly"
        Effect   = "Allow"
        Action   = ["s3:PutObject", "s3:PutObjectTagging"]
        Resource = "arn:aws:s3:::${local.media_bucket}/ready/*"
      },
      {
        Sid      = "AllowProcessingQueueOnly"
        Effect   = "Allow"
        Action   = ["sqs:ReceiveMessage", "sqs:DeleteMessage", "sqs:ChangeMessageVisibility", "sqs:GetQueueAttributes"]
        Resource = "arn:aws:sqs:${local.region}:${local.account_id}:${local.processing_queue}"
      },
      {
        Sid      = "AllowProcessorLogsOnly"
        Effect   = "Allow"
        Action   = ["logs:CreateLogStream", "logs:PutLogEvents"]
        Resource = "arn:aws:logs:${local.region}:${local.account_id}:log-group:/aws/lambda/${local.processor_name}:*"
      }
    ]
  })
}

resource "aws_iam_role" "processor" {
  name                 = "TNetC3MediaProcessor"
  description          = "Allows Lambda functions to call AWS services on your behalf."
  max_session_duration = 3600
  permissions_boundary = aws_iam_policy.processor_boundary.arn

  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [{
      Effect    = "Allow"
      Principal = { Service = "lambda.amazonaws.com" }
      Action    = "sts:AssumeRole"
    }]
  })
}

resource "aws_iam_role_policy" "processor" {
  name = "TNetC3MediaProcessorRuntime"
  role = aws_iam_role.processor.id

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid      = "ReadQuarantineObjects"
        Effect   = "Allow"
        Action   = ["s3:GetObject", "s3:GetObjectVersion"]
        Resource = "arn:aws:s3:::${local.media_bucket}/quarantine/*"
      },
      {
        Sid      = "WriteReadyObjects"
        Effect   = "Allow"
        Action   = ["s3:PutObject", "s3:PutObjectTagging"]
        Resource = "arn:aws:s3:::${local.media_bucket}/ready/*"
      },
      {
        Sid      = "ReadReadyObjectsForPostWriteVerification"
        Effect   = "Allow"
        Action   = "s3:GetObject"
        Resource = "arn:aws:s3:::${local.media_bucket}/ready/*"
      },
      {
        Sid      = "DeleteProcessedQuarantineObjects"
        Effect   = "Allow"
        Action   = "s3:DeleteObject"
        Resource = "arn:aws:s3:::${local.media_bucket}/quarantine/*"
      },
      {
        Sid      = "ConsumeProcessingQueue"
        Effect   = "Allow"
        Action   = ["sqs:ReceiveMessage", "sqs:DeleteMessage", "sqs:ChangeMessageVisibility", "sqs:GetQueueAttributes"]
        Resource = "arn:aws:sqs:${local.region}:${local.account_id}:${local.processing_queue}"
      },
      {
        Sid      = "WriteBoundedLogs"
        Effect   = "Allow"
        Action   = ["logs:CreateLogStream", "logs:PutLogEvents"]
        Resource = "arn:aws:logs:${local.region}:${local.account_id}:log-group:/aws/lambda/${local.processor_name}:*"
      }
    ]
  })
}

resource "aws_cloudwatch_log_group" "processor" {
  name              = "/aws/lambda/${local.processor_name}"
  retention_in_days = 7
}

resource "aws_lambda_function" "processor" {
  function_name                  = local.processor_name
  role                           = aws_iam_role.processor.arn
  package_type                   = "Image"
  image_uri                      = local.processor_image
  architectures                  = ["arm64"]
  memory_size                    = 2048
  timeout                        = 60
  reserved_concurrent_executions = 2

  ephemeral_storage {
    size = 512
  }

  environment {
    variables = {
      C3_MEDIA_BUCKET = local.media_bucket
    }
  }

  tracing_config {
    mode = "PassThrough"
  }

  logging_config {
    log_format = "Text"
    log_group  = "/aws/lambda/${local.processor_name}"
  }
}

resource "aws_lambda_event_source_mapping" "processor" {
  event_source_arn                   = aws_sqs_queue.processing.arn
  function_name                      = aws_lambda_function.processor.arn
  batch_size                         = 1
  maximum_batching_window_in_seconds = 0
  function_response_types            = ["ReportBatchItemFailures"]
  enabled                            = true
}

resource "aws_cloudfront_origin_access_control" "media" {
  name                              = "tnet-c3-media-ready-oac"
  description                       = "C3 media ready S3 origin access control"
  origin_access_control_origin_type = "s3"
  signing_behavior                  = "always"
  signing_protocol                  = "sigv4"
}

resource "aws_cloudfront_distribution" "media" {
  enabled         = true
  is_ipv6_enabled = true
  comment         = "TNet C3 public ready media"
  price_class     = "PriceClass_100"
  aliases         = [local.media_host]

  origin {
    domain_name              = "${local.media_bucket}.s3.${local.region}.amazonaws.com"
    origin_id                = "C3MediaS3Origin"
    origin_path              = "/ready"
    origin_access_control_id = aws_cloudfront_origin_access_control.media.id
  }

  default_cache_behavior {
    target_origin_id       = "C3MediaS3Origin"
    viewer_protocol_policy = "redirect-to-https"
    allowed_methods        = ["GET", "HEAD"]
    cached_methods         = ["GET", "HEAD"]
    compress               = true
    cache_policy_id        = "658327ea-f89d-4fab-a63d-7e88639e58f6"
  }

  restrictions {
    geo_restriction {
      restriction_type = "none"
    }
  }

  viewer_certificate {
    acm_certificate_arn      = "arn:aws:acm:us-east-1:${local.account_id}:certificate/f976179d-4f1c-4efd-970e-05cb73b8c47b"
    minimum_protocol_version = "TLSv1.2_2021"
    ssl_support_method       = "sni-only"
  }

  tags = {
    Project     = "TeachersNet"
    Component   = "C3Media"
    Environment = "CommunityV1"
  }
}

resource "aws_route53_record" "media" {
  zone_id = "Z3U32UPCTZ7GK7"
  name    = local.media_host
  type    = "A"

  alias {
    name                   = "dqmsvj26oip2t.cloudfront.net."
    zone_id                = "Z2FDTNDATAQYW2"
    evaluate_target_health = false
  }
}
