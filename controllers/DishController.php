<?php
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/services/DishService.php";
    include_once "BasicController.php";

    class DishController extends BasicController{

        public static function getResponse($method, $urlList, $requestData) {
            $response = self::setResponse($method, $urlList, $requestData);
            return json_encode($response);
        }
        private static function setResponse($method, $urlList, $requestData) {
            if(isset(getallheaders()["Authorization"])) {
                $token = explode(" ", getallheaders()["Authorization"])[1];
            }
            switch($method) {
                case "GET":
                    if(empty($urlList)) {
                        return DishService::getDishList($requestData->params);
                    }
                case "POST":
                    return array();
            }
        }
    }
?>