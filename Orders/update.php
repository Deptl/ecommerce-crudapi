<?php

error_reporting(0);

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];


if ($requestMethod == "PUT") {

    $updateOrder = json_decode(file_get_contents("php://input"), true);
    if(empty($updateOrder)){
        $updateOrderData = updateOrder($_POST, $_GET);
    } else {
        $updateOrderData = updateOrder($updateOrder, $_GET);
    }

    echo $updateOrderData;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}


function updateOrder($orderInput, $updatedParams){

    global $connection;

    if(!isset($updatedParams['orderid'])){

        $data = [
            'status' => 422,
            'message' => "Order Id not Found"
        ];
    
        echo json_encode($data);
    }
    elseif($updatedParams['orderid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Order Id"
        ];
    
        echo json_encode($data);
    }

    $id = mysqli_real_escape_string($connection, $updatedParams["orderid"]);
    $recordingofsale = mysqli_real_escape_string($connection, $orderInput[ 'recordingofsale'] );

    if(empty(trim($recordingofsale))){
        return error422("Enter Recording of sale");
    } else{
        $query = "UPDATE orders SET recordingofsale = '$recordingofsale' WHERE orderid = '$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
        
        if($result){

            $data = [
                'status' => '201',
                'message' => 'Order Updated Successfully',
            ];

            return json_encode($data);

        } else {
            $data = [
                'status' => '500',
                'message' => 'Internal Server Error',
            ];
        
            return json_encode( $data );
        }
    }
}

function error422($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>