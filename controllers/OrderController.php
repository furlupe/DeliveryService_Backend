<?php
    include_once "BasicController.php";
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/services/OrderService.php";

    class OrderController extends BasicController {
        protected function setResponse($method, $urlList, $requestData) {
            switch($method) {
                case "GET":
                    if (empty($urlList)) {
                        OrderService::getOrders();
                    }
                case "POST":
                    return array();
                default:
                    throw new InvalidDataException(
                        "Wrong request's method: '$method'"
                    );
            }
        }
    }
?>