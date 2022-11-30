<?php
    include_once "headers.php";
    class BasicResponse {
        private $status;
        private $message;
        public function __construct($message, $code = "200") {
            $this->status = determineStatus($code);
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