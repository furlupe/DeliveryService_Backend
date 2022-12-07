<?php
    include_once dirname(__DIR__, 1)."/utils/BasicResponse.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/utils/Token.php";
    include_once dirname(__DIR__, 1)."/models/OrderInfoDTO.php";
    include_once dirname(__DIR__, 1)."/models/OrderDTO.php";
    include_once dirname(__DIR__, 1)."/models/OrderModel.php";
    include_once dirname(__DIR__, 1)."/queries/OrderQueries.php";

    class OrderService {
        public static function getOrders() {
            if(is_null($GLOBALS["USER_ID"])) {
                throw new AuthException();
            }

            $orders = OrderQueries::getOrders($GLOBALS["USER_ID"]);
            
            $response = array();
            foreach($orders as $key => $value) {
                array_push(
                    $response, 
                    (new OrderInfoDTO($value))->getData()
                );
            }

            return $response;
        }

        public static function getOrder($id) {
            if(is_null($GLOBALS["USER_ID"])) {
                throw new AuthException();
            }

            $order = (new OrderDTO(
                $GLOBALS["USER_ID"], OrderQueries::getOrder($id)
            ))->getData();

            return $order;
        }

        public static function createOrder($data) {
            if(is_null($GLOBALS["USER_ID"])) {
                throw new AuthException();
            }

            $order = new OrderModel($data, $GLOBALS["USER_ID"]);
            $order->createOrder();
            
            return (new BasicResponse("Order created"))->getData();
        }

        public static function confirmOrder($id) {
            if(is_null($GLOBALS["USER_ID"])) {
                throw new AuthException();
            }

            $order = self::getOrder($id);
            if (empty($order)) {
                throw new NonExistingURLException();
            }
            OrderQueries::confirmOrder($GLOBALS["USER_ID"], $id);
            $dishes = OrderQueries::getOrderDishes($id);
            
            foreach($dishes as $key => $value) {
                OrderQueries::addUserDish(
                    $GLOBALS["USER_ID"], 
                    $value["dishId"]
                );
            }

            return (new BasicResponse("Order's delivery confirmed"))->getData();
        }
    }
?>