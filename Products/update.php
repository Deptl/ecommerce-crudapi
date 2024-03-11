<?php

//Remove error and Warnings from postman console
error_reporting(0);

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is PUT or not else sending status 405
if ($requestMethod == "PUT") {

    $updateProduct = json_decode(file_get_contents("php://input"), true);
    if(empty($updateProduct)){
        $updateProductData = updateProduct($_POST, $_GET);
    } else {
        $updateProductData = updateProduct($updateProduct, $_GET);
    }

    echo $updateProductData;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

//Function for PUT method for product
function updateProduct($productInput, $updatedParams){

    //Making database connection variable global
    global $connection;

    //Checking if the value of productid is not null
    if(!isset($updatedParams['productid'])){

        $data = [
            'status' => 422,
            'message' => "Customer Id not Found"
        ];
    
        echo json_encode($data);
    }
    elseif($updatedParams['productid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Customer Id"
        ];
    
        echo json_encode($data);
    }

    //Getting id, description, image, pricing, shippingcost from product input
    $id = mysqli_real_escape_string($connection, $updatedParams["productid"]);
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

        //SQL query for updating Data in product table
        $query = "UPDATE product SET description = '$description', image = '$image', pricing = '$pricing', shippingcost = '$shippingcost' WHERE productid = '$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
        
        //If data updated successfully then sending success message with status
        if($result){

            $data = [
                'status' => '200',
                'message' => 'Product Updated Successfully',
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