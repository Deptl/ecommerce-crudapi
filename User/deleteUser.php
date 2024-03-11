<?php 

//Setting headers for JSON content type 
header('Content-Type: application/json');

//Importing  the required files for database connection
require '../Connection/dbconnection.php';

//Setting request method as Server request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

//Checking if the requested method is DELETE or not else sending status 405
if ($requestMethod == "DELETE") {
    $deleteuser = deleteUser($_GET);
    echo $deleteuser;
} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];
    echo json_encode( $data );
}

//Function for DELETE method for user
function deleteUser($params){

    //Making database connection variable global
    global $connection;

    //Checking if the value is not null
    if(!isset($params['userid'])){
        $data = [
            'status' => 422,
            'message' => "User Id not Found"
        ];
        return json_encode($data);
    }
    elseif($params['userid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter User Id"
        ];
        return json_encode($data);
    }

    //Getting userid which we want to delete
    $id = mysqli_real_escape_string($connection, $params["userid"]);

    //SQL query for deleting Data in user table
    $query = "DELETE FROM user WHERE userid='$id' LIMIT 1";
    $result = mysqli_query($connection, $query);

    //If data deleted successfully then sending success message with status 
    if($result){
        $data = [
            'status' => '200',
            'message' => 'User Deleted Successfully',
        ];

        return json_encode($data);
    } else {
        $data = [
            'status' => '404',
            'message' => 'User Not Found',
        ];
        return json_encode( $data );
    }
}

?>