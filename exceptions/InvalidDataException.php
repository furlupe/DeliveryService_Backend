<?php
    include_once "BasicEException.php";
    class InvalidDataException extends BasicEException{
        protected $code = '400';
        protected $message = 'Bad request';
        
    }
?>