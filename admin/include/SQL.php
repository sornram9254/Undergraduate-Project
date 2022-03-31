<?php
    class cSQL{
        //public $txt = "Hello!"; // static
        var $lol;
        var $regexResult;
        public function getSQL($sqlCommand = 'invalid sql command / cannot be null',$charset = 'utf8'){
            $host="localhost";
            $user="root";
            $pass="";
            $db_name="stdcheckdb"; 
            $table = "data_std_detail_tb";
            $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
            $this_db->exec("set names ".$charset);
            
            $res = $this_db->prepare($sqlCommand);
            $res->execute();
            $res->setFetchMode(PDO::FETCH_ASSOC);
            return $res;
        }
    }
?>