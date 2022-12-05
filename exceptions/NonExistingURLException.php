<?php
    include_once "BasicEException.php";
    class NonExistingURLException extends BasicEException {
        protected $code = '404';
        protected $message = 'URL does not exist';
    }
?>