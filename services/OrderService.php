<?php
    include_once dirname(__DIR__, 1)."/utils/BasicResponse.php";
    include_once dirname(__DIR__, 1)."/utils/Token.php";
    include_once dirname(__DIR__, 1)."/models/OrderInfoDTO.php";

    class OrderService {
        public static function getOrders() {
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);
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
            return array();
        }

        public static function createOrder() {
            return (new BasicResponse("Order created"))->getData();
        }

        public static function confirmOrder($id) {
            return (new BasicResponse("Order's delivery confirmed"))->getData();
        }
    }
?>