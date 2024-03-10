<?php

error_reporting(0);

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];


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


function updateProduct($productInput, $updatedParams){

    global $connection;

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

    $id = mysqli_real_escape_string($connection, $updatedParams["productid"]);
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
        $query = "UPDATE product SET description = '$description', image = '$image', pricing = '$pricing', shippingcost = '$shippingcost' WHERE productid = '$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
        
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

?>