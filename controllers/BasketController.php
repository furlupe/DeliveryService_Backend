<?php
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/services/BasketService.php";
    include_once dirname(__DIR__, 1)."/helpers/regexFormatting.php";
    include_once "BasicController.php";
    class BasketController extends BasicController {
        protected function setResponse($method, $urlList, $requestData) {
            switch($method) {
                case "GET":
                    if (!empty($urlList)) {
                        throw new NonExistingURLException();
                    }
                    return BasketService::getBasket();
                case "POST":
                    if(empty($urlList)) {
                        throw new NonExistingURLException(); 
                    }

                    $r = joinRegex("/^dish\//", $GLOBALS["UUID_REGEX"]);
                    $r = joinRegex($r, "/$/");
                    if (!preg_match($r, implode("/",$urlList))) {
                        throw new NonExistingURLException();
                    }
                    return BasketService::addDish($urlList[1]);
            }
        }
    }
?>