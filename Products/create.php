<?php 

error_reporting(0);

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

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

function postProduct($productInput){
    
    global $connection;

    $description = mysqli_real_escape_string($connection, $productInput[ 'description'] );
    $image = mysqli_real_escape_string( $connection, $productInput['image']);
    $pricing = mysqli_real_escape_string( $connection, $productInput['pricing'] );
    $shippingcost =  mysqli_real_escape_string( $connection,$productInput['shippingcost'] );

    if(empty(trim($description))){
        return error422("Enter Description");
    } elseif (empty(trim($image))){
        return error422("Upload Image");
    } elseif (empty(trim($pricing))){
        return error422("Price is required");
    } elseif (empty(trim($shippingcost))){
        return error422("Shipping Cost is Required");
    } else{
        $query = "INSERT INTO product(description, image, pricing, shippingcost) VALUES ('$description', '$image', '$pricing', '$shippingcost')";
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