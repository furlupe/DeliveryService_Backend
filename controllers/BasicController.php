<?php
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once "ControllerInterface.php";

    class BasicController implements IController {
        public static function getResponse($method, $urlList, $requestData) {
            $response = self::setResponse($method, $urlList, $requestData);
            return json_encode($response);
        }

        private static function setResponse($method, $urlList, $requestData) : array {
            return array();
        }
    }
?>