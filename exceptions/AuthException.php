<?php
    include_once "ExtendedExceptionInterface.php";
    class AuthException extends Exception implements IExtendedException {
        private $data;
        const code = "401";
        const message = "Authorization required";
        public function __construct($message = self::message, $code = self::code, $extras = null, Throwable $prev = null) {
            parent::__construct($message, $code, $prev);
            $this->data = $extras;
        }

        public function getData() {
            return $this->data;
        }
    }
?>
