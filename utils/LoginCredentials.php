<?php
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/queries/LoginQueries.php";
    class LoginCredentials {
        public static function checkExistance($email, $password) {
            $password = hash("sha1", $password);
            if(!LoginQueries::checkUser($email, $password)) {
                throw new InvalidDataException("Wrong E-mail or password", "400");
            }
            return true;
        }
    }
?>