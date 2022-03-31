<?php
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb";
    $tbl_name="login";
    
    mysql_connect("$host", "$user", "$pass")or die("cannot connect");
    mysql_select_db("$db_name")or die("cannot select DB");
    
    $sql="SELECT * FROM $tbl_name";
    $result=mysql_query($sql);
    $count=mysql_num_rows($result);
    $resultGetClaim=mysql_query($sql) or die("Error select claimants: ".mysql_error());
    echo "id, user, pass, ip, time, level <br/>";
    while($rowGetClaim = mysql_fetch_array($resultGetClaim)) {
        echo $rowGetClaim['id']. ", ";
        echo $rowGetClaim['user']. ", ";
        echo $rowGetClaim['pass']. ", ";
        echo $rowGetClaim['ip']. ", ";
        echo $rowGetClaim['time']. ", ";
        echo $rowGetClaim['level']. "<br/>";
    }
?>