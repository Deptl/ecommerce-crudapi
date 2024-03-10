<?php

error_reporting(0);

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];


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


function updateComment($commentInput, $updatedParams){

    global $connection;

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

    $id = mysqli_real_escape_string($connection, $updatedParams["commentid"]);
    $product = mysqli_real_escape_string($connection, $commentInput[ 'product'] );
    $user = mysqli_real_escape_string( $connection, $commentInput['user']);
    $rating = mysqli_real_escape_string( $connection, $commentInput['rating'] );
    $images =  mysqli_real_escape_string( $connection,$commentInput['images'] );
    $text =  mysqli_real_escape_string( $connection,$commentInput['text'] );


    if(empty(trim($product))){
        return error422("Enter Description");
    } elseif (empty(trim($user))){
        return error422("Upload Image");
    } elseif (empty(trim($rating))){
        return error422("Price is required");
    } elseif (empty(trim($images))){
        return error422("Shipping Cost is Required");
    } elseif(empty(trim($text))) {
        return error422("Text is required");
    }
    
    else{
        $query = "UPDATE comments SET product = '$product', user = '$user', rating = '$rating', images = '$images', text = '$text' WHERE commentid = '$id' LIMIT 1";
        $result = mysqli_query($connection, $query);
        
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

function error422($errorMessage){

    $data = [
        'status' => 422,
        'message' => $errorMessage
    ];

    echo json_encode($data);
}

?>