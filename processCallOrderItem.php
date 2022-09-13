<?php 
    $checkSecure = 0;

    if(isset($_GET["id"])){
        $idOrder = $_GET["id"];
        $checkSecure++;
    }

    if($checkSecure > 0){
        require_once 'DataProvider.php';

        $sql = "select * from order_detail od join order_items ot on od.id = ot.order_id, product p, image_product ip where ot.product_id = p.id and p.id = ip.product_id and od.id = '$idOrder'";
		$list = DataProvider::execQuery($sql);
        while ($row = mysqli_fetch_array($list, MYSQLI_ASSOC)) {
            $listItem[] = array('productID' => $row["product_id"], 'name'=>$row["product_name"], 'image' => $row["img_thumb"], 'qty' => $row["quantity"], 'total' => $row["price"]*$row["quantity"], 'stock' => $row["total_store"]);
        }
        // image: '83',
        // name: 'Nike Pumps',
        // description: 'Apple\'s latest headphones.',
        // cost: '200.00',
        // qty: '1',
        // total: '200.00',
        // stock: '8'

    	$listJSItemOrder = json_encode($listItem);
        echo $listJSItemOrder;
    }
?>