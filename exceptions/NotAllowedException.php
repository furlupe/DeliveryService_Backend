<?php
    include_once "BasicEException.php";
    class NotAllowedException extends BasicEException {
        protected $code = '403';
        protected $message = '';
    }
?>