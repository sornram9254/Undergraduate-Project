<?php
session_start();
include('admin/include/nocsrf.php');
if ( isset($_POST['user']) && isset($_POST['pass']) )
{
    try
    {
        // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
        NoCSRF::check( 'csrf_token', $_POST, true, 60*10, false );
        // form parsing, DB inserts, etc.
        // ...
        
        $host="localhost";
        $user="root";
        $pass="";
        $db_name="stdcheckdb";
        $tbl_name="login";
        
        mysql_connect("$host", "$user", "$pass")or die("cannot connect");
        mysql_select_db("$db_name")or die("cannot select DB");
        
        $username=$_POST['user'];
        $password=$_POST['pass'];
        
        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysql_real_escape_string($username);
        $password = mysql_real_escape_string($password);
        
        $sql="SELECT * FROM $tbl_name WHERE user='$username' and pass='$password'";
        $resultCount=mysql_query($sql);
        $count=mysql_num_rows($resultCount); 
        $expt = time()+3600;       
        if($count==1) // if success
        {
            $result = 'Login Successful.';
            //setcookie("expt", $expt, $expt);
            setcookie("username", $username, $expt);
            //header("location:admin/");
        }
        else {
            $result = 'The user name or password is incorrect.';
            $dir = basename(dirname($_SERVER['PHP_SELF']));
            //header("location:../$dir");
            setcookie("hasLogin", 0);
        }
        
    }
    catch ( Exception $e )
    {
        // CSRF attack detected
        $result = $e->getMessage() . ' Form ignored.';
    }
}
else
{
    $result = 'No post data yet.';
}
// Generate CSRF token to use in form hidden field
$token = NoCSRF::generate( 'csrf_token' );
?>


<h1>CSRF sandbox</h1>
<pre style="color: red"><?php echo $result; ?></pre>
<form name="csrf_form" action="#" method="post">
    <h2>Form using generated token.</h2>
    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
    <input type="text" name="user" value="">
    <input type="text" name="pass" value="l;ylfuotgikgv'">
    <input type="submit" value="Send form">
</form>
<form name="nocsrf_for