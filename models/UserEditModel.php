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

            $this->birthDate = (strlen($data->birthDate)) ? date('y-m-d',strtotime($data->birthDate)) : null;
            $this->address = (strlen($data->address)) ? $data->address : null;

            $this->email = $data->email;

            if ($this->errors) {
                throw new InvalidDataException(
                    "One or more fields contain wrong data",
                    "400",
                    array("errors" => $this->errors)
                );
            }
        }
        public function edit() {
            $this->link->query("UPDATE USERS 
                SET 
                name='$this->fullName', 
                birthdate='$this->birthDate',
                gender='$this->gender',
                phone='$this->phoneNumber',
                adress='$this->address'
                WHERE email='$this->email'"
            );
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