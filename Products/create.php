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

    $createProduct = json_decode(file_get_contents("php://input"), true);
    if(empty($createProduct)){
        $storeProductData = postProduct($_POST);
    } else {
        $storeProductData = postProduct($createProduct);
    }

    echo $storeProductData;
} else {

    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

//Function for POST method for product
function postProduct($productInput){
    
    //Making database connection variable global
    global $connection;

    //Getting description, image, pricing, shippingcost from user input
    $description = mysqli_real_escape_string($connection, $productInput[ 'description'] );
    $image = mysqli_real_escape_string( $connection, $productInput['image']);
    $pricing = mysqli_real_escape_string( $connection, $productInput['pricing'] );
    $shippingcost =  mysqli_real_escape_string( $connection,$productInput['shippingcost'] );

    //Checking if  all fields are filled otherwise sending error message with status 422
    if(empty(trim($description))){
        return errorMessage("Enter Description");
    } elseif (empty(trim($image))){
        return errorMessage("Upload Image");
    } elseif (empty(trim($pricing))){
        return errorMessage("Price is required");
    } elseif (empty(trim($shippingcost))){
        return errorMessage("Shipping Cost is Required");
    } else{

        //SQL query for inserting Data in product table
        $query = "INSERT INTO product(description, image, pricing, shippingcost) VALUES ('$description', '$image', '$pricing', '$shippingcost')";
        $result = mysqli_query($connection, $query);
        
        //If data inserted successfully then sending success message with status 
        if($result){

            $data = [
                'status' => '201',
                'message' => 'Product Created Successfully',
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