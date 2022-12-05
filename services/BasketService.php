<?php
    include_once dirname(__DIR__, 1)."/utils/Token.php";
    include_once dirname(__DIR__, 1)."/models/DishBasketDTO.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/utils/BasicResponse.php";
    include_once dirname(__DIR__, 1)."/queries/BasketQueries.php";

    class BasketService {
        public static function getBasket() {
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);
            if(is_null($userId)) {
                throw new AuthException();
            }

            $basket = BasketQueries::getBasket($userId);
            
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

            BasketQueries::addDish($userId, $id);

            return (new BasicResponse("Dish added"))->getData();
        }

        public static function removeDish($id, $decrease) {
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);
            if(is_null($userId)) {
                throw new AuthException();
            }
            echo $decrease;
            if ($decrease) {
                BasketQueries::removeOneDish($userId, $id);
            } else {
                BasketQueries::removeAllDish($userId, $id);
            }

            return (new BasicResponse("Dish removed"))->getData();
        }
    }
?>