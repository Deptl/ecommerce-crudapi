<?php 

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "DELETE") {

    $deletecomments = deleteComment($_GET);
    echo $deletecomments;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

function deleteComment($params){

    global $connection;

    if(!isset($params['commentid'])){

        $data = [
            'status' => 422,
            'message' => "Comment Id not Found"
        ];
    
        return json_encode($data);
    }
    elseif($params['commentid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Comment Id"
        ];
    
        return json_encode($data);
    }

    $id = mysqli_real_escape_string($connection, $params["commentid"]);

    $query = "DELETE FROM comments WHERE commentid='$id' LIMIT 1";
    $result = mysqli_query($connection, $query);

    if($result){

        $data = [
            'status' => '200',
            'message' => 'Product Deleted Successfully',
        ];

        return json_encode($data);

    } else {
        $data = [
            'status' => '404',
            'message' => 'Product Not Found',
        ];
    
        return json_encode( $data );
    }
}

?>