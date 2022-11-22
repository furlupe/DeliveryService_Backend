<?php
    class Token {
        public static function storeTokenOnEmail($email, $token) {
            $id = self::getIdByEmail($email)['id'];
            Query::connect()->query(
                "INSERT INTO TOKENS(value, userID) VALUES ('$token', '$id')"
            );
        }

        private static function getIdByEmail($email) {
            return Query::connect()->query(
                "SELECT id FROM USERS WHERE email='$email'"
            )->fetch_assoc();
        }
    }
?>