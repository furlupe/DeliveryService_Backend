<?php
    include_once "ExtendedExceptionInterface.php";
    include_once dirname(__DIR__, 1)."/utils/headers.php";
    class BasicEException extends Exception implements IExtendedException {
        private $data;
        private $code;
        private $message;
        public function __construct($message = $this->message, $code = $this->code, $extras = null, Throwable $prev = null) {
            parent::__construct($message, $code, $prev);
            $this->data = $extras;
        }

        public function getData() {
            return $this->data;
        }

        public function sendHTTP() {
            setHTPPStatus(
                $this->code,
                $this->message,
                $this->data
            );
        }
    }
?>