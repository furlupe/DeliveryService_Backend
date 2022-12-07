<?php
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/queries/BasketQueries.php";
    include_once dirname(__DIR__, 1)."/queries/OrderQueries.php";
    include_once dirname(__DIR__, 1)."/utils/UUID.php";
    include_once dirname(__DIR__, 1)."/utils/dateFormatting.php";
    class OrderModel {
        protected $deliveryTime;
        protected $address;
        protected $userId;
        protected $orderTime;
        protected $price;
        protected $id;
        const DELTA_TIME = 30;

        public function __construct($data, $userId) {
            $this->userId = $userId;
            $this->orderTime = date('Y-m-d H:i');
            $this->setDeliveryTime($data->deliveryTime);
            $this->setAddress($data->address);
            $this->id = UUID::v4();
            $this->price = 0;

        }

        private function setDeliveryTime($date) {
            $date = dateFormatting($date);
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
            if(strlen($date) < 1 || !$d) {
                throw new InvalidDataException(extras: 
                    array("errors" => array(
                            "deliveryTime" => "Wrong timedate format"
                        )
                    ));
            }
            $diff = $d->diff(DateTime::createFromFormat('Y-m-d H:i', $this->orderTime));
            $mins = $diff->days * 24 * 60;
            $mins += $diff->h * 60;
            $mins += $diff->i;

            if ($mins < self::DELTA_TIME) {
                throw new InvalidDataException(extras: 
                    array("errors" => array(
                            "deliveryTime" => "Delivery time is to small! (at least ".self::DELTA_TIME." mins)"
                        )
                    ));
            }
            $this->deliveryTime = $d->format('Y-m-d H:i');
        }

        private function setAddress($address) {
            if (is_null($address) || empty($address)) {
                throw new InvalidDataException("No address provided. Where should we deliver?");
            }

            $this->address = $address;
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