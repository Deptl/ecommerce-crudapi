<?php

//Setting headers for JSON content type 
header('Content-Type: application/json');

//Remove error and Warnings from postman console
error_reporting(0);

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is PUT or not else sending status 405
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

//Function for UPDATE method for orders
function updateOrder($orderInput, $updatedParams){

    //Making database connection variable global
    global $connection;

    //Checking if the value of orderid is not null
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

    //Getting id, reacordigofsale from order input
    $id = mysqli_real_escape_string($connection, $updatedParams["orderid"]);
    $recordingofsale = mysqli_real_escape_string($connection, $orderInput[ 'recordingofsale'] );

    //Checking if  all fields are filled otherwise sending error message with status 422
    if(empty(trim($recordingofsale))){
        return errorMessage("Enter Recording of sale");
    } else{

        //SQL query for updating Data in order table
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

//Custom function for returning error messages with a specific status code and message
function errorMessage($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>