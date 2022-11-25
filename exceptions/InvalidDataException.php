<?php
    include_once "ExtendedExceptionInterface.php";
    class InvalidDataException extends Exception implements IExtendedException{
        private $data;
        const code = '400';
        public function __construct($message, $code = self::code, $extras = null, Throwable $prev = null) {
            parent::__construct($message, $code, $prev);
            $this->data = $extras;
        }

        public function getData() {
            return $this->data;
        }
    }
?>