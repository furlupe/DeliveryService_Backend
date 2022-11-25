<?php
    include_once "ExtendedExceptionInterface.php";
    class InvalidDataException extends Exception implements IExtendedException{
        private $data;
        public function __construct($message, $code = 0, $extras = null, Throwable $prev = null) {
            parent::__construct($message, $code, $prev);
            $this->data = $extras;
        }

        public function getData() {
            return $this->data;
        }
    }
?>