<?php
    include_once dirname(__DIR__, 1) . "/queries/TokenQueries.php";
    include_once dirname(__DIR__, 1) . "/queries/AccountQueries.php";
    class Token {
        private static $key = "morbius";
        public static function forbidToken($token) {
            TokenQueries::forbidToken($token);
        }

        public static function generateJWT($payload) : string {
            $headers = array(
                "alg" => "HS256",
                "typ" => "JWT"
            );
            $headers_enc = self::base64url_encode(json_encode($headers));
            $payload_enc = self::base64url_encode(json_encode($payload));
    
            $signature = self::makeSignature($headers_enc, $payload_enc);
    
            return "$headers_enc.$payload_enc.$signature";
        }
        
        public static function getEmailFromToken($token) {
            $decoded = self::decodeToken($token);

            if(!$decoded) return null;
            
            if(!AccountQueries::getUser($decoded['payload']['email'])["id"]) {
                return null;
            }

            $headers_enc = self::base64url_encode(json_encode($decoded['headers']));
            $payload_enc = self::base64url_encode(json_encode($decoded['payload']));

            $hash = self::makeSignature($headers_enc, $payload_enc);
            
            if(strcmp($hash, $decoded["signature"]) != 0) {
                return null;
            }

            $now = strtotime((new DateTime())->format('Y-m-d H:i:s'));
            $tokenExp = strtotime($decoded['payload']['exp']);
            if ($now > $tokenExp) {
                return null;
            }

            if(TokenQueries::getBlacklistedToken($token)) {
                return null;
            }

            return $decoded["payload"]['email'];
        }

        public static function getIdFromToken($token) {
            $email = self::getEmailFromToken($GLOBALS["USER_TOKEN"]);

            return AccountQueries::getUser($email)["id"];
        }
        private static function base64url_encode($str) : string {
            return str_replace(
                ['+', '/', '='],
                ['-', '_', ''],
                base64_encode($str)
            );
        }

        private static function decodeToken($token) {
            $parts = array_combine(["headers", "payload", "signature"], explode(".", $token));

            $parts["headers"] = json_decode(base64_decode($parts["headers"]), true);
            $parts["payload"] = json_decode(base64_decode($parts["payload"]), true);
            return $parts;
        }

        private static function getIdByEmail($email) {
            return AccountQueries::getUser($email)["id"];
        }

        private static function makeSignature($headers, $payload) {
            $hash = hash_hmac(
                'SHA256',
                "$headers.$payload",
                self::$key,
                true
            );

            return self::base64url_encode($hash);
        }
    }
?>
