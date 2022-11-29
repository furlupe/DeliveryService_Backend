<?php
    include_once dirname(__DIR__, 1)."/helpers/Token.php";
    include_once dirname(__DIR__, 1)."/models/DishBasketDTO.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    class BasketService {
        public static function getBasket() {
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);
            if(is_null($userId)) {
                throw new AuthException();
            }

            $basket = $GLOBALS["LINK"]->query(
                "SELECT dishId, amount
                FROM BASKET
                WHERE userId = ?",
                array($userId)
            )->fetch();
            
            $response = array();
            foreach($basket as $key => $value) {
                array_push(
                    $response, 
                    (new DishBasketDTO(
                        $value["dishId"], 
                        $value["amount"]))->getData()
                );
            }
            return $response;
        }

        public static function addDish($id) {
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);
            if(is_null($userId)) {
                throw new AuthException();
            }

            $exists = $GLOBALS["LINK"]->query(
                "SELECT 1
                FROM BASKET
                WHERE userId=? AND dishId=?
                LIMIT 1",
                array($userId, $id)
            )->fetch();

            if($exists) {
                $GLOBALS["LINK"]->query(
                    "UPDATE BASKET
                    SET amount=amount+1
                    WHERE userId=? AND dishId=?",
                    array($userId, $id)
                );
            } else {
                $GLOBALS["LINK"]->query(
                    "INSERT INTO BASKET(userId, dishId, amount)
                    VALUES (?, ?, ?)",
                    array($userId, $id, 1)
                );
            }

            return array(
                "status" => "HTTP/1.0 200 OK",
                "message" => "Dish added");
        }

        public static function removeDish($id, $decrease) {
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);
            if(is_null($userId)) {
                throw new AuthException();
            }
            echo $decrease;
            if ($decrease) {
                $GLOBALS["LINK"]->query(
                    "UPDATE BASKET
                    SET amount=amount-1
                    WHERE userId=? AND dishId=?",
                    array($userId, $id)
                );
            } else {
                $GLOBALS["LINK"]->query(
                    "DELETE FROM BASKET
                    WHERE userId=? AND dishId=?",
                    array($userId, $id)
                );
            }

            return array(
                "status" => "HTTP/1.0 200 OK",
                "message" => "Dish removed"
            );
        }
    }
?>