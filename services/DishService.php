<?php
    include_once dirname(__DIR__, 1)."/models/DishPagedListDTO.php";
    include_once dirname(__DIR__, 1)."/exceptions/URLParametersException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/utils/Token.php";
    include_once dirname(__DIR__, 1)."/utils/BasicResponse.php";
    class DishService {
        public static function getDishList($filters) : array {
            if (!isset($filters["page"]) || empty($filters["page"])) {
                throw new URLParametersException(
                    extras: array("errors" => array(
                        "page" => "Page must be specified"
                    ))
                );
            }

            $list = new DishPagedListDTO($filters);
            return $list->getData();
        }

        public static function getDish($id) : array {
            $dish = new DishDTO($id);
            return $dish->getData();
        }

        public static function setRating($id, $rating) : array {
            if($rating < 1 || $rating > 10) {
                throw new URLParametersException(
                    extras: array("errors" => array(
                        "rating" => "rating is not in range [1, 10]"
                    ))
                );
            }

            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);
            if (is_null($userId)) {
                throw new AuthException();
            }

            $exists = $GLOBALS["LINK"]->query(
                "SELECT 1
                FROM RATING
                WHERE userId=? AND dishId=?
                LIMIT 1",
                $userId, $id
            );

            if($exists->num_rows()) {
                $GLOBALS["LINK"]->query(
                    "UPDATE RATING
                    SET value='$rating'
                    WHERE userId=? AND dishId=?",
                    $userId, $id
                );

            } else {
                $GLOBALS["LINK"]->query(
                    "INSERT INTO RATING(userId, dishId, value)
                    VALUES (?, ?, ?)",
                    $userId, $id, $rating
                );
            }

            return (new BasicResponse("Rating set: $rating"))->getData();
        }

        public static function checkIfCanSetRating($dishId) {
            $userId = Token::getIdFromToken($GLOBALS["USER_TOKEN"]);
            if (is_null($userId)) {
                throw new AuthException();
            }

            return $GLOBALS["LINK"]->query(
                "SELECT dishId
                FROM USER_DISH_ORDERED
                WHERE userId=? AND dishId=?",
                $userId, $dishId
            )->num_rows() > 0;
        }
    }
?>