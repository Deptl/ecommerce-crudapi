<?php 

$host = "localhost";
$username = "root";
$password = "";
$database = "shoppingsite";

$connection = mysqli_connect($host, $username, $password, $database);

if(!$connection){
    die("Connection Failed" . "<br>" . mysqli_connect_error());
}
else{
    
}

?>