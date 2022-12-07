<?php
    include_once "BasicEException.php";
    class URLParametersException extends BasicEException{
        protected $code = '400';
        protected $message = 'One or more parameters are wrong';
        protected $data = array("errors" => array());

        public function __construct($message = null, $extras = null) {
            $message = ($message) ? $message : $this->message;
            Exception::__construct($message, $this->code,);
            $this->data["errors"] = $extras;
        }
    }
?>
