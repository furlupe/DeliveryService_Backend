<?php
    include_once "BasicController.php";
    include_once dirname(__DIR__, 1)."/exceptions/NonExistingURLException.php";
    include_once dirname(__DIR__, 1)."/services/OrderService.php";
    include_once dirname(__DIR__, 1)."/utils/regexFormatting.php";


    class OrderController extends BasicController {
        protected function setResponse($method, $urlList, $requestData) {
            switch($method) {
                case "GET":
                    if (empty($urlList)) {
                        return OrderService::getOrders();
                    }

                    if (!preg_match($GLOBALS["UUID_REGEX"], $urlList[0])) {
                        throw new NonExistingURLException();
                    }

                    return OrderService::getOrder($urlList[0]);
                case "POST":
                    if (empty($urlList)) {
                        return OrderService::createOrder($requestData->body);
                    }
                    return array();
                default:
                    throw new NonExistingURLException();
            }
        }
    }
?>