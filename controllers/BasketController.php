<?php
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/services/BasketService.php";
    include_once dirname(__DIR__, 1)."/helpers/regexFormatting.php";
    include_once "ControllerInterface.php";
    class BasketController implements IController {
        public static function getResponse($method, $urlList, $requestData) {
            $response = self::setResponse($method, $urlList, $requestData);
            return json_encode($response);
        }

        public static function setResponse($method, $urlList, $requestData) {
            switch($method) {
                case "GET":
                    if (!empty($urlList)) {
                        throw new NonExistingURLException();
                    }
                    return BasketService::getBasket();
                case "POST":
                    return array();
            }
        }
    }
?>