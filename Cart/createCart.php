<?php 

//Setting headers for JSON content type 
header('Content-Type: application/json');

//Remove error and Warnings from postman console
error_reporting(0);

//Remove error and Warnings from postman console
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is POST or not else sending status 405
if($requestMethod == "POST"){

    $createCart = json_decode(file_get_contents("php://input"), true);
    if(empty($createCart)){
        $storeCartData = postCart($_POST);
    } else {
        $storeCartData = postCart($createCart);
    }

    echo $storeCartData;
} else {

    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

//Function for POST method for cart
function postCart($cartInput){
    
    //Making database connection variable global
    global $connection;

    //Getting product, quantities, user from user input
    //mysqli_real_escape_string is used to create proper sql string that is used in sql statement
    $product = mysqli_real_escape_string($connection, $cartInput[ 'product'] );
    $quantities = mysqli_real_escape_string( $connection, $cartInput['quantities']);
    $user = mysqli_real_escape_string( $connection, $cartInput['user'] );

    //Checking if  all fields are filled otherwise sending error message with status 422
    if(empty(trim($product))){
        return errorMessage("Enter Product");
    } elseif (empty(trim($quantities))){
        return errorMessage("Enter Quantity");
    } elseif (empty(trim($user))){
        return errorMessage("Enter user");
    } else{

        //SQL query for inserting Data in cart table
        $query = "INSERT INTO cart (product, quantities, user) VALUES ('$product', '$quantities', '$user')";
        $result = mysqli_query($connection, $query);
        
        //If data inserted successfully then sending success message with status
        if($result){

            $data = [
                'status' => '201',
                'message' => 'Cart Created Successfully',
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

function errorMessage($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>