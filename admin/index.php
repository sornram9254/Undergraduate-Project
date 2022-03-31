<?php
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
    $pageID = 1;
    $title = $pageListIcon[$pageID][0];
    $pageName = basename($_SERVER['PHP_SELF']);
    
    isset($_REQUEST['viewall']) ? $viewall = $_REQUEST['viewall'] : $viewall = '';
    isset($_REQUEST['currid']) ? $currid = $_REQUEST['currid'] : $currid = '';
    
    isset($_REQUEST['STU_CODE']) ? $STU_CODE = $_REQUEST['STU_CODE'] : $STU_CODE = '';
    isset($_REQUEST['CURR_NAME_THAI']) ? $CURR_NAME_THAI = $_REQUEST['CURR_NAME_THAI'] : $CURR_NAME_THAI = '';
    isset($_REQUEST['FAC_NAME_THAI']) ? $FAC_NAME_THAI = $_REQUEST['FAC_NAME_THAI'] : $FAC_NAME_THAI = '';
    isset($_REQUEST['ID_CARD']) ? $ID_CARD = $_REQUEST['ID_CARD'] : $ID_CARD = '';
    isset($_REQUEST['STU_FIRST_NAME_THAI']) ? $STU_FIRST_NAME_THAI = $_REQUEST['STU_FIRST_NAME_THAI'] : $STU_FIRST_NAME_THAI = '';
    isset($_REQUEST['STU_LAST_NAME_THAI']) ? $STU_LAST_NAME_THAI = $_REQUEST['STU_LAST_NAME_THAI'] : $STU_LAST_NAME_THAI = '';
    isset($_REQUEST['STU_FIRST_NAME_ENG']) ? $STU_FIRST_NAME_ENG = $_REQUEST['STU_FIRST_NAME_ENG'] : $STU_FIRST_NAME_ENG = '';
    isset($_REQUEST['STU_LAST_NAME_ENG']) ? $STU_LAST_NAME_ENG = $_REQUEST['STU_LAST_NAME_ENG'] : $STU_LAST_NAME_ENG = '';
    isset($_REQUEST['CURR_CODE']) ? $CURR_CODE = $_REQUEST['CURR_CODE'] : $CURR_CODE = '';
    isset($_REQUEST['DEPT_NAME_THAI']) ? $DEPT_NAME_THAI = $_REQUEST['DEPT_NAME_THAI'] : $DEPT_NAME_THAI = '';
    isset($_REQUEST['DIV_NAME_THAI']) ? $DIV_NAME_THAI = $_REQUEST['DIV_NAME_THAI'] : $DIV_NAME_THAI = '';
    isset($_REQUEST['DIV_SHRT_NAME']) ? $DIV_SHRT_NAME = $_REQUEST['DIV_SHRT_NAME'] : $DIV_SHRT_NAME = '';
    isset($_REQUEST['ROUND']) ? $ROUND = $_REQUEST['ROUND'] : $ROUND = '';
    isset($_REQUEST['STU_ST_DESC']) ? $STU_ST_DESC = $_REQUEST['STU_ST_DESC'] : $STU_ST_DESC = '';
    isset($_REQUEST['LEVEL_DESC']) ? $LEVEL_DESC = $_REQUEST['LEVEL_DESC'] : $LEVEL_DESC = '';
    
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb"; 
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
        <div id="page-wrapper" style='overflow:hidden'>
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
                  <hr/>
