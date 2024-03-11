<?php 

//Setting headers for JSON content type 
header('Content-Type: application/json');

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is GET or not else sending status 405
if ($requestMethod == "GET") {
    $products = getProducts();
    echo $products;
} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];
    echo json_encode( $data );
}

//Function for GET method for products
function getProducts() {

    //Making database connection variable global
    global $connection;

    //SQL query for getting Data in product table
    $query = "SELECT * FROM product";
    $query_run = mysqli_query($connection, $query);

    //If data displayed successfully then sending success message with status
    if($query_run){

        //Checking if the table is empty or not
        if(mysqli_num_rows($query_run) > 0 ){
            $response = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            $data = [
                'status' => '200',
                'message' => 'Products Fetched Successfully',
                'data' => $response
            ]; 
            return json_encode( $data );
        } else {
            $data = [
                'status' => '404',
                'message' => 'No Product Found',
            ];  
            return json_encode( $data );
        }
    } else {
        $data = [
            'status' => '500',
            'message' => 'Internal Server Error',
        ];
        return json_encode( $data );
    }
}

?>
