<?php
    $expire = 365 * 24 * 3600; // We choose a one year duration
    ini_set('session.gc_maxlifetime', $expire);
    session_start();
    setcookie(session_name(), session_id(), time() + $expire); 
    
    $checkSecure = 0;
    require_once '../DataProvider.php';
    $mysqli = DataProvider::getConnection();
    if(isset($_POST["email"])){
        $email = mysqli_real_escape_string($mysqli, $_POST["email"]);
        $checkSecure++;
    }  

    if(isset($_POST["password"])){
        $password = md5($_POST["password"]);
        $checkSecure++;
    }

    if($checkSecure >= 2){
        $sql = "select * from account_admin where admin_email = '$email' and admin_password = '$password'";
        $list = DataProvider::execQuery($sql);
        $row = mysqli_fetch_assoc($list);
        $numRow = mysqli_num_rows($list);
        
        if($numRow > 0){
            $_SESSION['adminName'] = $row["admin_name"];
            $_SESSION['adminEmail'] = $row['admin_email'];
            echo true;
        }
    }
    else{
        echo false;
    }

?>