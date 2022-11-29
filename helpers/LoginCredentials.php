<?php
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    class LoginCredentials {
        public static function checkExistance($email, $password) {
            $password = hash("sha1", $password);
            if(!$GLOBALS["LINK"]->query(
                "SELECT *
                FROM USERS
                WHERE email=? AND password=?",
                array($email, $password)
            )) {
                    throw new InvalidDataException("Wrong E-mail or password", "400");
                }
            return true;
        }
    }
?>