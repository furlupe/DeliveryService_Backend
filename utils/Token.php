<?php
    class Token {
        private static $key = "morbius";
        public static function forbidToken($token) {
            $GLOBALS["LINK"]->query(
                "INSERT INTO BLACKLIST(value) VALUES (?)",
                $token
            );
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
            
            if(!self::getIdByEmail($decoded['payload']['email'])) {
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

            if($GLOBALS["LINK"]->query(
                "SELECT * FROM BLACKLIST WHERE value=?",
                array($token)
            )->num_rows()) {
                return null;
            }

            return $decoded["payload"]['email'];
        }

        public static function getIdFromToken($token) {
            $email = self::getEmailFromToken($GLOBALS["USER_TOKEN"]);

            return self::getIdByEmail($email);
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
            return $GLOBALS["LINK"]->query(
                    "SELECT id FROM USERS WHERE email=?",
                    array($email)
                )->fetch_assoc()["id"];
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
