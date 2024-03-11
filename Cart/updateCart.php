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

    $updateCart = json_decode(file_get_contents("php://input"), true);
    if(empty($updateCart)){
        $updateCartData = updateCart($_POST, $_GET);
    } else {
        $updateCartData = updateCart($updateCart, $_GET);
    }

    echo $updateCartData;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

//Function for PUT method for cart
function updateCart($cartInput, $updatedParams){

    //Making database connection variable global
    global $connection;

    //Checking if the value of cartid is not null
    if(!isset($updatedParams['cartitemid'])){

        $data = [
            'status' => 422,
            'message' => "Cart Item Id not Found"
        ];
    
        echo json_encode($data);
    }
    elseif($updatedParams['cartitemid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Cart Item Id"
        ];
    
        echo json_encode($data);
    }

    //Getting id, product, quantities, user from user input
    //mysqli_real_escape_string is used to create proper sql string that is used in sql statement
    $id = mysqli_real_escape_string($connection, $updatedParams["cartitemid"]);
    $product = mysqli_real_escape_string($connection, $cartInput[ 'product'] );
    $quantities = mysqli_real_escape_string( $connection, $cartInput['quantities']);
    $user = mysqli_real_escape_string( $connection, $cartInput['user'] );

    //Checking if  all fields are filled otherwise sending error message with status 422
    if(empty(trim($product))){
        return errorMessage("Enter Product");
    } elseif (empty(trim($quantities))){
        return errorMessage("Enter quantities");
    } elseif (empty(trim($user))){
        return errorMessage("User is required");
    } else {

        //SQL query for updating Data in cart table
        $query = "UPDATE cart SET product = '$product', quantities = '$quantities', user = '$user' WHERE cartitemid = '$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
        
        //If data updated successfully then sending success message with status
        if($result){

            $data = [
                'status' => '200',
                'message' => 'Cart Updated Successfully',
            ];

            return json_encode($data);

        } else{
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