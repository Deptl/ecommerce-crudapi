<?php 

//Setting database connection variables
$host = "localhost";
$username = "root";
$password = "";
$database = "shoppingsite";

//establishing connection
$connection = mysqli_connect($host, $username, $password, $database);

//checking if connection is successful
if(!$connection){
    die("Connection Failed" . "<br>" . mysqli_connect_error());
}
else{
    
}

?>