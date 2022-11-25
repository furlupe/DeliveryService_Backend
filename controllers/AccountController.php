<?php
    include_once dirname(__DIR__, 1)."/services/AccountService.php";
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    class AccountController {
        public static function getResponse($method, $urlList, $requestData) {
            if (!$urlList) {
                throw new NonExistingURLException(
                    "URL doesn't exists", 
                    "404"
                );
            }
            $response = self::setResponse($method, $urlList, $requestData);
            return json_encode($response);
        }

        private static function setResponse($method, $urlList, $requestData) {
            switch($method) {
                case "GET":
                    switch($urlList[0]) {
                        case "profile":
                            return;
                        default:
                            throw new NonExistingURLException(
                                "URL doesn't exists",
                                "404"
                            );
                    }

                case "POST":
                    switch($urlList[0]) {
                        case "register":                             
                            return AccountService::register($requestData->body);
                        case "login":                             
                            return AccountService::login($requestData->body);
                        case "logout":                             
                            return AccountService::logout(
                                explode(" ", getallheaders()["Authorization"])[1]
                            );
                        default:
                            throw new NonExistingURLException(
                                "URL doesn't exists",
                                "404"
                            );
                    }
                    
                default:
                    throw new NonExistingURLException(
                        "URL doesn't exists", 
                        "404"
                    );
            }
        }
    }
?>