<?php
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
    require("include/dbconnect.php");
    $tbl_name = "login";
    $tbl_std_name = "stdlogin";
    $pageID = 9;
    $title = $pageListIcon[$pageID][0];
    $pageName = basename($_SERVER['PHP_SELF']);
    
    require("include/_chkPermission.php");
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
                   </div>
                </div>
<!-- ##################################################################################  -->
                 <hr />
                  <!--   Kitchen Sink -->
                    <div class="panel panel-default">
                        <div class="panel-heading">กรุณากรอกข้อมูลให้ครบทุกช่อง</div>
                        <div class="panel-body">
                            <div class="table-responsive">
<?php
    $list = array( // USER,PASS,IP,TIME,LEVEL
        //         [0]                [1]          [2]          [3]
        1 => array("ชื่อผู้ใช้",         "tag",          "user",       "text"),
        2 => array("รหัสผ่าน",         "lock",         "pass",      "password"),
        3 => array("ไอพี",           "list-alt",     "ip",        "text"),  // auto get ip
        4 => array("อีเมลล์",          "list-alt",     "email",     "email"),
        5 => array("ระดับสิทธิ์",        "list-alt",     "level"),
    );

    // Check Token
    try
    {
        NoCSRF::check( 'csrf_token', $_POST, true, 60*10, false );
        try{
            isset($_REQUEST['name']) ? $name = $_REQUEST['name'] : $name = '';
            #$get_id = (isset($_GET['id'])) ? $_GET['id'] : '';
            //
            $user =  (isset($_POST['user'])) ? $_POST['user']   : '';
            $pass =  (isset($_POST['pass'])) ? $_POST['pass']   : '';
            $ip = get_ip_address();
            $email = (isset($_POST['email'])) ? $_POST['email'] : '';
            $level = (isset($_POST['level'])) ? $_POST['level'] : '';
            ////////////////////////////////
            $query = $this_db->prepare( "SELECT USER
                         FROM $tbl_name 
                         WHERE USER = :USER" );
            $query->bindParam(':USER', $user);
            $query->execute();
            if( $query->rowCount() > 0 ) { # If rows are found for query
                $result = "[DEBUG] - User already exists!";
                $result .= "<br/>[DEBUG] - Insert Failure!";
            }
            else {
                $result = "[DEBUG] - User not found!";
                // prepare sql and bind parameters
                $url = 'http://202.28.17.14/stdimages.php?stdcode='.$user;
                $dest = 'assets/img/student/'.$user.'.jpg';
                    
                if($level==0){
                    $this_stmt = $this_db->prepare("INSERT INTO $tbl_std_name (STDID,STDCITIZEN,IP,EMAIL,LEVEL,PICTURE)
                    VALUES (:USER, :PASS, :IP, :EMAIL, :LEVEL, :PICTURE)");
                    //if(!copy($url, $dest)){/*echo "failed to download.\n";*/}
                    $this_stmt->bindParam(':PICTURE', $dest);
                    if(!@copy($url, $dest)){ $dest = 'assets/img/find_user.png'; }
                }else{
                    $this_stmt = $this_db->prepare("INSERT INTO $tbl_name (USER,PASS,IP,EMAIL,LEVEL)
                    VALUES (:USER, :PASS, :IP, :EMAIL, :LEVEL)");
                }
                $this_stmt->bindParam(':USER', $user);
                $this_stmt->bindParam(':PASS', password_hash($pass, PASSWORD_DEFAULT));
                $this_stmt->bindParam(':IP', $ip);
                $this_stmt->bindParam(':EMAIL', $email);
                $this_stmt->bindParam(':LEVEL', $level);
                // insert a row
                if ($this_stmt->execute()) {
                    $result .= "<br/>[DEBUG] - Insert Success!";
                }else{
                    $result = "[DEBUG] - Insert Failure!";
                }
            }
        }
        catch(PDOException $e)
        {
            //echo "Error: " . $e->getMessage();
            $result = "[DEBUG] - Error: " . $e->getMessage();
        }
        $this_db = null;
    }
    catch ( Exception $e )
    {
        // CSRF attack detected
        $result = "[DEBUG] - " . $e->getMessage();
    }
    // Generate CSRF token to use in form hidden field
    $token = NoCSRF::generate( 'csrf_token' );
?>
<!-- SEARCH -------->
<form method="POST" action="AddMember.php">
<?php for($count=1;$count<=count($list);$count++){ ?>
<div class="form-group input-group" style="width:50%;min-width:250px;">
    <?php if($count==5){ ?>
    <div class="form-group" style="padding-bottom:30px;">
        <label><?php echo $list[5][0]; ?></label>
        <select name="level" class="form-control" required>
            <option value="">--- กรุณาเลือก ---</option>
            <option value="1">ผู้ดูแลระบบ</option>
            <option value="2">เจ้าหน้าที่</option>
            <option value="3">อาจารย์</option>
            <option value="0">นักเรียน</option>
        </select>
    </div>
    <?php
        }
        else{
    ?>
    <span class="input-group-addon"><i class="fa fa-<?php echo $list[$count][1]; ?>"></i></span>
    <input type="<?php echo $list[$count][3]; ?>" name="<?php echo $list[$count][2]; ?>" class="form-control" required="true" placeholder="<?php echo $list[$count][0]; ?>" <?php
            if($count==3){
                echo 'readonly="true" value="' . get_ip_address() . '"';
            }
        ?> />
</div>
    <?php
        }
    }
    ?>
<input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
<button type="submit" class="btn btn-primary" onClick>ส่งข้อมูล</button>
<button type="reset" class="btn btn-default">ล้างข้อมูล</button>
</form>
<!-- SEARCH -------->
                            </div>
                        </div>
                    </div>
                  <!-- End  Kitchen Sink -->
<!-- ##################################################################################  -->
                 <hr />
            </div>
        </div>
        <?php require("include/_FOOTER.php"); ?>
</body>
</html>
