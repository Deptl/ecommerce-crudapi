<?php 

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
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

function deleteUser($params){

    global $connection;

    if(!isset($params['userid'])){

        $data = [
            'status' => 422,
            'message' => "Customer Id not Found"
        ];
    
        return json_encode($data);
    }
    elseif($params['userid'] == null){
        $data = [
            'status' => 422,
            'message' => "Enter Customer Id"
        ];
    
        return json_encode($data);
    }

    $id = mysqli_real_escape_string($connection, $params["userid"]);

    $query = "DELETE FROM user WHERE userid='$id' LIMIT 1";
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