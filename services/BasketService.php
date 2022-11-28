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
                WHERE userId = '$userId'"
            )->fetch_all(MYSQLI_ASSOC);
            
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
    }
?>