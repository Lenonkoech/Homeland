<?php
try{
//host
define("HOSTNAME","localhost");
//database
define("DBNAME","homeland");
//user
define("USER","root");
//password
define("PASS","");


$conn =new PDO("mysql:host=".HOSTNAME.";dbname=".DBNAME.";",USER,PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    //cancel DB connection and display error message
    die("Database connection failed :". $e->getMessage());
}