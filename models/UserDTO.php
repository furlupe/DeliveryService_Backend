<?php
    class UserDTO {
        private $id;
        private $fullName;
        private $birthDate;
        private $gender;
        private $address;
        private $email;
        private $phoneNumber;

        public function __construct($email) {
            $user = $GLOBALS["LINK"]->query(
                "SELECT id, name, birthdate, gender, phone, email, adress 
                FROM USERS 
                WHERE email=?",
                array($email)
            )->fetch_assoc();

            $this->id = $user['id'];
            $this->fullName = $user['name'];
            $this->gender = $user['gender'];
            $this->phoneNumber = $user['phone'];
            $this->email = $user['email'];
            $this->address = $user['adress'];
            $this->birthDate = $user['birthdate'];
        }

        public function getData() {
            $r = array();
            foreach(get_object_vars($this) as $key => $value) {
                $r[$key] = $value;
            }

            return $r;
        }
    }
?>