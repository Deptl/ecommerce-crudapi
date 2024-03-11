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

    $updateUser = json_decode(file_get_contents("php://input"), true);
    if(empty($updateUser)){
        $updateUserData = updateProduct($_POST, $_GET);
    } else {
        $updateUserData = updateProduct($updateUser, $_GET);
    }

    echo $updateUserData;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

//Function for PUT method for user
function updateProduct($userInput, $updatedParams){

    //Making database connection variable global
    global $connection;

    //Checking if the value of userid is not null
    if(!isset($updatedParams['userid'])){

        $data = [
            'status' => 422,
            'message' => "User Id not Found"
        ];
    
        echo json_encode($data);
    }
    elseif($updatedParams['userid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter User Id"
        ];
    
        echo json_encode($data);
    }

    //Getting id, email, password, username, shippingaddress and purchasehistory from user input
    $id = mysqli_real_escape_string($connection, $updatedParams["userid"]);
    $email = mysqli_real_escape_string($connection, $userInput[ 'email'] );
    $password = mysqli_real_escape_string( $connection, $userInput['password']);
    $username = mysqli_real_escape_string( $connection, $userInput['username'] );
    $shippingaddress =  mysqli_real_escape_string( $connection,$userInput['shippingaddress'] );
    $purchasehistory = mysqli_real_escape_string($connection, $userInput['purchasehistory']);

    //Checking if  all fields are filled otherwise sending error message with status 422
    if(empty(trim($email))){
        return errorMessage("Enter Email");
    } elseif (empty(trim($password))){
        return errorMessage("Enter Password");
    } elseif (empty(trim($username))){
        return errorMessage("Enter Username");
    } elseif (empty(trim($shippingaddress))){
        return errorMessage("Enter Shipping Address");
    } elseif(empty(trim($purchasehistory))){
        return errorMessage("Purchase History is Required");
    } else{

        //SQL query for updating Data in user table
        $query = "UPDATE user SET email = '$email', password = '$password', username = '$username', purchasehistory = '$purchasehistory', shippingaddress = '$shippingaddress' WHERE userid = '$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
        
        //If data updated successfully then sending success message with status 
        if($result){

            $data = [
                'status' => '201',
                'message' => 'User Updated Successfully',
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