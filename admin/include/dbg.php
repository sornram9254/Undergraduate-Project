<?php
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb"; 
    $username = (isset($_POST['user_input'])) ? $_POST['user_input'] : '';
    $password = (isset($_POST['pass_input'])) ? $_POST['pass_input'] : '';
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
    $this_stmt = $this_db->prepare("SELECT id,user,pass,level FROM login WHERE user = :username");
    $this_stmt->execute(array(':username' => $username));
    $res = $this_stmt->fetch();
    $hash = $res['pass'];
    
    echo "PASS_INPUT : " . $password . "<br/>";
    echo "PASS_CRYPTO : " . $hash . "<br/>";
    if (password_verify($password, $hash)) {
        echo "PASS_FRM_DB : " . $hash . "<br/>";
        echo 'Password is valid!';
    } else {
        echo 'Invalid password.';
    }
?>

<form id="form" action="dbg.php" method="post" enctype="multipart/form-data">
Username: <input type="text" name="user_input" value="root" /> <br />
Password: <input type="password" name="pass_input" value="root" /> <br />
<input type="submit" value="Login" name="Submit" autofocus="true" />
</form>