<?php
    error_reporting(E_ERROR | E_PARSE);
    header('Content-Type: text/html; charset=utf-8');
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
    require("include/SQL.php");
    require("include/dbconnect.php");
    $user = $_COOKIE['username'];
    $level = $_COOKIE['level'];
    if($level==0){
        $pageID = 5;
    }else{
        $pageID = 8;
    }
    if($level==0){
        $title = $pageListIconSTD[$pageID][0];
    }else{
        $title = $pageListIcon[$pageID][0];
    }
    $pageName = basename($_SERVER['PHP_SELF']);
    
    isset($_REQUEST['currPass']) ? $currPass = $_REQUEST['currPass'] : $currPass = '';
    isset($_REQUEST['newPass']) ? $newPass = $_REQUEST['newPass'] : $newPass = '';
    isset($_REQUEST['newPassConf']) ? $newPassConf = $_REQUEST['newPassConf'] : $newPassConf = '';
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?=$title?></title>
    <?php require("include/_INCLUDE.php"); ?>
</head>
<body>
    <?php require("include/_HEAD.php"); ?>
    <?php require("include/_SidebarMenu.php"); ?>
    
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                   <div class="col-md-12">
                      <h2><?=$title?></h2>   
                      <h5>
                        <?php
                            require('include/_quote.php');
                        ?>
                      </h5>
<!-- ##############################################################################  -->
                  <!--   Kitchen Sink -->
                    <div class="panel panel-default">
                    <div class="panel-heading">เปลี่ยนรหัสผ่าน</div>
                        <div class="panel-body">
                            <div class="table-responsive">
<!--   ============ -->
            <form method="POST" action="changePass.php">
                <label>รหัสผ่านปัจจุบัน</label>
                <div class="form-group input-group" style="width:50%;min-width:250px;">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                    <input name="currPass" class="form-control" required="true" placeholder="รหัสผ่านปัจจุบัน" type="password">
                </div><hr/>
                <label>รหัสผ่านใหม่</label>
                <div class="form-group input-group" style="width:50%;min-width:250px;">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                    <input name="newPass" class="form-control" required="true" placeholder="รหัสผ่านใหม่" type="password">
                </div>
                <label>ยืนยันรหัสผ่าน</label>
                <div class="form-group input-group" style="width:50%;min-width:250px;">
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                    <input name="newPassConf" class="form-control" required="true" placeholder="ยืนยันรหัสผ่าน" type="password">
                </div>
                <div class="form-group input-group" style="width:50%;min-width:250px;">
                    <button type="submit" class="btn btn-primary" onclick="">ส่งข้อมูล</button>
                    <button type="reset" class="btn btn-default">ล้างข้อมูล</button>
                </div>
            </form>
<!--   ============ -->
                            </div>
                        </div>
                    </div>
                  <!-- End  Kitchen Sink -->
<!-- ##############################################################################  -->
<?php
    if($currPass!='' && $newPass!='' && $newPassConf!=''){
        $exec = new cSQL();
        $res = $exec->getSQL("SELECT pass FROM login WHERE user = '".$user."';");
        $hash = implode(",", $res->fetch());
        if (password_verify($currPass, $hash)) {
            $result = "[DEBUG] - Password is valid!";
            if($level==0){
                $this_stmt = $this_db->prepare("UPDATE `stdlogin` SET STDCITIZEN = :PASS WHERE STDID = :USER");
            }else{
                $this_stmt = $this_db->prepare("UPDATE `login` SET PASS = :PASS WHERE USER = :USER");
            }
            $this_stmt->bindParam(':USER', $user);
            $this_stmt->bindParam(':PASS', password_hash($newPassConf, PASSWORD_DEFAULT));
            if ($this_stmt->execute()) {
                $result .= "<br/>[DEBUG] - Update Success!";
                print "<script>
                          if(!alert('Update Success')) document.location = 'logout.php';
                       </script>";
            }else{
                $result = "[DEBUG] - Update Failure!";
                print "<script>alert('Update Failure');</script>";
            }
            ////////////////////////////
        } else {
            echo 'Invalid password.';
            $result = "[DEBUG] - Invalid password!";
        }
} else{}
?>
                   </div>
                </div>
            </div>
        </div>
        <?php require("include/_FOOTER.php"); ?>
</body>
</html>
