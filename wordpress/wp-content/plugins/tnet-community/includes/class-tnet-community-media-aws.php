<?php
defined('ABSPATH') || exit;

/**
 * Small, dependency-free AWS bridge for the approved Sandy workload path and
 * the explicitly local DDEV QA path. Credentials are obtained from IMDS or a
 * host-side credential-process socket, exchanged through STS, and retained
 * only in this PHP request while a presigned POST is produced.
 */
final class TNet_Community_Media_Aws {
    private const REGION = 'us-west-2';
    private const ROLE_ARN = 'arn:aws:iam::553830187994:role/TNetC3MediaApplicationSigner';
    private const BUCKET = 'tnet-c3-media-553830187994-us-west-2';
    private const LOCAL_SOCKET = '/run/tnet-c3-media/credentials.sock';
    private const MAX_BYTES = 10485760;
    private static ?array $session = null;

    public static function presigned_post(string $key, string $mime, int $size): array {
        if (!preg_match('~^quarantine/[A-Za-z0-9][A-Za-z0-9._-]{0,127}/original\.(jpg|jpeg|png|webp)$~', $key)) {
            throw new InvalidArgumentException('MEDIA_KEY_INVALID');
        }
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true) || $size < 1 || $size > self::MAX_BYTES) {
            throw new InvalidArgumentException('MEDIA_UPLOAD_CONTRACT_INVALID');
        }
        $session = self::session();
        $now = time();
        $amz_date = gmdate('Ymd\THis\Z', $now);
        $date = gmdate('Ymd', $now);
        $credential = $session['access_key'] . '/' . $date . '/' . self::REGION . '/s3/aws4_request';
        $policy = [
            'expiration' => gmdate('Y-m-d\TH:i:s\Z', $now + 600),
            'conditions' => [
                ['eq', '$bucket', self::bucket()],
                ['eq', '$key', $key],
                ['eq', '$Content-Type', $mime],
                ['content-length-range', 1, self::MAX_BYTES],
                ['eq', '$x-amz-server-side-encryption', 'AES256'],
                ['eq', '$success_action_status', '201'],
                ['eq', '$x-amz-date', $amz_date],
                ['eq', '$x-amz-security-token', $session['token']],
                ['eq', '$x-amz-algorithm', 'AWS4-HMAC-SHA256'],
                ['eq', '$x-amz-credential', $credential],
            ],
        ];
        $encoded_policy = base64_encode(wp_json_encode($policy, JSON_UNESCAPED_SLASHES));
        $signing_key = self::hmac('AWS4' . $session['secret_key'], $date);
        $signing_key = self::hmac($signing_key, self::REGION);
        $signing_key = self::hmac($signing_key, 's3');
        $signing_key = self::hmac($signing_key, 'aws4_request');
        return [
            'url' => 'https://' . self::bucket() . '.s3.' . self::REGION . '.amazonaws.com/',
            'fields' => [
                'key' => $key,
                'bucket' => self::bucket(),
                'Content-Type' => $mime,
                'x-amz-server-side-encryption' => 'AES256',
                'success_action_status' => '201',
                'x-amz-algorithm' => 'AWS4-HMAC-SHA256',
                'x-amz-credential' => $credential,
                'x-amz-date' => $amz_date,
                'x-amz-security-token' => $session['token'],
                'policy' => $encoded_policy,
                'x-amz-signature' => hash_hmac('sha256', $encoded_policy, $signing_key),
            ],
            'expires_at' => gmdate('c', $now + 600),
        ];
    }

    private static function session(): array {
        if (is_array(self::$session) && (int) self::$session['expires_at'] > time() + 60) return self::$session;
        $base = self::base_credentials();
        $body = self::sts_request($base, [
            'Action' => 'AssumeRole',
            'DurationSeconds' => '3600',
            'RoleArn' => self::role_arn(),
            'RoleSessionName' => self::session_name(),
            'Version' => '2011-06-15',
        ]);
        $xml = @simplexml_load_string($body);
        $xml = $xml ? $xml->children('https://sts.amazonaws.com/doc/2011-06-15/') : false;
        if (!$xml || empty($xml->AssumeRoleResult->Credentials)) throw new RuntimeException('MEDIA_SIGNER_ASSUME_FAILED');
        $credentials = $xml->AssumeRoleResult->Credentials;
        self::$session = [
            'access_key' => (string) $credentials->AccessKeyId,
            'secret_key' => (string) $credentials->SecretAccessKey,
            'token' => (string) $credentials->SessionToken,
            'expires_at' => strtotime((string) $credentials->Expiration),
        ];
        if (self::$session['access_key'] === '' || self::$session['secret_key'] === '' || self::$session['token'] === '') throw new RuntimeException('MEDIA_SIGNER_CREDENTIALS_INVALID');
        return self::$session;
    }

    private static function imds_credentials(): array {
        $token = self::http('http://169.254.169.254/latest/api/token', 'PUT', ['X-aws-ec2-metadata-token-ttl-seconds: 21600']);
        $role = trim(self::http('http://169.254.169.254/latest/meta-data/iam/security-credentials/', 'GET', ['X-aws-ec2-metadata-token: ' . $token]));
        if ($role === '') throw new RuntimeException('MEDIA_INSTANCE_ROLE_UNAVAILABLE');
        $json = json_decode(self::http('http://169.254.169.254/latest/meta-data/iam/security-credentials/' . rawurlencode($role), 'GET', ['X-aws-ec2-metadata-token: ' . $token]), true);
        if (!is_array($json) || empty($json['AccessKeyId']) || empty($json['SecretAccessKey']) || empty($json['Token'])) throw new RuntimeException('MEDIA_INSTANCE_CREDENTIALS_INVALID');
        return ['access_key' => (string) $json['AccessKeyId'], 'secret_key' => (string) $json['SecretAccessKey'], 'token' => (string) $json['Token']];
    }

    private static function base_credentials(): array {
        $mode = (string) (getenv('C3_MEDIA_RUNTIME_MODE') ?: 'production');
        $provider = (string) (getenv('C3_MEDIA_CREDENTIAL_PROVIDER') ?: 'imds');
        if ($mode === 'production' && $provider !== 'imds') throw new RuntimeException('MEDIA_LOCAL_PROVIDER_FORBIDDEN_IN_PRODUCTION');
        if ($mode === 'local') {
            if (getenv('IS_DDEV_PROJECT') !== 'true' || $provider !== 'unix_socket') throw new RuntimeException('MEDIA_LOCAL_PROVIDER_REQUIRES_DDEV');
            return self::local_socket_credentials();
        }
        if ($mode !== 'production' || $provider !== 'imds') throw new RuntimeException('MEDIA_RUNTIME_CONFIGURATION_INVALID');
        return self::imds_credentials();
    }

    private static function local_socket_credentials(): array {
        $handle = @stream_socket_client('unix://' . self::LOCAL_SOCKET, $error_code, $error_message, 2, STREAM_CLIENT_CONNECT);
        if (!is_resource($handle)) throw new RuntimeException('MEDIA_LOCAL_CREDENTIAL_PROVIDER_UNAVAILABLE');
        stream_set_timeout($handle, 5);
        $request = wp_json_encode(['version' => 1]) . "\n";
        if (@fwrite($handle, $request) !== strlen($request)) {
            fclose($handle);
            throw new RuntimeException('MEDIA_LOCAL_CREDENTIAL_PROVIDER_FAILED');
        }
        $line = @fgets($handle, 16385);
        fclose($handle);
        if (!is_string($line) || strlen($line) > 16384) throw new RuntimeException('MEDIA_LOCAL_CREDENTIAL_PROVIDER_FAILED');
        $json = json_decode($line, true);
        if (!is_array($json) || empty($json['AccessKeyId']) || empty($json['SecretAccessKey']) || empty($json['SessionToken']) || empty($json['Expiration'])) throw new RuntimeException('MEDIA_LOCAL_CREDENTIALS_INVALID');
        return ['access_key' => (string) $json['AccessKeyId'], 'secret_key' => (string) $json['SecretAccessKey'], 'token' => (string) $json['SessionToken']];
    }

    private static function session_name(): string {
        $prefix = getenv('C3_MEDIA_RUNTIME_MODE') === 'local' ? 'tnet-c3-media-local-' : 'tnet-c3-media-app-';
        return $prefix . substr(hash('sha256', wp_generate_uuid4()), 0, 16);
    }

    private static function sts_request(array $credentials, array $params): string {
        ksort($params);
        $payload = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $host = 'sts.' . self::REGION . '.amazonaws.com';
        $amz_date = gmdate('Ymd\THis\Z');
        $date = gmdate('Ymd');
        $headers = ['content-type' => 'application/x-www-form-urlencoded; charset=utf-8', 'host' => $host, 'x-amz-date' => $amz_date, 'x-amz-security-token' => $credentials['token']];
        ksort($headers);
        $canonical_headers = '';
        foreach ($headers as $name => $value) $canonical_headers .= $name . ':' . trim($value) . "\n";
        $signed = implode(';', array_keys($headers));
        $canonical = "POST\n/\n\n" . $canonical_headers . "\n" . $signed . "\n" . hash('sha256', $payload);
        $scope = $date . '/' . self::REGION . '/sts/aws4_request';
        $key = self::hmac('AWS4' . $credentials['secret_key'], $date);
        $key = self::hmac($key, self::REGION);
        $key = self::hmac($key, 'sts');
        $key = self::hmac($key, 'aws4_request');
        $signature = hash_hmac('sha256', "AWS4-HMAC-SHA256\n{$amz_date}\n{$scope}\n" . hash('sha256', $canonical), $key);
        $headers['authorization'] = 'AWS4-HMAC-SHA256 Credential=' . $credentials['access_key'] . '/' . $scope . ', SignedHeaders=' . $signed . ', Signature=' . $signature;
        return self::http('https://' . $host . '/', 'POST', array_map(static fn($name, $value) => $name . ': ' . $value, array_keys($headers), array_values($headers)), $payload);
    }

    private static function http(string $url, string $method, array $headers = [], string $body = ''): string {
        if (!function_exists('curl_init')) throw new RuntimeException('MEDIA_HTTP_CLIENT_UNAVAILABLE');
        $handle = curl_init($url);
        curl_setopt_array($handle, [CURLOPT_CUSTOMREQUEST => $method, CURLOPT_HTTPHEADER => $headers, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 8, CURLOPT_CONNECTTIMEOUT => 2, CURLOPT_POSTFIELDS => $body, CURLOPT_FAILONERROR => false]);
        $result = curl_exec($handle);
        $code = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        curl_close($handle);
        if (!is_string($result) || $code < 200 || $code >= 300) throw new RuntimeException('MEDIA_AWS_REQUEST_FAILED');
        return $result;
    }

    private static function hmac(string $key, string $data): string { return hash_hmac('sha256', $data, $key, true); }
    private static function bucket(): string { return (string) (getenv('C3_MEDIA_BUCKET') ?: self::BUCKET); }
    private static function role_arn(): string { return (string) (getenv('C3_MEDIA_SIGNER_ROLE_ARN') ?: self::ROLE_ARN); }
}
