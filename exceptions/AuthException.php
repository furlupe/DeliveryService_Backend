<?php
    include_once "BasicEException.php";
    class AuthException extends BasicEException {
        protected $code = "401";
        protected $message = "Authorization required";
    }
?>