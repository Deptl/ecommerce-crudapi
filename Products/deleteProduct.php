<?php 

//Setting headers for JSON content type 
header('Content-Type: application/json');

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is DELETE or not else sending status 405
if ($requestMethod == "DELETE") {
    $deleteproducts = deleteProducts($_GET);
    echo $deleteproducts;
} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];
    echo json_encode( $data );
}

//Function for DELETE method for products
function deleteProducts($params){

    //Making database connection variable global
    global $connection;

    //Checking if the value is not null
    if(!isset($params['productid'])){
        $data = [
            'status' => 422,
            'message' => "Product Id not Found"
        ];
        return json_encode($data);
    }
    elseif($params['productid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Product Id"
        ];
        return json_encode($data);
    }

    //Getting productid which we want to delete
    $id = mysqli_real_escape_string($connection, $params["productid"]);

    //SQL query for deleting Data in product table
    $query = "DELETE FROM product WHERE productid='$id' LIMIT 1";
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