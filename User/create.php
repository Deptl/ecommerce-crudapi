<?php 

// error_reporting(0);

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if($requestMethod == "POST"){

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

function postUser($userInput){
    
    global $connection;

    $email = mysqli_real_escape_string($connection, $userInput[ 'email'] );
    $password = mysqli_real_escape_string( $connection, $userInput['password']);
    $username = mysqli_real_escape_string( $connection, $userInput['username'] );
    $shippingaddress =  mysqli_real_escape_string( $connection,$userInput['shippingaddress'] );
    $purchasehistory = mysqli_real_escape_string($connection, $userInput['purchasehistory']);

    if(empty(trim($email))){
        return error422("Enter Email");
    } elseif (empty(trim($password))){
        return error422("Enter Password");
    } elseif (empty(trim($username))){
        return error422("Enter Username");
    } elseif (empty(trim($shippingaddress))){
        return error422("Enter Shipping Address");
    } elseif(empty(trim($purchasehistory))){
        return error422("Purchase History is Required");
    } else{
        $query = "INSERT INTO user (email, password, username, purchasehistory, shippingaddress ) VALUES ('$email', '$password', '$username',  '$purchasehistory','$shippingaddress')";
        $result = mysqli_query($connection, $query);
        
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

function error422($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>