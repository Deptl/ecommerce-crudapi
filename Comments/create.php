<?php 

error_reporting(0);

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

if($requestMethod == "POST"){

    $createComment = json_decode(file_get_contents("php://input"), true);
    if(empty($createComment)){
        $storeCommentData = postComment($_POST);
    } else {
        $storeCommentData = postComment($createComment);
    }

    echo $storeCommentData;
} else {

    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

function postComment($commentInput){
    
    global $connection;

    $product = mysqli_real_escape_string($connection, $commentInput[ 'product'] );
    $user = mysqli_real_escape_string( $connection, $commentInput['user']);
    $rating = mysqli_real_escape_string( $connection, $commentInput['rating'] );
    $images =  mysqli_real_escape_string( $connection,$commentInput['images'] );
    $text =  mysqli_real_escape_string( $connection,$commentInput['text'] );

    if(empty(trim($product))){
        return error422("Enter Product");
    } elseif (empty(trim($user))){
        return error422("Upload User");
    } elseif (empty(trim($rating))){
        return error422("Price is rating");
    } elseif (empty(trim($images))){
        return error422("Images are Required");
    } elseif(empty(trim($text))) {
        return error422("Please enter Text");
    } else{
        $query = "INSERT INTO comments (product, user, rating, images, text) VALUES ('$product', '$user', '$rating', '$images', '$text')";
        $result = mysqli_query($connection, $query);
        
        if($result){

            $data = [
                'status' => '201',
                'message' => 'Comment Created Successfully',
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