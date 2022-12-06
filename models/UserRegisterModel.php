<?php
    include_once dirname(__DIR__, 1)."/enums/Gender.php";
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/utils/dbStringFormat.php";
    include_once dirname(__DIR__, 1)."/queries/AccountQueries.php";

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

            $this->setDate($data->birthDate);
            $this->address = (strlen($data->address)) ? $data->address : null;

            if ($this->errors) {
                throw new InvalidDataException(
                    "One or more registration errors occured",
                    array("errors" => $this->errors)
                );
            }
        }

        public function exists($email) {
            return AccountQueries::getUser($email);
        }

        public function store() {
            AccountQueries::addUser(
                $this->fullName,
                formatDbNullableString($this->birthDate),
                $this->gender,
                formatDbNullableString($this->phoneNumber),
                $this->email,
                formatDbNullableString($this->address),
                $this->password
            );
        }

        private function setDate($date) {
            $d = DateTime::createFromFormat('Y-m-d', $date);
            if(strlen($date) < 1) {
                $this->birthDate = null;
                return;
            }
            if (!$d) {
                $this->errors["birthdate"] = 
                    (object) [
                        "message" => "wrong birthdate"
                    ];
                return;
            }

            $this->birthDate = $d->format('Y-m-d');
        }
        private function setName($name) {
            if(strlen($name) < 1) {
                $this->errors["name"] = 
                    (object) [
                        "message" => "name too short"
                    ];
                return;
            }

            $this->fullName = $name;
        }

        private function setPassword($password) {
            if(strlen($password) < 6) {
                $this->errors["password"] = 
                    (object) [
                        "message" => "password too short"
                    ];
                return;
            }

            $this->password = hash("sha1", $password);
        }

        private function setEmail($email) {
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

        private function setGender($gender) {
            if(!Gender::checkIfExists($gender)) {
                $this->errors["gender"] = 
                    (object) [
                        "message" => "invalid gender"
                    ];
                return;
            }

            $this->gender = $gender;
        }

        private function setPhone($phone) {
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