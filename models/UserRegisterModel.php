<?php
    include dirname(__DIR__, 1)."/Query.php";
    class UserRegisterModel {
        private $fullName;
        private $password;
        private $email;
        private $address;
        private $birthDate;
        private $gender;
        private $phoneNumber;

        private $link;

        public function __construct($data) {
                $this->fullName = $data->fullName;
                $this->password = $data->password;
                $this->birthDate = $data->birthDate;
                $this->gender = $data->gender;
                $this->address = $data->address;
                $this->email = $data->email;
                $this->phoneNumber = $data->phoneNumber;

                $this->link = Query::connect();
        }

        public function store() {
            return $this->link->query("INSERT INTO USERS(name, birthdate, gender, phone, email, adress, password) 
                VALUES(
                    '$this->fullName',
                    '$this->birthDate',
                    '$this->gender',
                    '$this->phoneNumber',
                    '$this->email',
                    '$this->address',
                    '$this->password'
            )");
        }
    }
?>