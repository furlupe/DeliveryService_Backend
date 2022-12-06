<?php
    class TokenQueries {
        public static function forbidToken($token) {
            $GLOBALS["LINK"]->query(
                "INSERT INTO BLACKLIST(value) VALUES (?)",
                $token
            );
        }

        public static function getBlacklistedToken($token) {
            return $GLOBALS["LINK"]->query(
                "SELECT * FROM BLACKLIST WHERE value=?",
                $token
            )->fetch_assoc();
        }
    }
?>