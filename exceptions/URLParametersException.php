<?php
    include_once "BasicEException.php";
    class URLParametersException extends BasicEException{
        protected $code = '400';
        protected $message = 'One or more parameters are wrong';
    }
?>