<!-- #############################################################################  -->
<?php
if($_COOKIE['level']==0){ // STUDENT
    $tbl_name = "data_std_detail_tb";
    $sql = "SELECT * FROM `$tbl_name` WHERE STU_CODE = $_COOKIE[username];";
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
    $this_db->exec("set names utf8");
    $res = $this_db->prepare($sql);
    $res->execute();
    $res->setFetchMode(PDO::FETCH_ASSOC);
    isset($_REQUEST['currid']) ? $currid = $_REQUEST['currid'] : $currid = '';
    foreach ($res as $row)
    {
        $STU_CODE             = $row['STU_CODE'];
        $ID_CARD              = $row['ID_CARD'];
        $STU_FIRST_NAME_THAI  = $row['STU_FIRST_NAME_THAI'];
        $STU_LAST_NAME_THAI   = $row['STU_LAST_NAME_THAI'];
        $STU_FIRST_NAME_ENG   = $row['STU_FIRST_NAME_ENG'];
        $STU_LAST_NAME_ENG    = $row['STU_LAST_NAME_ENG'];
        $CURR_CODE            = $row['CURR_CODE'];
        $CURR_NAME_THAI       = $row['CURR_NAME_THAI'];
        $FAC_NAME_THAI        = $row['FAC_NAME_THAI'];
        $DEPT_NAME_THAI       = $row['DEPT_NAME_THAI'];
        $DIV_NAME_THAI        = $row['DIV_NAME_THAI'];
        $DIV_SHRT_NAME        = $row['DIV_SHRT_NAME'];
        $ROUND                = $row['ROUND'];
        $STU_ST_DESC          = $row['STU_ST_DESC'];
        $LEVEL_DESC           = $row['LEVEL_DESC'];
    }
    if(strlen($STU_CODE)==0){
        //print "ไม่พบข้อมูล / ยังไม่ได้ลงทะเบียนบัตรนักศึกษา";
        print <<<HTML
<div class="panel panel-default">
    <div class="panel-heading">ข้อมูลส่วนตัว</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
ไม่พบข้อมูล / ยังไม่ได้ลงทะเบียนบัตรนักศึกษา
            </table>
        </div>
    </div>
</div>
HTML;
    }else{
        $CURR_NAME_THAI = preg_replace("/หลักสูตร/", "", $CURR_NAME_THAI);
        $FAC_NAME_THAI = preg_replace("/คณะ/", "", $FAC_NAME_THAI);
print <<<HTML
<div class="panel panel-default">
    <div class="panel-heading">ข้อมูลส่วนตัว</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <td width="20%;">รหัสนักศึกษา </td>
                    <td>$STU_CODE</td>
                </tr>
                <tr>
                    <td>รหัสประจำตัวประชาชน</td>
                    <td>$ID_CARD</td>
                </tr>
                <tr>
                    <td>ชื่อ-สกุล</td>
                    <td>
                    $STU_FIRST_NAME_THAI $STU_LAST_NAME_THAI
                    <br/>
                    $STU_FIRST_NAME_ENG $STU_LAST_NAME_ENG
                    </td>
                </tr>
                <tr>
                    <td>หลักสูตร</td>
                    <td><a href="CourseFull.php?courseInfo=$CURR_CODE" target="_blank">$CURR_NAME_THAI</a></td>
                </tr>
                <tr>
                    <td>คณะ/วิทยาลัย</td>
                    <td>$FAC_NAME_THAI</td>
                </tr>
                <tr>
                    <td>ภาควิชา</td>
                    <td>$DEPT_NAME_THAI</td>
                </tr>
                <tr>
                    <td>สาขา</td>
                    <td>$DIV_NAME_THAI $DIV_SHRT_NAME-$ROUND</td>
                </tr>
                <tr>
                    <td>ระดับการศึกษา</td>
                    <td>$LEVEL_DESC</td>
                </tr>
            </table>
        </div>
    </div>
</div>
HTML;
        //<tr><td>สถานภาพ</td><td>$STU_ST_DESC</td></tr>
        include("include/_news.php");
    }
}else{ // ADMIN | DEPARTMENT | TEACHER
    $tbl_name = "room";
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
    $this_db->exec("set names tis620");
    $sql = "SELECT * FROM `$tbl_name`";
    $res = $this_db->prepare($sql);
    $res->execute();
    $res->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($res as $row)
    {
        $ROOM_NUMBER[] = $row['ROOM_NUMBER'];
    }
    if(count($ROOM_NUMBER)==0){
        print "ไม่พบข้อมูล";
    }else{
        //จำนวนห้องเรียนทั้งหมดภายในภาควิชา
        print "<h4>จำนวนห้องเรียนทั้งหมดภายในคณะ  " . count($ROOM_NUMBER) . " ห้อง</h4>";
    }
    //------------------------
    $tbl_name = "device";
    $sql = "SELECT * FROM `$tbl_name`";
    $res = $this_db->prepare($sql);
    $res->execute();
    $res->setFetchMode(PDO::FETCH_ASSOC);
    isset($_REQUEST['currid']) ? $currid = $_REQUEST['currid'] : $currid = '';
    foreach ($res as $row)
    {
        $DEVICE_ID[] = $row['DEVICE_ID'];
        $DEVICE_NAME[] = $row['DEVICE_NAME'];
        $DEVICE_INSTANT_ID[] = $row['DEVICE_INSTANT_ID'];
        $ROOM[] = $row['ROOM'];
    }
    if(count($DEVICE_ID)==0){
        print "ไม่พบข้อมูล";
    }else{
?>
<!-- <div class="panel panel-default">
    <div class="panel-heading">ข้อมูลเครื่องอ่านบัตร</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
<?php /*
    ///
    $timeOK     =   null;
    $timeLate   =   null;
    $timeOver   =   null;
    $timeNOP    =   null;
    ///
    require_once("include/SQL.php");
    $exec = new cSQL();
//    $res = $exec->getSQL("SELECT c.ID, d.STU_CODE, d.STU_FIRST_NAME_THAI,d.STU_LAST_NAME_THAI, c.ROOM,c.TIME, crr.COURSE_NAME, crr.COUSE_NO, crr.TEACH_TIME, crr.TEACH_TIME_LATE
//FROM  card_uid_citizenid c
//INNER JOIN data_std_detail_tb d
//ON c.UID = d.UID OR c.CITIZENID = d.ID_CARD
//INNER JOIN data_curr_room crr
//ON c.ROOM = crr.ROOM_NO");
$execStd = new cSQL();
$resStd = $execStd->getSQL("SELECT distinct(UID) FROM `card_uid_citizenid`;");
$rowStd = $resStd->fetch();
$stdCount = $resStd->rowCount();
///
    foreach ($res as $row)
    {
        $time = (int)$row['TIME'];
        $TimeZoneNameFrom="UTC";
        $TimeZoneNameTo="Asia/Bangkok";
        ///
        $time    = (int)$row['TIME'];
        $time = date_create(date('r', $time), new DateTimeZone($TimeZoneNameFrom))
        ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("H:i");
        ///
        $timeDay = (int)$row['TIME'];
        $timeDay = date_create(date('r', $timeDay), new DateTimeZone($TimeZoneNameFrom))
        ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("D");
    
        preg_match_all("/(\d+:\d+)-(\d+:\d+)/", $row['TEACH_TIME'], $m_TEACH_TIME);
        for($i=0;$i<1;$i++){
            $TEACH_TIME_start = $m_TEACH_TIME[1][$i];
        }
        for($i=0;$i<1;$i++){
            $TEACH_TIME_end = $m_TEACH_TIME[2][$i];
        }
        $before_TEACH_TIME = ($TEACH_TIME_start-1).":00";
        $TEACH_TIME_LATE = date('H:i', strtotime('+30 minutes', strtotime($TEACH_TIME_start)));
        
        if(strtotime($time) > strtotime($TEACH_TIME_start) && strtotime($time) <= strtotime($TEACH_TIME_LATE)){ //
             $timeLate++;   
        }elseif(strtotime($time) <= strtotime($TEACH_TIME_start) && strtotime($time) > strtotime($before_TEACH_TIME)){ //
            $timeOK++;
        }elseif(strtotime($time) > strtotime($before_TEACH_TIME) && strtotime($time) < strtotime($TEACH_TIME_end)){ //
            $timeOver++;
        }else{
            $timeNOP++;
        }
        print "</tr>\n";
    }
    */
?>
<tr>
    <td width="30%" style="min-width:200px;">จำนวนนักศึกษา</td>
    <td><?=$stdCount;?></td>
</tr>
<tr>
    <td>เข้าเรียนทัน</td>
    <td><?=$timeOK;?></td>
</tr>
<tr>
    <td>เข้าเรียนสาย</td>
    <td><?=$timeLate;?></td>
</tr>
<tr>
    <td>ขาดเรียน</td>
    <td><?=$timeOver;?></td>
</tr>
<tr>
    <td>เช็คอินก่อนเวลา</td>
    <td><?=$timeNOP;?></td>
</tr>
            </table>
        </div>
    </div>
</div>-->
<?php    }
/////////////////////////////////////////
/////////////////////////////////////////
/////////////////////////////////////////
?>
<hr/>
<div class="panel panel-default">
    <div class="panel-heading">ข้อมูลเครื่องอ่านบัตร</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>DEVICE_NAME</th>
                        <th>DEVICE_INSTANT_ID</th>
                        <th>ROOM</th>
                    </tr>
                </thead>
                <tbody>
<?php
        for($i=0;$i<=count($DEVICE_ID)-1;$i++){
            $x=$i;
            print "<tr>";
            print "<td>".$DEVICE_ID[$i]."</td>";
            print "<td>".$DEVICE_NAME[$i]."</td>";
            print "<td>".$DEVICE_INSTANT_ID[$i]."</td>";
            print "<td>".$ROOM[$i]."</td>";
            print "</tr>";
        }
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- #############################################################################  -->
<?php }
    if($_COOKIE['level']==0){}else{
        print "จำนวนห้องที่ติดตั้งเครื่องอ่านบัตร : " . count($DEVICE_ID) . " ห้อง";
        print "<hr/>";
        include("include/_news.php");
    }
?>
                 <hr />
        </div>
        <?php require("include/_FOOTER.php"); ?>
</body>
</html>
