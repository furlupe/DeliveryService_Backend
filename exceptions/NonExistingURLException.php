<?php
    include_once "BasicEException.php";
    class NonExistingURLException extends BasicEException {
        private $code = '404';
        private $message = 'URL does not exist';
    }
?>