<?php
    include_once dirname(__DIR__, 1)."/helpers/Token.php";
    class AccountService {
        public static function logout($token) {
            $GLOBALS["LINK"]->query(
                "INSERT INTO BLACKLIST(value) VALUES ('$token')"
            );
            return array("message" => "logout successful");
        }
    }
?>
