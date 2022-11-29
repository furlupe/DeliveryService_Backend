<?php
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/exceptions/URLParametersException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/services/BasketService.php";
    include_once dirname(__DIR__, 1)."/utils/regexFormatting.php";
    include_once "BasicController.php";
    class BasketController extends BasicController {
        protected function setResponse($method, $urlList, $requestData) {
            switch($method) {
                case "GET":
                    if (!empty($urlList)) {
                        throw new NonExistingURLException();
                    }
                    return BasketService::getBasket();

                case "DELETE":
                case "POST":
                    if(empty($urlList)) {
                        throw new NonExistingURLException(); 
                    }

                    $r = joinRegex("/^dish\//", $GLOBALS["UUID_REGEX"]);
                    $r = joinRegex($r, "/$/");
                    if (!preg_match($r, implode("/",$urlList))) {
                        throw new NonExistingURLException();
                    }

                    if (strcmp($method, "DELETE") == 0) {
                        if (!isset($requestData->params['increase']) 
                        || empty($requestData->params['increase'])) {
                            throw new URLParametersException(
                                extras: array("errors" => array(
                                    "increase" => "increase must be specified"
                                ))
                            );
                        }

                        return BasketService::removeDish(
                            $urlList[1], 
                            filter_var($requestData->params['increase'], FILTER_VALIDATE_BOOLEAN)
                        );
                    }

                    return BasketService::addDish($urlList[1]);
                default:
                    throw new NonExistingURLException();
            }
        }
    }
?>