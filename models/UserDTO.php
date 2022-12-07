<?php
    include_once "BasicDTO.php";
    include_once dirname(__DIR__, 1) . "/queries/AccountQueries.php";
    class UserDTO extends BasicDTO{
        protected $id;
        protected $fullName;
        protected $birthDate;
        protected $gender;
        protected $address;
        protected $email;
        protected $phoneNumber;

        public function __construct($email) {
            $user = AccountQueries::getUser($email);

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