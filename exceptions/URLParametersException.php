<?php
    include_once "BasicEException.php";
    class URLParametersException extends BasicEException{
        private $code = '400';
        private $message = 'One or more parameters are wrong';
    }
?>