<?php
    include_once dirname(__DIR__, 1)."/utils/BasicResponse.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/utils/Token.php";
    include_once dirname(__DIR__, 1)."/models/OrderInfoDTO.php";
    include_once dirname(__DIR__, 1)."/models/OrderDTO.php";
    include_once dirname(__DIR__, 1)."/models/OrderModel.php";

    class OrderService {
        public static function getOrders() {
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);

            if(is_null($userId)) {
                throw new AuthException();
            }

            $orders = $GLOBALS["LINK"]->query(
                "SELECT id
                FROM ORDERS
                where userId=?",
                $userId
            )->fetch_all();
            
            $response = array();
            foreach($orders as $key => $value) {
                array_push(
                    $response, 
                    (new OrderInfoDTO($value['id']))->getData()
                );
            }

            return $response;
        }

        public static function getOrder($id) {
            if(is_null(Token::getIdFromToken($GLOBALS["USER_TOKEN"]))) {
                throw new AuthException();
            }

            return (new OrderDTO($id))->getData();
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
            return (new BasicResponse("Order's delivery confirmed"))->getData();
        }
    }
?>