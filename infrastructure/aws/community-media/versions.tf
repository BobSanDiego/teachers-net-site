terraform {
  required_version = ">= 1.12.0"

  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = ">= 6.0, < 7.0"
    }
  }

  backend "s3" {
    bucket  = "tnet-c3-media-state-553830187994-us-west-2"
    key     = "community-media/terraform.tfstate"
    region  = "us-west-2"
    encrypt = true
    profile = "tnet-c3-media-iac-tofu"
  }
}

provider "aws" {
  region  = "us-west-2"
  profile = "tnet-c3-media-iac-tofu"
}
