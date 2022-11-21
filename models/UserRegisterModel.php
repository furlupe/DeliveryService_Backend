<?php
    include dirname(__DIR__, 1)."/Query.php";
    include "ModelInterface.php";
    class UserRegisterModel implements IModel {
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

        public function exists() {
            return $this->link->query("SELECT id FROM USERS WHERE email='$this->email'")->fetch_assoc();
        }

        public function store() {
            return (!$this->exists()) ? 
            $this->link->query("INSERT INTO USERS(name, birthdate, gender, phone, email, adress, password) 
                VALUES(
                    '$this->fullName',
                    '$this->birthDate',
                    '$this->gender',
                    '$this->phoneNumber',
                    '$this->email',
                    '$this->address',
                    '$this->password'
            )") :
            null;
        }
    }
?>