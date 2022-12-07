<?php
    include_once dirname(__DIR__, 1)."/services/AccountService.php";
    include_once "BasicController.php";
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    class AccountController extends BasicController {

        public function getResponse($method, $urlList, $requestData) {
            if (!$urlList) {
                throw new NonExistingURLException();
            }
            $response = self::setResponse($method, $urlList, $requestData);
            return json_encode($response);
        }

        protected function setResponse($method, $urlList, $requestData) {
            switch($method) {
                case "GET":
                    switch($urlList[0]) {
                        case "profile":
                            if(!isset($GLOBALS["USER_TOKEN"])) {
                                throw new AuthException();
                            }
                            return AccountService::getProfile($GLOBALS["USER_TOKEN"]);
                        default:
                            throw new NonExistingURLException();
                    }

                case "POST":
                    switch($urlList[0]) {
                        case "register":                             
                            return AccountService::register($requestData->body);
                        case "login":                             
                            return AccountService::login($requestData->body);
                        case "logout":                             
                            return AccountService::logout(
                                $GLOBALS["USER_TOKEN"]
                            );
                        default:
                            throw new NonExistingURLException(
                                "URL doesn't exists: /".implode("/", $urlList),
                                "404"
                            );
                        }
                case "PUT":
                    switch($urlList[0]) {
                        case "profile":
                            $data = $requestData->body;
                            $data->token = $GLOBALS["USER_TOKEN"];
                            return AccountService::editProfile($data);
                        default:
                            throw new NonExistingURLException(
                                "URL doesn't exists: /".implode("/", $urlList),
                                "404"
                            );
                    }
                default:
                    throw new NonExistingURLException();
            }
        }
    }
?>