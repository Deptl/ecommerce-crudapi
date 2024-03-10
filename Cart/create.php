<?php 

error_reporting(0);

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

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

function postCart($cartInput){
    
    global $connection;

    $product = mysqli_real_escape_string($connection, $cartInput[ 'product'] );
    $quantities = mysqli_real_escape_string( $connection, $cartInput['quantities']);
    $user = mysqli_real_escape_string( $connection, $cartInput['user'] );

    if(empty(trim($product))){
        return error422("Enter Product");
    } elseif (empty(trim($quantities))){
        return error422("Enter Quantity");
    } elseif (empty(trim($user))){
        return error422("Enter user");
    } else{
        $query = "INSERT INTO cart (product, quantities, user) VALUES ('$product', '$quantities', '$user')";
        $result = mysqli_query($connection, $query);
        
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

function error422($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>