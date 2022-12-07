<?php
    class BasicResponse {
        private $status;
        private $message;
        public function __construct($message, $code = null) {
            $this->status = $code;
            $this->message = $message;
        }

        public function getData() {
            return array(
                "status" => $this->status,
                "message" => $this->message
            );
        }
    }
?>