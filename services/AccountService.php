<?php
    include_once dirname(__DIR__, 1)."/helpers/Token.php";
    include_once dirname(__DIR__, 1)."/helpers/LoginCredentials.php";
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/models/UserRegisterModel.php";
    include_once dirname(__DIR__, 1)."/models/UserDTO.php";
    class AccountService {
        public static function register($data) : array {
            $user = new UserRegisterModel($data);

            $user->store();
            
            $logdata = array(
                "email" => $data->email,
                "password" => $data->password
            );

            return self::login($logdata);
        }
        
        public static function login($data) : array {
            if (!isset($data->email)) {
                throw new InvalidDataException("Email field is empty");
            }

            if (!isset($data->password)) {
                throw new InvalidDataException("Password field is empty");
            }

            LoginCredentials::checkExistance($data->email, $data->password);
            
            $token = Token::generateJWT(
                array(
                    "email" => $data->email,
                    "exp" => ((new DateTime())->modify("+20 minutes")->format('Y-m-d H:i:s'))
                ));

            return array("token" => $token);
        }

        public static function logout($token) {
            $GLOBALS["LINK"]->query(
                "INSERT INTO BLACKLIST(value) VALUES ('$token')"
            );
            
            return array(
                "status" => "HTTP/1.0 200 OK",
                "message" => "logout successful"
            );
        }

        public static function getProfile($token) {
            $email = Token::getEmailFromToken($token);
            if(!$email) {
                throw new AuthException();
            }

            $profile = new UserDTO($email);
            return $profile->getData();
        }
    }
?>
