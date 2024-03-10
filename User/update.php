<?php

// error_reporting(0);

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];


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


function updateProduct($userInput, $updatedParams){

    global $connection;

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

    $id = mysqli_real_escape_string($connection, $updatedParams["userid"]);
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
        $query = "UPDATE user SET email = '$email', password = '$password', username = '$username', purchasehistory = '$purchasehistory', shippingaddress = '$shippingaddress' WHERE userid = '$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
        
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

function error422($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>