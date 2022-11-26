<?php
    include_once "ExtendedExceptionInterface.php";
    class NonExistingURLException extends Exception implements IExtendedException {
        private $data;
        const code = '404';
        const message = 'URL does not exist';
        public function __construct($message = self:: message, $code = self::code, $extras = null, Throwable $prev = null) {
            parent::__construct($message, $code, $prev);
            $this->data = $extras;
        }

        public function getData() {
            return $this->data;
        }
    }
?>