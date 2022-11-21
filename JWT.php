<?php
    class JWT {
        private $token;
        public function __construct($headers, $payload, $secret="morbius") {
            $headers_enc = self::base64url_encode(json_encode($headers));
            $payload_enc = self::base64url_encode(json_encode($payload));

            $signature = hash_hmac('SHA256', "$headers_enc.$payload_enc", $secret, true);
            $signature_enc = self::base64url_encode($signature);

            $this->token = "$headers_enc.$payload_enc.$signature_enc";
        }
        public function getToken() {
            return $this->token;
        }
        private static function base64url_encode($str) {
            return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
        }
    }
?>