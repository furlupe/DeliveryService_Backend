<?php
    include_once "BasicDTO.php";
    class UserDTO extends BasicDTO{
        protected $id;
        protected $fullName;
        protected $birthDate;
        protected $gender;
        protected $address;
        protected $email;
        protected $phoneNumber;

        public function __construct($email) {
            $user = $GLOBALS["LINK"]->query(
                "SELECT id, name, birthdate, gender, phone, email, adress 
                FROM USERS 
                WHERE email=?",
                $email
            )->fetch_assoc();

            $this->id = $user['id'];
            $this->fullName = $user['name'];
            $this->gender = $user['gender'];
            $this->phoneNumber = $user['phone'];
            $this->email = $user['email'];
            $this->address = $user['adress'];
            $this->birthDate = $user['birthdate'];
        }
    }
?>