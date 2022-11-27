<?php
    include_once dirname(__DIR__, 1)."/enums/Gender.php";
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/exceptions/DBException.php";
    include_once dirname(__DIR__, 1)."/helpers/dbStringFormat.php";
    include_once "ModelInterface.php";

    class UserRegisterModel {
        private $fullName;
        private $password;
        private $email;
        private $address;
        private $birthDate;
        private $gender;
        private $phoneNumber;
        private static $link;
        private $errors = array();

        public function __construct($data) {
            $this->link = $GLOBALS["LINK"];
            
            $this->setName($data->fullName);
            $this->setPassword($data->password);
            $this->setGender($data->gender);
            $this->setEmail($data->email);
            $this->setPhone($data->phoneNumber);

            $this->birthDate = (strlen($data->birthDate)) ? date('y-m-d',strtotime($data->birthDate)) : null;
            $this->address = (strlen($data->address)) ? $data->address : null;

            if ($this->errors) {
                throw new InvalidDataException(
                    "One or more registration errors occured",
                    "400",
                    array("errors" => $this->errors)
                );
            }
        }

        public function exists($email) {
            return $this->link->query("SELECT id FROM USERS WHERE email='$email'")->fetch_assoc();
        }

        public function store() {
            $this->link->query("INSERT INTO USERS(id, name, birthdate, gender, phone, email, adress, password) 
                VALUES(
                    UUID(),
                    '$this->fullName',"
                    .formatDbNullableString($this->birthDate).",
                    '$this->gender',"
                    .formatDbNullableString($this->phoneNumber).",
                    '$this->email',"
                    .formatDbNullableString($this->address).",
                    '$this->password'
            )");
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

            if ($this->exists($email)) {
                $this->errors["email"] = 
                    (object) [
                        "message" => "$email is already taken"
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
            if(!strlen($phone)) return null;

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