<?php
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/queries/BasketQueries.php";
    include_once dirname(__DIR__, 1)."/queries/OrderQueries.php";
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

            $basket = BasketQueries::getBasket($this->userId);

            if(!$basket) {
                throw new InvalidDataException(extras: 
                    array("errors" => array(
                        "basket" => "Empty basket"
                    ))
                );
            }

            OrderQueries::createOrder((object) [
                "id" => $this->id,
                "userId" => $this->userId,
                "deliveryTime" => $this->deliveryTime,
                "orderTime" => $this->orderTime,
                "address" => $this->address,
            ]);

            $this->createOrderDish($basket);
            $this->countPrice();

            OrderQueries::updateOrderPrice($this->price, $this->id);

            BasketQueries::clearBasket($this->userId);
        }

        private function createOrderDish($basket) {

            foreach($basket as $key => $value) {
                OrderQueries::addOrderDish(
                    $this->id, 
                    $value["dishId"], 
                    $value["amount"]
                );
            }
        }
        private function countPrice() {
            $prices = OrderQueries::getPriceAndAmount($this->id);

            foreach($prices as $key => $value) {
                $this->price += intval($value["price"]) * intval($value["amount"]);
            }
        }
    }
?>