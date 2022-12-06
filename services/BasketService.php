<?php
    include_once dirname(__DIR__, 1)."/utils/Token.php";
    include_once dirname(__DIR__, 1)."/models/DishBasketDTO.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/utils/BasicResponse.php";
    include_once dirname(__DIR__, 1)."/queries/BasketQueries.php";

    class BasketService {
        public static function getBasket() {
            if(is_null($GLOBALS["USER_ID"])) {
                throw new AuthException();
            }

            $basket = BasketQueries::getBasket($GLOBALS["USER_ID"]);
            
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
            if(is_null($GLOBALS["USER_ID"])) {
                throw new AuthException();
            }

            BasketQueries::addDish($GLOBALS["USER_ID"], $id);

            return (new BasicResponse("Dish added"))->getData();
        }

        public static function removeDish($id, $decrease) {
            if(is_null($GLOBALS["USER_ID"])) {
                throw new AuthException();
            }
            echo $decrease;
            if ($decrease) {
                BasketQueries::removeOneDish($GLOBALS["USER_ID"], $id);
            } else {
                BasketQueries::removeAllDish($GLOBALS["USER_ID"], $id);
            }

            return (new BasicResponse("Dish removed"))->getData();
        }
    }
?>