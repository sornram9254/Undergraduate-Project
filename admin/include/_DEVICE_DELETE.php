<?php
    if($_POST['selectID'])
    {
        try
        {
            $selectID       =   $_POST['selectID'];
            $opRoom         =   $_POST['opRoom'];
            $opDay          =   $_POST['opDay'];
            $txtTeacher     =   $_POST['txtTeacher'];
            $txtTeachTime   =   $_POST['txtTeachTime'];
            $host="localhost";
            $user="root";
            $pass="";
            $db_name="stdcheckdb"; 
            $table = "device";
            $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
            $sql = "DELETE FROM `stdcheckdb`.`device` WHERE `device`.`DEVICE_ID` = '".$selectID."'";
            $this_stmt = $this_db->prepare($sql);
            //$this_stmt->bindParam(':selectID', $selectID, PDO::PARAM_INT);   
            $this_stmt->execute();
            echo 1;
        }
        catch ( Exception $e )
        {
            echo 0;   //echo $e->getMessage();
        }
    }
?>