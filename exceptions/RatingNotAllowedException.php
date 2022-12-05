<?php
    include_once "BasicEException.php";
    class RatingNotAllowedException extends BasicEException {
        protected $code = '403';
        protected $message = 'User is not allowed to set the rating';
    }
?>