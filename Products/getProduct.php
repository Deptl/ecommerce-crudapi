<?php 

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is GET or not else sending status 405
if ($requestMethod == "GET") {

    if(isset($_GET['productid'])){

        $product = getOneProduct($_GET);
        echo $product;
    } else {
        $productList = getSpecificProduct();
        echo $productList;
    }

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

//Function for GET method for specific products
function  getOneProduct($params){

    //Making database connection variable global
    global $connection;

    //Getting id from user input
    $productid = mysqli_real_escape_string($connection, $params['productid']);

    //If data displayed successfully then sending success message with status
    if(empty($productid)) {
        return errorMessage("Enter Product id");
    } else {

        //SQL query for getting Data from id in product table
        $query = "SELECT * FROM product WHERE productid = '$productid' LIMIT 1";
        $result = mysqli_query($connection, $query);

        //If data updated successfully then sending success message with status
        if($result){

            //Checking if the table is empty or not
            if(mysqli_num_rows($result) == 1 ){

                $response = mysqli_fetch_all($result);

                $data = [
                    'status' => '200',
                    'message' => 'Product Fetched Successfully',
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
}

//Custom function for returning error messages with a specific status code and message
function errorMessage($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>