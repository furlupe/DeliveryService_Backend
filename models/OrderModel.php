<?php
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/utils/UUID.php";
    class OrderModel {
        protected $deliveryTime;
        protected $address;
        protected $userId;
        protected $orderTime;
        protected $price;
        protected $errors;
        protected $id;

        public function __construct($data, $userId) {
            $this->userId = $userId;
            $this->deliveryTime = date('Y-m-d H:m', strtotime($data->deliveryTime));
            $this->address = $data->address;
            $this->orderTime = date('Y-m-d H:m');
            $this->id = UUID::v4();
            $this->price = 0;
            
        }

        public function createOrder() {

            $basket = $this->getBasket();

            if(!$basket) {
                throw new InvalidDataException(extras: 
                    array("errors" => array(
                        "basket" => "Empty basket"
                    ))
                );
            }

            $GLOBALS["LINK"]->query(
                "INSERT INTO ORDERS(id, userId, deliveryTime, orderTime, address)
                VALUES (?, ?, ?, ?, ?)",
                $this->id,
                $this->userId,
                $this->deliveryTime,
                $this->orderTime,
                $this->address
            );

            $this->createOrderDish($basket);
            $this->countPrice();

            $GLOBALS["LINK"]->query(
                "UPDATE ORDERS
                SET price=?
                WHERE id=?",
                $this->price, $this->id
            );

            $GLOBALS["LINK"]->query(
                "DELETE FROM BASKET
                WHERE userId=?",
                $this->userId
            );
        }

        private function getBasket() {
            return $GLOBALS["LINK"]->query(
                "SELECT dishId, amount
                FROM BASKET
                WHERE userId=?",
                $this->userId
            )->fetch_all();
        }
        private function createOrderDish($basket) {

            foreach($basket as $key => $value) {
                $GLOBALS["LINK"]->query(
                    "INSERT INTO ORDER_DISHES(orderId, dishId, amount)
                    VALUES (?, ?, ?)",
                    $this->id, $value["dishId"], $value["amount"]
                );
            }
        }
        private function countPrice() {
            $prices = $GLOBALS["LINK"]->query(
                "SELECT price, amount
                FROM DISHES INNER JOIN ORDER_DISHES
                ON ORDER_DISHES.dishId = DISHES.id
                WHERE orderId=?",
                $this->id
            )->fetch_all();

            foreach($prices as $key => $value) {
                echo json_encode($value).PHP_EOL;
                $this->price += intval($value["price"]) * intval($value["amount"]);
            }
        }
    }
?>