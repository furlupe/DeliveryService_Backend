<?php
    include_once dirname(__DIR__, 1)."/utils/Token.php";
    include_once dirname(__DIR__, 1)."/utils/LoginCredentials.php";
    include_once dirname(__DIR__, 1)."/exceptions/InvalidDataException.php";
    include_once dirname(__DIR__, 1)."/models/UserRegisterModel.php";
    include_once dirname(__DIR__, 1)."/models/UserEditModel.php";
    include_once dirname(__DIR__, 1)."/models/UserDTO.php";
    include_once dirname(__DIR__, 1)."/utils/BasicResponse.php";
    class AccountService {
        public static function register($data) : array {
            $user = new UserRegisterModel($data);

            $user->store();
            
            $logdata = new stdClass();
            $logdata->email = $data->email;
            $logdata->password = $data->password;

            return self::login($logdata);
        }
        
        public static function login($data) : array {
            if (empty($data->email)) {
                throw new InvalidDataException("Email field is empty");
            }

            if (empty($data->password)) {
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
            Token::forbidToken($token);
            
            return (new BasicResponse("Logout successful"))->getData();
        }

        public static function getProfile($token) {
            $email = Token::getEmailFromToken($token);
            if(!$email) {
                throw new AuthException();
            }

            $profile = new UserDTO($email);
            return $profile->getData();
        }

        public static function editProfile($data) {
            $email = Token::getEmailFromToken($data->token);
            if(!$email) {
                throw new AuthException();
            }
            $data->email = $email;
            $profile = new UserEditModel($data);
            $profile->edit();

            return (new BasicResponse("Edit successful"))->getData();
        }
    }
?>
