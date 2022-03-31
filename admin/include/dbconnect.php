<?php
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb"; 
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
?>