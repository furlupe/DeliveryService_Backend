<?php
    include_once dirname(__DIR__, 1) . "/queries/AccountQueries.php";
    class UserEditModel {
        private $fullName;
        private $address;
        private $birthDate;
        private $gender;
        private $phoneNumber;
        private $email;
        private static $link;
        private $errors = array();

        public function __construct($data) {
            $this->link = $GLOBALS["LINK"];
            
            $this->setName($data->fullName);
            $this->setGender($data->gender);
            $this->setPhone($data->phoneNumber);

            $this->setDate($data->birthDate);
            $this->address = (strlen($data->address)) ? $data->address : null;

            $this->email = $data->email;

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
        public function edit() {
            AccountQueries::editUser(
                $this->email,
                $this->fullName, 
                $this->birthDate,
                $this->gender,
                $this->phoneNumber,
                $this->address
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