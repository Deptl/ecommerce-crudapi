<?php 

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "DELETE") {

    $deleteOrders = deleteOrders($_GET);
    echo $deleteOrders;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

function deleteOrders($params){

    global $connection;

    if(!isset($params['orderid'])){

        $data = [
            'status' => 422,
            'message' => "Customer Id not Found"
        ];
    
        return json_encode($data);
    }
    elseif($params['orderid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Customer Id"
        ];
    
        return json_encode($data);
    }

    $id = mysqli_real_escape_string($connection, $params["orderid"]);

    $query = "DELETE FROM orders WHERE orderid='$id' LIMIT 1";
    $result = mysqli_query($connection, $query);

    if($result){

        $data = [
            'status' => '200',
            'message' => 'Product Deleted Successfully',
        ];

        return json_encode($data);

    } else {
        $data = [
            'status' => '404',
            'message' => 'Product Not Found',
        ];
    
        return json_encode( $data );
    }
}

?>