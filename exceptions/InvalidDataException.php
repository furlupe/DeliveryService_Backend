<?php
    class InvalidDataException extends Exception {
        public function __construct($message, $code = 0, Throwable $prev = null) {
            parent::__construct($message, $code, $prev);
        }

        public function __toString() {
            return __CLASS__."\n\tmessage: $this->message\n";
        }
    }
?>