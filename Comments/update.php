<?php

//Remove error and Warnings from postman console
error_reporting(0);

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is PUT or not else sending status 405
if ($requestMethod == "PUT") {

    $updateComment = json_decode(file_get_contents("php://input"), true);
    if(empty($updateComment)){
        $updateCommentData = updateComment($_POST, $_GET);
    } else {
        $updateCommentData = updateComment($updateComment, $_GET);
    }

    echo $updateCommentData;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

//Function for PUT method for comments
function updateComment($commentInput, $updatedParams){

    //Making database connection variable global
    global $connection;

    //Checking if the value of commentid is not null
    if(!isset($updatedParams['commentid'])){

        $data = [
            'status' => 422,
            'message' => "Comment Id not Found"
        ];
    
        echo json_encode($data);
    }
    elseif($updatedParams['commentid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Comment Id"
        ];
    
        echo json_encode($data);
    }

    //Getting id, product, user, rating, images, and text from user input
    $id = mysqli_real_escape_string($connection, $updatedParams["commentid"]);
    $product = mysqli_real_escape_string($connection, $commentInput[ 'product'] );
    $user = mysqli_real_escape_string( $connection, $commentInput['user']);
    $rating = mysqli_real_escape_string( $connection, $commentInput['rating'] );
    $images =  mysqli_real_escape_string( $connection,$commentInput['images'] );
    $text =  mysqli_real_escape_string( $connection,$commentInput['text'] );

    //Checking if  all fields are filled otherwise sending error message with status 422
    if(empty(trim($product))){
        return errorMessage("Enter Description");
    } elseif (empty(trim($user))){
        return errorMessage("Upload Image");
    } elseif (empty(trim($rating))){
        return errorMessage("Price is required");
    } elseif (empty(trim($images))){
        return errorMessage("Shipping Cost is Required");
    } elseif(empty(trim($text))) {
        return errorMessage("Text is required");
    } else{
        
        //SQL query for updating Data in comments table
        $query = "UPDATE comments SET product = '$product', user = '$user', rating = '$rating', images = '$images', text = '$text' WHERE commentid = '$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
        
        //If data updated successfully then sending success message with status
        if($result){

            $data = [
                'status' => '200',
                'message' => 'Comment Updated Successfully',
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