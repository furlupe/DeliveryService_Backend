<?php
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/services/DishService.php";
    include_once "ControllerInterface.php";

    class DishController implements IController{

        public static function getResponse($method, $urlList, $requestData) {
            $response = self::setResponse($method, $urlList, $requestData);
            return json_encode($response);
        }
        public static function setResponse($method, $urlList, $requestData) {
            if(isset(getallheaders()["Authorization"])) {
                $token = explode(" ", getallheaders()["Authorization"])[1];
            }

            switch($method) {
                case "GET":
                    if(empty($urlList)) {
                        return DishService::getDishList($requestData->params);
                    }
                    if (!preg_match($GLOBALS["UUID_REGEX"], $urlList[0])) {
                        throw new NonExistingURLException();
                    }
                    return DishService::getDish($urlList[0]);
                case "POST":
                    return array();
            }
        }
    }
?>