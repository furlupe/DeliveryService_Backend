<?php
    class Token {
        public static function forbidToken($email, $token) {
            $id = self::getIdByEmail($email)['id'];
            $GLOBALS["LINK"]->query(
                "INSERT INTO BLACKLIST(value, userID) VALUES ('$token', '$id')"
            );
        }

        public static function generateJWT($payload, $secret="morbius") : string {
            $headers = array(
                "alg" => "HS256",
                "typ" => "JWT"
            );
            $headers_enc = self::base64url_encode(json_encode($headers));
            $payload_enc = self::base64url_encode(json_encode($payload));
    
            $signature = hash_hmac('SHA256', "$headers_enc.$payload_enc", $secret, true);
            $signature_enc = self::base64url_encode($signature);
    
            return "$headers_enc.$payload_enc.$signature_enc";
        }
        
        private static function base64url_encode($str) : string {
            return str_replace(
                ['+', '/', '='],
                ['-', '_', ''],
                base64_encode($str)
            );
        }

        private static function getIdByEmail($email) {
            return $GLOBALS["LINK"]->query(
                "SELECT id FROM USERS WHERE email='$email'"
            )->fetch_assoc();
        }
    }
?>