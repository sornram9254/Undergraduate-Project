<?php
    session_start();
    include('admin/include/nocsrf.php');
    
    if(isset($_COOKIE['username'])) { 
        header("location:admin");
    }
    if ( isset($_POST['user']) && isset($_POST['pass']) ||
         isset($_POST['stdID']) && isset($_POST['stdCitizen']) )
    {
        try
        {
            NoCSRF::check( 'csrf_token', $_POST, true, 60*10, false );
            require("admin/include/dbconnect.php");
            $stdID=$_POST['stdID'];
            $stdCitizen=$_POST['stdCitizen'];
            $stdID = stripslashes($stdID);
            $stdCitizen = stripslashes($stdCitizen);
            $stdID = mysql_real_escape_string($stdID);
            $stdCitizen = mysql_real_escape_string($stdCitizen);
            
            $username = (isset($_POST['user'])) ? $_POST['user'] : '';
            $password = (isset($_POST['pass'])) ? $_POST['pass'] : '';
            $username = stripslashes($username);
            $password = stripslashes($password);
            $username = mysql_real_escape_string($username);
            $password = mysql_real_escape_string($password);
            
            if(empty($_POST['user']) && empty($_POST['pass'])){
                $tbl_name="stdLogin";
                $username = $stdID;
                $this_stmt = $this_db->prepare("SELECT ID,STDID,STDCITIZEN,LEVEL FROM $tbl_name WHERE STDID = :username");
            }else{
                $tbl_name="login";
                $this_stmt = $this_db->prepare("SELECT ID,USER,PASS,LEVEL FROM $tbl_name WHERE USER = :username");
            }
            $this_stmt->execute(array(':username' => $username));
            $res = $this_stmt->fetch();
            if(empty($_POST['user']) && empty($_POST['pass'])){
                $hash = $res['STDCITIZEN'];
                $password = $stdCitizen;
            }else{
                $hash = $res['PASS'];
            }
            $level = $res['LEVEL'];
            
            if (password_verify($password, $hash)) {
                $result = '[DEBUG] - Login Successful.';
                setcookie("username", $username, $expt);
                setcookie("level", $level, $expt);
                header("location:admin/");
            } else {
                $result = '[DEBUG] - The USER or PASS incorrect.';
            }
        }
        catch ( Exception $e )
        {
            $result = "[DEBUG] - " . $e->getMessage();
        }
    }
    else
    {
        $result = '[DEBUG] - No post data yet ,No Token.';
    }
    $token = NoCSRF::generate( 'csrf_token' );
?>