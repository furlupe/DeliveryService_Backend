<?php
    include_once "BasicEException.php";
    class InvalidDataException extends BasicEException{
        private $code = '400';
        private $message = 'Bad request';
        
    }
?>