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
            $this->deliveryTime = $data["deliveryTime"];
            $this->address = $data["address"];
            $this->orderTime = date('Y-m-d H:m');
            $this->id = UUID::v4();

            $this->createOrderDish();
            $this->countPrice();

            if ($this->errors) {
                throw new InvalidDataException(
                    extras: array("errors" => $this->errors)
                );
            }
        }

        public function createOrder() {
            $GLOBALS["LINK"]->query(
                "INSERT INTO ORDERS(id, userId, deliveryTime, orderTime, price, address)
                VALUES (?, ?, ?, ?, ?, ?)",
                $this->id,
                $this->userId,
                $this->deliveryTime,
                $this->orderTime,
                $this->price,
                $this->address
            );

           /* $GLOBALS["LINK"]->query(
                "DELETE FROM BASKET
                WHERE userId=?",
                $this->userId
            );*/
        }
        private function createOrderDish() {
            $dishes = $GLOBALS["LINK"]->query(
                "SELECT dishId, amount
                FROM BASKET
                WHERE userId=?",
                $this->userId
            )->fetch_all();

            if(!$dishes) {
                $this->errors["basket"] = 
                    (object) [
                        "message" => "empty basket"
                    ];
                return;
            }

            foreach($dishes as $key => $value) {
                $GLOBALS["LINK"]->query(
                    "INSERT INTO ORDER_DISHES(orderId, dishId, amount)
                    VALUES (?, ?, ?)",
                    $this->id, $value["dishID"], $value["amount"]
                );
            }
        }

        private function countPrice() {
            $prices = $GLOBALS["LINK"]->query(
                "SELECT price, amount
                FROM DISHES INNER JOIN ORDER_DISHES
                WHERE orderId=?",
                $this->id
            )->fetch_all();

            foreach($prices as $key => $value) {
                $this->price += intval($value["price"]) * intval($value["amount"]);
            }
        }
    }
?>