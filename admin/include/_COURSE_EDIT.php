<?php
    if($_POST['selectID'])
    {
        try
        {
            $selectID           =   $_POST['selectID'];
            $opRoom             =   $_POST['opRoom'];
            $opDay              =   $_POST['opDay'];
            $txtTeacher         =   $_POST['txtTeacher'];
            $txtTeachTime       =   $_POST['txtTeachTime'];
            $txtTeachTimeLate  =   $_POST['txtTeachTimeLate'];
            $host="localhost";
            $user="root";
            $pass="";
            $db_name="stdcheckdb"; 
            $table = "data_curr_room";
            $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
            $sql = "UPDATE `stdcheckdb`.`data_curr_room`
            SET `ROOM_NO`       = '".$opRoom."',
                `DAY`           = '".$opDay."',
                `TEACHER`       = '".$txtTeacher."',
                `TEACH_TIME`    = '".$txtTeachTime."',
                `TEACH_TIME_LATE`    = '".$txtTeachTimeLate."'
            WHERE `data_curr_room`.`ID_CURR` = '".$selectID."';";
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