<?php
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb";
    $tbl_name="room";
    $arrRoom = array();
    mysql_connect("$host", "$user", "$pass")or die("cannot connect");
    mysql_select_db("$db_name")or die("cannot select DB");
    
    $sql="SELECT * FROM $tbl_name";
    $result=mysql_query($sql);
    $count=mysql_num_rows($result);
    $resultGetClaim=mysql_query($sql) or die("Error select claimants: ".mysql_error());
    while($rowGetClaim = mysql_fetch_array($resultGetClaim)) {
        array_push($arrRoom,$rowGetClaim['ROOM_NUMBER']);
        //echo $rowGetClaim['ROOM_ID']. ", ";
        //echo $rowGetClaim['ROOM_NUMBER']. ", ";
    }
    //print_r($arrRoom);
?>