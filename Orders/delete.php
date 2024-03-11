<?php 

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is DELETE or not else sending status 405
if ($requestMethod == "DELETE") {

    $deleteOrders = deleteOrders($_GET);
    echo $deleteOrders;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

//Function for DELETE method for orders
function deleteOrders($params){

    //Making database connection variable global
    global $connection;

    //Checking if the value is not null
    if(!isset($params['orderid'])){

        $data = [
            'status' => 422,
            'message' => "Order Id not Found"
        ];
    
        return json_encode($data);
    }
    elseif($params['orderid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Order Id"
        ];
    
        return json_encode($data);
    }

    //Getting orderid which we want to delete
    $id = mysqli_real_escape_string($connection, $params["orderid"]);

    //SQL query for deleting Data in product table
    $query = "DELETE FROM orders WHERE orderid='$id' LIMIT 1";
    $result = mysqli_query($connection, $query);

    //If data deleted successfully then sending success message with status
    if($result){

        $data = [
            'status' => '200',
            'message' => 'Order Deleted Successfully',
        ];

        return json_encode($data);

    } else {
        $data = [
            'status' => '404',
            'message' => 'Order Not Found',
        ];
    
        return json_encode( $data );
    }
}

?>