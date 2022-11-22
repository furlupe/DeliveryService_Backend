<?php
    class Response {
        private $status;
        private $message;

        public function __construct($status, $message) {
            $this->status = $status;
            $this->message = $message;
        }

        public function getJSON() {
            return json_encode(
                array(
                    "status" => $this->status,
                    "message" => $this->message
                )
            );
        }
    }
?>