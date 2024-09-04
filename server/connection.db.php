<?php

    date_default_timezone_set('Africa/Johannesburg');
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    $errors = array(); 
    $success = array();
    $_SESSION['success'] = "";

    class Connection {
        private $server = "localhost";
        private $username = "root";
        private $password = "Str0ngP@ssw0rd!";
        private $database = "sasimfup_helpdesk_cms";

        public function getConn(){
            $conn = new mysqli($this->server,$this->username, $this->password, $this->database); 
            if(!$conn){ 
                array_push($errors,  'Database Connection Error ' . mysqli_connect_error($conn));
                return 'Database Connection Error ' . mysqli_connect_error($conn);  
            }

            return $conn;
        }
    }
    $connection = new Connection();
$conn = $connection->getConn();
?>
