<?php
    if($_POST['selectID'])
    {
        try
        {
            $selectID=$_POST['selectID'];
            $host="localhost";
            $user="root";
            $pass="";
            $db_name="stdcheckdb"; 
            $table = "card_uid_citizenid";
            $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
            $sql = "DELETE FROM $table WHERE ID = :selectID";
            $this_stmt = $this_db->prepare($sql);
            $this_stmt->bindParam(':selectID', $selectID, PDO::PARAM_INT);   
            $this_stmt->execute();
            echo 1;
        }
        catch ( Exception $e )
        {
            echo 0;   //echo $e->getMessage();
        }
    }
?>