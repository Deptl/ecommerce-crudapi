<?php 

//Setting headers for JSON content type 
header('Content-Type: application/json');

//Remove error and Warnings from postman console
error_reporting(0);

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is POST or not else sending status 405
if($requestMethod == "POST"){

    //Converts json data
    $createUser = json_decode(file_get_contents("php://input"), true);
    if(empty($createUser)){
        $storeUserData = postUser($_POST);
    } else {
        $storeUserData = postUser($createUser);
    }
    echo $storeUserData;
} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];
    echo json_encode( $data );
}

//Function for POST method for user
function postUser($userInput){
    
    //Making database connection variable global
    global $connection;

    //Getting email, password, username, shippingaddress and purchasehistory from user input
    //mysqli_real_escape_string is used to create proper sql string that is used in sql statement
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

        //SQL query for inserting Data in user table
        $query = "INSERT INTO user (email, password, username, purchasehistory, shippingaddress ) VALUES ('$email', '$password', '$username',  '$purchasehistory','$shippingaddress')";
        $result = mysqli_query($connection, $query);
        
        //If data inserted successfully then sending success message with status 
        if($result){
            $data = [
                'status' => '201',
                'message' => 'User Created Successfully',
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
    return json_encode($data);
}

?>