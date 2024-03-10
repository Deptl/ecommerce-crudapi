<?php 

// error_reporting(0);

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if($requestMethod == "POST"){

    $createOrder = json_decode(file_get_contents("php://input"), true);
    if(empty($createOrder)){
        $storeOrderData = postOrder($_POST);
    } else {
        $storeOrderData = postOrder($createOrder);
    }

    echo $storeOrderData;
} else {

    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

function postOrder($orderInput){
    
    global $connection;

    $recordingofsale = mysqli_real_escape_string($connection, $orderInput['recordingofsale']);

    if(empty(trim($recordingofsale))){
        return error422("Enter Recording");
    } else {
        $query = "INSERT INTO orders (recordingofsale) VALUES ('$recordingofsale')";
        $result = mysqli_query($connection, $query);
        
        if($result){

            $data = [
                'status' => '201',
                'message' => 'Customer Created Successfully',
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