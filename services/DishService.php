<?php
    include_once dirname(__DIR__, 1)."/models/DishPagedListDTO.php";
    include_once dirname(__DIR__, 1)."/exceptions/URLParametersException.php";
    include_once dirname(__DIR__, 1)."/exceptions/NotAllowedException.php";
    include_once dirname(__DIR__, 1)."/exceptions/AuthException.php";
    include_once dirname(__DIR__, 1)."/utils/Token.php";
    include_once dirname(__DIR__, 1)."/utils/BasicResponse.php";
    include_once dirname(__DIR__, 1)."/queries/DishQueries.php";
    class DishService {
        public static function getDishList($filters) : array {
            if (!isset($filters["page"]) || empty($filters["page"])) {
                throw new URLParametersException(
                    extras: array(
                        "page" => "Page must be specified"
                    )
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
                    extras: array("rating" => "rating is not in range [1, 10]")
                );
            }

            if (is_null($GLOBALS["USER_ID"])) {
                throw new AuthException();
            }

            if (!self::checkIfCanSetRating($id)) {
                throw new NotAllowedException('User is not allowed to set the rating on that dish');
            }

            DishQueries::setRating($GLOBALS["USER_ID"], $id, $rating);

            return (new BasicResponse("Rating set: $rating"))->getData();
        }

        public static function checkIfCanSetRating($dishId) {
            if (is_null($GLOBALS["USER_ID"])) {
                throw new AuthException();
            }

            return DishQueries::getUserDishOrdered(
                $GLOBALS["USER_ID"], $dishId)->num_rows() > 0;
        }
    }
?>