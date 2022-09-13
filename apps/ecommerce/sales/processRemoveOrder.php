<?php
    $checkSecure = 0;
    if(isset($_POST["id"])){
        $idOrder = $_POST["id"];
        $checkSecure++;
    }

    if($checkSecure > 0){
        require_once '../../../DataProvider.php';
        $sqlOrder = "delete from order_detail where id = '$idOrder'";
		DataProvider::execQuery($sqlOrder);
    }

?>