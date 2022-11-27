<?php
    include_once "ExtendedExceptionInterface.php";
    class URLParametersException extends Exception implements IExtendedException{
        private $data;
        const code = '400';
        const message = 'One or more parameters are wrong';
        public function __construct($message = self::message, $code = self::code, $extras = null, Throwable $prev = null) {
            parent::__construct($message, $code, $prev);
            $this->data = $extras;
        }

        public function getData() {
            return $this->data;
        }
    }
?>