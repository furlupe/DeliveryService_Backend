<?php
    include_once dirname(__DIR__, 1)."/enums/Gender.php";
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/utils/dbStringFormat.php";
    include_once dirname(__DIR__, 1)."/queries/AccountQueries.php";
    include_once dirname(__DIR__, 1)."/utils/dateFormatting.php";

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

            $e = array();
            foreach($this->errors as $key => $value) {
                if ($value) {
                    $e[$key] = $value;
                }
            }

            if($e) {
                throw new InvalidDataException(
                    "One or more registration errors occured",
                    array("errors" => $e)
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
            $this->errors["BirthDate"] = array();
            $date = dateFormatting($date);
            $d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
            if(strlen($date) < 1) {
                $this->birthDate = null;
                return;
            }
            if (!$d) {
                array_push(
                    $this->errors["BirthDate"], 
                    "Wrong date format"
                );
                return;
            }

            $this->birthDate = $d->format('Y-m-d');
        }
        private function setName($name) {
            $this->errors["FullName"] = array();
            if(strlen($name) < 1) {
                array_push(
                    $this->errors["FullName"], 
                    "Name too short"
                );
                return;
            }

            $this->fullName = $name;
        }

        private function setPassword($password) {
            $this->errors["Password"] = array();
            if(strlen($password) < 6) {
                array_push(
                    $this->errors["Password"], 
                    "Password too short, at least 6 characters required"
                );
            }

            if(!preg_match('/[A-Za-z0-9_]/', $password)) { 
                array_push(
                    $this->errors["Password"], 
                    "Password must have English letters, digits and underscore only"
                );
            }

            if (!preg_match('~[0-9]+~', $password)) {
                array_push(
                    $this->errors["Password"], 
                    "Password must have at least one digit"
                );
            }

            if (count($this->errors["Password"]) > 0)
                return;

            $this->password = hash("sha1", $password);
        }

        private function setEmail($email) {
            $this->errors["Email"] = array();
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push(
                    $this->errors["Email"], 
                    "Wrong E-mail format"
                );
                return;
            }

            if ($this->exists($email)) {
                array_push(
                    $this->errors["Email"], 
                    "Email is already taken"
                );
                return;
            } 

            $this->email = $email;
        }

        private function setGender($gender) {
            $this->errors["Gender"] = array();

            if(!Gender::checkIfExists($gender)) {
                array_push(
                    $this->errors["Gender"], 
                    "No such gender exists"
                );
                return;
            }

            $this->gender = $gender;
        }

        private function setPhone($phone) {
            if(!strlen($phone)) return null;
            $this->errors["PhoneNumber"] = array();

            if(!preg_match('/\+7\(\d{3}\)\d{3}-\d{2}-\d{2}/', $phone)) {
                array_push(
                    $this->errors["PhoneNumber"], 
                    "Wrong phone number format"
                );
                return;
            }

            $this->phoneNumber = $phone;
        }
    }
?>