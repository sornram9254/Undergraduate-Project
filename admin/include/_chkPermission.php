<?php
    isset($_COOKIE['level'])    ? $level    = $_COOKIE['level']    : $level    = '';
    isset($_COOKIE['username'])    ? $username    = $_COOKIE['username']    : $username    = '';
    if($_COOKIE['level'] == 0){
        //header("location:../");
        //print '<meta http-equiv="refresh" content="0; url=index.php" />';
    }else{
        //
    }
?>