<?php
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

            if ($this->errors) {
                throw new InvalidDataException(
                    "One or more fields contain wrong data",
                    array("errors" => $this->errors)
                );
            }
        }
        public function edit() {
            $this->link->query("UPDATE USERS 
                SET name=?, birthdate=?, gender=?, phone=?, adress=?
                WHERE email=?",
                $this->fullName, 
                $this->birthDate,
                $this->gender,
                $this->phoneNumber,
                $this->address,
                $this->email
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