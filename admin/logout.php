<?php
    $dir = basename(dirname(dirname($_SERVER['PHP_SELF'])));
    $chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;
    #$dir = basename(__DIR__);
    if (isset($_COOKIE['username']) && isset($_COOKIE['level'])) {
        setcookie('username', null, -1,"/$dir/");
        unset($_COOKIE['username']);
        
        setcookie('level', null, -1,"/$dir/");
        unset($_COOKIE['level']);
        
# get_browser
$currDir = strtolower(basename(dirname(__DIR__)));
if ($chrome) {
    setcookie('username', null, -1,"/$currDir");
    setcookie('level', null, -1,"/$currDir");
}
        //print "<script>alert('".$currDir."');</script>";
        header("location:../../");
        return true;
    }
?>