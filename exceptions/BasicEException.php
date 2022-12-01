<?php
    include_once "ExtendedExceptionInterface.php";
    include_once dirname(__DIR__, 1)."/utils/headers.php";
    class BasicEException extends Exception implements IExtendedException {
        protected $data;
        protected $code;
        protected $message;
        public function __construct($message = null, $extras = null) {
            $message = ($message) ? $message : $this->message;
            parent::__construct($message, $this->code, null);
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