<?php
  
    $checkSecure = 0;
    require_once '../DataProvider.php';
    $mysqli = DataProvider::getConnection();

    if(isset($_POST["email"])){
        $email = $_POST["email"];
        $checkSecure++;
    }  

    if(isset($_POST["token"])){
        $token = $_POST["token"];
        $checkSecure++;
    }  

    if(isset($_POST["password"])){
        $password = md5($_POST["password"]);
        $checkSecure++;
    }  

    if(isset($_POST["cpassword"])){
        $cpassword = md5($_POST["cpassword"]);
        $checkSecure++;
    } 

    if($checkSecure >= 4 && $password === $cpassword){
        $sql = "update account_admin set admin_password = '$password', token = NULL where admin_email = '$email'";
        DataProvider::execQuery($sql);
        echo true;
    }   
    else{
        echo false;
    }

?>