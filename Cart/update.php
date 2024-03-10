<?php

error_reporting(0);

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];


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


function updateCart($cartInput, $updatedParams){

    global $connection;

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

    $id = mysqli_real_escape_string($connection, $updatedParams["cartitemid"]);
    $product = mysqli_real_escape_string($connection, $cartInput[ 'product'] );
    $quantities = mysqli_real_escape_string( $connection, $cartInput['quantities']);
    $user = mysqli_real_escape_string( $connection, $cartInput['user'] );

    if(empty(trim($product))){
        return error422("Enter Product");
    } elseif (empty(trim($quantities))){
        return error422("Enter quantities");
    } elseif (empty(trim($user))){
        return error422("User is required");
    } else {
        $query = "UPDATE cart SET product = '$product', quantities = '$quantities', user = '$user' WHERE cartitemid = '$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
        
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

function error422($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>