<?php
    include dirname(__DIR__, 1)."/enums/Gender.php";
    include dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
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
        private $errors = array();

        public function __construct($data) {
            $this->setName($data->fullName);
            $this->setPassword($data->password);
            $this->birthDate = date('y-m-d',strtotime($data->birthDate));
            $this->setGender($data->gender);
            $this->address = $data->address;
            $this->setEmail($data->email);
            $this->setPhone($data->phoneNumber);

            if ($this->errors) {
                throw new InvalidDataException(
                    json_encode(array("errors" => $this->errors))
                );
            }

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
                $this->errors["name"] = 
                    (object) [
                        "message" => "name too short"
                    ];
                return;
            }

            $this->fullName = $name;
        }

        public function setPassword($password) {
            if(strlen($password) < 6) {
                $this->errors["password"] = 
                    (object) [
                        "message" => "password too short"
                    ];
                return;
            }

            $this->password = hash("sha1", $password);
        }

        public function setEmail($email) {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors["email"] = 
                    (object) [
                        "message" => "invalid email"
                    ];
                return;
            }

            $this->email = $email;
        }

        public function setGender($gender) {
            if(!Gender::checkIfExists($gender)) {
                $this->errors["gender"] = 
                    (object) [
                        "message" => "invalid gender"
                    ];
                return;
            }

            $this->gender = $gender;
        }

        public function setPhone($phone) {
            if(!preg_match('/\+7\(\d{3}\)\d{3}-\d{2}-\d{2}/', $phone)) {
                $this->errors["phone"] = 
                    (object) [
                        "message" => "invalid phone"
                    ];
                return;
            }

            $this->phoneNumber = $phone;
        }
    }
?>