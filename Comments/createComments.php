<?php 

//Setting headers for JSON content type 
header('Content-Type: application/json');

//Remove error and Warnings from postman console
error_reporting(0);

//Remove error and Warnings from postman console
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is POST or not else sending status 405
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

//Function for POST method for comments
function postComment($commentInput){
    
    //Making database connection variable global
    global $connection;

    //Getting product, user, rating, images, and text from user input
    //mysqli_real_escape_string is used to create proper sql string that is used in sql statement
    $product = mysqli_real_escape_string($connection, $commentInput[ 'product'] );
    $user = mysqli_real_escape_string( $connection, $commentInput['user']);
    $rating = mysqli_real_escape_string( $connection, $commentInput['rating'] );
    $images =  mysqli_real_escape_string( $connection,$commentInput['images'] );
    $text =  mysqli_real_escape_string( $connection,$commentInput['text'] );

    //Checking if  all fields are filled otherwise sending error message with status 422
    if(empty(trim($product))){
        return errorMessage("Enter Product");
    } elseif (empty(trim($user))){
        return errorMessage("Upload User");
    } elseif (empty(trim($rating))){
        return errorMessage("Price is rating");
    } elseif (empty(trim($images))){
        return errorMessage("Images are Required");
    } elseif(empty(trim($text))) {
        return errorMessage("Please enter Text");
    } else{

        //SQL query for inserting Data in comments table
        $query = "INSERT INTO comments (product, user, rating, images, text) VALUES ('$product', '$user', '$rating', '$images', '$text')";
        $result = mysqli_query($connection, $query);
        
        //If data inserted successfully then sending success message with status
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

function errorMessage($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>