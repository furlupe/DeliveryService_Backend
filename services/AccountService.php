<?php
    include_once dirname(__DIR__, 1)."/helpers/Token.php";
    include_once dirname(__DIR__, 1)."/helpers/LoginCredentials.php";
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/models/UserRegisterModel.php";
    class AccountService {
        public static function register($data) : array {
            $user = new UserRegisterModel($data);

            $user->store();
            
            /* move code on lines 12-25 to the login() func when it's implemented */
            $token = Token::generateJWT(
                array(
                    "email" => $data->email,
                    "exp" => ((new DateTime())->modify("+20 minutes")->format('Y-m-d H:i:s'))
                ));

            return array("token" => $token);
        }
        
        public static function login($data) : array {
            if (!isset($data->email)) {
                throw new InvalidDataException("Email field is empty", "400");
            }

            if (!isset($data->password)) {
                throw new InvalidDataException("Password field is empty", "400");
            }

            LoginCredentials::checkExistance($data->email, $data->password);
            
            $token = Token::generateJWT(
                array(
                    "email" => $data->email,
                    "exp" => ((new DateTime())->modify("+20 minutes")->format('Y-m-d H:i:s'))
                ));

            return array("token" => $token);
        }
    }
?>
