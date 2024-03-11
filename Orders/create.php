<?php 

//Setting headers for JSON content type 
header('Content-Type: application/json');

//Remove error and Warnings from postman console
error_reporting(0);

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is POST or not else sending status 405
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

//Function for POST method for orders
function postOrder($orderInput){
    
    //Making database connection variable global
    global $connection;

    //Getting reacordig of sale from order input
    $recordingofsale = mysqli_real_escape_string($connection, $orderInput['recordingofsale']);

    //Checking if  all fields are filled otherwise sending error message with status 422
    if(empty(trim($recordingofsale))){
        return errorMessage("Enter Recording");
    } else {

        //SQL query for inserting Data in orders table
        $query = "INSERT INTO orders (recordingofsale) VALUES ('$recordingofsale')";
        $result = mysqli_query($connection, $query);
        
        //If data inserted successfully then sending success message with status 
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

//Custom function for returning error messages with a specific status code and message
function errorMessage($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>