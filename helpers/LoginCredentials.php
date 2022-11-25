<?php
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    class LoginCredentials {
        public static function checkExistance($email, $password) {
            if(!$GLOBALS["LINK"]->query(
                "SELECT id 
                FROM USERS 
                WHERE email='$email' AND password='$password'")->num_rows) {
                    throw new InvalidDataException("Wrong E-mail or password", "400");
                }
            return true;
        }
    }
?>