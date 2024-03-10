<?php 

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {

    $comments = getComments();
    echo $comments;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

function getComments() {

    global $connection;

    $query = "SELECT * FROM comments";
    $query_run = mysqli_query($connection, $query);

    if($query_run){
        if(mysqli_num_rows($query_run) > 0 ){

            $response = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => '200',
                'message' => 'Products Fetched Successfully',
                'data' => $response
            ];
        
            return json_encode( $data );

        } else {
            $data = [
                'status' => '404',
                'message' => 'No Product Found',
            ];
        
            return json_encode( $data );
        }
    } else {
        $data = [
            'status' => '500',
            'message' => 'Internal Server Error',
        ];
    
        return json_encode( $data );
    }
}

?>
