<?php
    class UserRegisterModel {
        private $fullName;
        private $password;
        private $email;
        private $address;
        private $birthDate;
        private $gender;
        private $phoneNumber;

        public function __construct(
            $fullName,
            $password,
            $email, 
            $address,
            $birthDate,
            $gender,
            $phoneNumber) {
                $this->fullName = $fullName;
                $this->password = $password;
                $this->birthDate = $birthDate;
                $this->gender = $gender;
                $this->address = $address;
                $this->email = $email;
                $this->phoneNumber = $phoneNumber;
        }

        public function getJSON() {
            $json = [];
            foreach(get_object_vars($this) as $key => $value) {
                $json[$key] = $value;
            }

            return $json;
        }

    }
?>