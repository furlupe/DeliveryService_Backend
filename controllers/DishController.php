<?php
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/services/DishService.php";
    include_once dirname(__DIR__, 1)."/helpers/regexFormatting.php";
    include_once "ControllerInterface.php";

    class DishController implements IController{

        public static function getResponse($method, $urlList, $requestData) {
            $response = self::setResponse($method, $urlList, $requestData);
            return json_encode($response);
        }
        public static function setResponse($method, $urlList, $requestData) {
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
                    if(empty($urlList)) {
                        throw new NonExistingURLException(); 
                    }
                    if (!preg_match(joinRegex($GLOBALS["UUID_REGEX"], "/\/rating/"), implode("/",$urlList))) {
                        throw new NonExistingURLException();
                    }

                    return DishService::setRating($urlList[0], intval($requestData->params["ratingScore"]));
            }
        }
    }
?>