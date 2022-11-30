<?php
    include_once "BasicEException.php";
    class AuthException extends BasicEException {
        private $code = "401";
        private $message = "Authorization required";
    }
?>