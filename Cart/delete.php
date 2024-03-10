<?php 

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "DELETE") {

    $deleteCart = deleteCart($_GET);
    echo $deleteCart;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

function deleteCart($params){

    global $connection;

    if(!isset($params['cartitemid'])){

        $data = [
            'status' => 422,
            'message' => "Customer Id not Found"
        ];
    
        return json_encode($data);
    }
    elseif($params['cartitemid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Customer Id"
        ];
    
        return json_encode($data);
    }

    $id = mysqli_real_escape_string($connection, $params["cartitemid"]);

    $query = "DELETE FROM cart WHERE cartitemid='$id' LIMIT 1";
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