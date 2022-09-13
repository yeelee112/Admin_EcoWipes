<?php
    $checkSecure = 0;

    if(isset($_POST["id_order"])){
        $idOrder = $_POST["id_order"];
        $checkSecure++;
    }

    if(isset($_POST["status_order"])){
        $statusOrder = $_POST["status_order"];
        $checkSecure++;
    }

    if(isset($_POST["payment_method"])){
        $paymentMethodOrder = $_POST["payment_method"];
        $checkSecure++;
    }

    if(isset($_POST["fullname"])){
        $fullname = $_POST["fullname"];
        $checkSecure++;
    }

    if(isset($_POST["phone"])){
        $phone = $_POST["phone"];
        $checkSecure++;
    }

    if(isset($_POST["email"])){
        $email = $_POST["email"];
        $checkSecure++;
    }

    if(isset($_POST["address_detail"])){
        $addressDetail = $_POST["address_detail"];
        $checkSecure++;
    }

    if(isset($_POST["address"])){
        $address = $_POST["address"];
        $checkSecure++;
    }

    if($checkSecure > 0){
        require_once '../../../DataProvider.php';
        $sqlUpdate = "UPDATE order_detail SET 
        fullname = '$fullname', phone = '$phone', email = '$email', address = '$address', address_detail = '$addressDetail', payment_method = '$paymentMethodOrder', status_order = $statusOrder, updated_at = now()
        WHERE id = '$idOrder';";
		DataProvider::execQuery($sqlUpdate);
    }

?>