<?php
    interface IController {
        public static function getResponse($method, $urlList, $requestData);
    }
?>