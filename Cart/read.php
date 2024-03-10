<?php 

require '../Connection/dbconnection.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == "GET") {

    $cart = getCart();
    echo $cart;

} else {
    $data = [
        'status' => '405',
        'message' => $requestMethod . ' Method Not Allowed'
    ];

    echo json_encode( $data );
}

function getCart() {

    global $connection;

    $query = "SELECT * FROM cart";
    $query_run = mysqli_query($connection, $query);

    if($query_run){
        if(mysqli_num_rows($query_run) > 0 ){

            $response = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => '200',
                'message' => 'Cart Fetched Successfully',
                'data' => $response
            ];
        
            return json_encode( $data );

        } else {
            $data = [
                'status' => '404',
                'message' => 'No Items in Cart Found',
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
