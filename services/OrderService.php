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
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);

            if(is_null($userId)) {
                throw new AuthException();
            }

            $orders = OrderQueries::getOrders($userId);
            
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
            if(is_null(Token::getIdFromToken($GLOBALS["USER_TOKEN"]))) {
                throw new AuthException();
            }

            return (new OrderDTO(
                OrderQueries::getOrder($id)
            ))->getData();
        }

        public static function createOrder($data) {
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);
            if(is_null($userId)) {
                throw new AuthException();
            }
            $order = new OrderModel($data, $userId);
            $order->createOrder();
            
            return (new BasicResponse("Order created"))->getData();
        }

        public static function confirmOrder($id) {
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);
            if(is_null($userId)) {
                throw new AuthException();
            }

            OrderQueries::confirmOrder($userId, $id);
            $dishes = OrderQueries::getOrderDishes($id);
            
            foreach($dishes as $key => $value) {
                OrderQueries::addUserDish(
                    $userId, 
                    $value["dishId"]
                );
            }

            return (new BasicResponse("Order's delivery confirmed"))->getData();
        }
    }
?>