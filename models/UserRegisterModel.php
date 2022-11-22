<?php
    include dirname(__DIR__, 1)."/enums/Gender.php";
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
                $this->setName($data->fullName);
                $this->setPassword($data->password);
                $this->birthDate = date('y-m-d',strtotime($data->birthDate));
                $this->setGender($data->gender);
                $this->address = $data->address;
                $this->setEmail($data->email);
                $this->setPhone($data->phoneNumber);
                $this->link = $GLOBALS["LINK"];
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

        public function setName($name) {
            if(strlen($name) < 1) {
                echo "wrong name";
                return;
            }

            $this->fullName = $name;
        }

        public function setPassword($password) {
            if(strlen($password) < 6) {
                echo "wrong password";
                return;
            }

            $this->password = hash("sha1", $password);;
        }

        public function setEmail($email) {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "wrong email";
                return;
            }

            $this->email = $email;
        }

        public function setGender($gender) {
            if(!Gender::checkIfExists($gender)) {
                echo "no such gender exists";
                return;
            }

            $this->gender = $gender;
        }

        public function setPhone($phone) {
            if(!preg_match('/\+7\(\d{3}\)\d{3}-\d{2}-\d{2}/', $phone)) {
                echo "wrong phone";
                return;
            }

            $this->phoneNumber = $phone;
        }
    }
?>