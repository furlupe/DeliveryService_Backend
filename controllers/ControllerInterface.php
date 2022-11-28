<?php
    interface IController {
        public static function getResponse($method, $urlList, $requestData);
        public static function setResponse($method, $urlList, $requestData);
    }
?>
