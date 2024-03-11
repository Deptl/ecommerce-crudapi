<?php 

//Setting headers for JSON content type 
header('Content-Type: application/json');

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is DELETE or not else sending status 405
if ($requestMethod == "DELETE") {
    $deleteCart = deleteCart($_GET);
    echo $deleteCart;
} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];
    echo json_encode( $data );
}

//Function for DELETE method for cart
function deleteCart($params){

    //Making database connection variable global
    global $connection;

    //Checking if the value is not null
    if(!isset($params['cartitemid'])){
        $data = [
            'status' => 422,
            'message' => "Cart Id not Found"
        ];
        return json_encode($data);
    }
    elseif($params['cartitemid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Cart Id"
        ];
        return json_encode($data);
    }

    //Getting commentid which we want to delete
    $id = mysqli_real_escape_string($connection, $params["cartitemid"]);

    //SQL query for deleting Data in cart table
    $query = "DELETE FROM cart WHERE cartitemid='$id' LIMIT 1";
    $result = mysqli_query($connection, $query);

    //If data deleted successfully then sending success message with status
    if($result){
        $data = [
            'status' => '200',
            'message' => 'Product Deleted Successfully',
        ];
        return json_encode($data);
    } else {
        $data = [
            'status' => '404',
            'message' => 'Product Not Found',
        ];
        return json_encode( $data );
    }
}

?>