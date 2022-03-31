<?php  // DEBUG -> 161
    error_reporting(0);
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
    require("include/dbconnect.php");
    require("include/SQL.php");
    $pageID = 6;
    $title = $pageListIcon[$pageID][0];
    $pageName = basename($_SERVER['PHP_SELF']);
    
    require("include/_chkPermission.php");
    
    isset($_REQUEST['currCode']) ? $currCode = $_REQUEST['currCode'] : $currCode = '';
    isset($_REQUEST['stuYear']) ? $stuYear = $_REQUEST['stuYear'] : $stuYear = '';
    isset($_REQUEST['arrCurrNameFinal']) ? $arrCurrNameFinal = $_REQUEST['arrCurrNameFinal'] : $arrCurrNameFinal = '';
    isset($_REQUEST['arrCurrDescFinal']) ? $arrCurrDescFinal = $_REQUEST['arrCurrDescFinal'] : $arrCurrDescFinal = '';
    
    isset($_REQUEST['viewall']) ? $viewall = $_REQUEST['viewall'] : $viewall = '';
    isset($_REQUEST['import']) ? $import = $_REQUEST['import'] : $import = '';
    isset($_REQUEST['stdID']) ? $stdID = $_REQUEST['stdID'] : $stdID = '';
    
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb"; 
    $table = "data_curr_room";
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host;charset=utf8", $user, $pass);
    $this_db->exec("set names utf8");
    $sql = null;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?=$title?></title>
    <?php require("include/_INCLUDE.php"); ?>
    <!-- |||||||||||||||||||||||||||||||| -->
    <style>
        .valueDB,.valueDB.focus{
            width:100px;
        }
    </style>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {            // table id=''  | class='delete'
            $('table#editTable td button.edit').click(function()
            {
                var id = $(this).closest('tr').attr('selectThis'); // ID
                var eq = $(this).closest('tr').index();
                var opRoom       = $('#editTable [name=opRoom]:eq('+ eq +')').val();
                var opDay        = $('#editTable [name=opDay]:eq('+ eq +')').val();
                // fix : can't get select value
                if(opDay=='MON'){
                    opDay = 'M';
                }
                if(opDay=='TUE'){
                    opDay = 'T';
                }
                if(opDay=='WED'){
                    opDay = 'W';
                }
                if(opDay=='THU'){
                    opDay = 'H';
                }
                if(opDay=='FRI'){
                    opDay = 'F';
                }
                var txtTeacher   = $('#editTable [name=txtTeacher]:eq('+ eq +')').val();
                var txtTeachTime = $('#editTable [name=txtTeachTime]:eq('+ eq +')').val();
                var txtTeachTimeLate = $('#editTable [name=txtTeachTimeLate]:eq('+ eq +')').val();
                // http://stackoverflow.com/a/9328781/5464184
                // data: { code: code, userid: userid }
                var data = 'selectID='      + id +
                           '&opRoom='       + opRoom +
                           '&opDay='        + opDay +
                           '&txtTeacher='   + txtTeacher +
                           '&txtTeachTime='   + txtTeachTime +
                           '&txtTeachTimeLate=' + txtTeachTimeLate;
                var parent = $(this).parent().parent();
                $.ajax(
                {
                    type: "POST",
                    url: "include/_COURSE_EDIT.php",
                    data: data,
                    cache: false,
                    success: function(output)
                    {
                        if(output!=0){
                            $('div#editResult').html("Record has been successfully Updated.");
                            //$("#editTable").load(location.href + " #editTable");
                        }else{
                            $('div#editResult').text("Oops, something went wrong. Please try again later.");
                        }
                    }
                });	
            });
        });
    </script>
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
                 <hr />
                 <form method="GET" action="#">
                    <!-- SEARCH -------->
                    <div class="form-group input-group" style="width:300px">
                        <input type="text" name="currCode" class="form-control" placeholder="กรุณากรอกข้อมูล">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                    <!-- SEARCH -------->
                 </form>
                 <table>
                    <tr>
                        <td>
<?php 
$level = $_COOKIE['level'];
if($level==1 || $level==2){ // root / teacher ?>
<!--<form method='POST'>-->
<form method='GET'>
    <input type='submit' class='btn btn-default btn-sm' value='นำเข้าข้อมูล'/>
    <input type='hidden' value='true' name='import'/>
</form>
<?php }else {} ?>
                        </td>
                        <td>
<form method='GET'>
<!--<form method='POST'>-->
    <input type='submit' class='btn btn-default btn-sm' value='ดูข้อมูลทั้งหมด'/>
    <input type='hidden' value='true' name='viewall'/>
</form>
                        </td>
                    </tr>
                 </table>
                <br/>
<!-- ###############################################################################  -->
                <!--   Kitchen Sink -->
                <div class="panel panel-default">
                    <div class="panel-heading">ตารางสอน</div>
                    <div class="panel-body">
                        <div class="table-responsive">
<?php
    // fix ; can't get optionSelect value
    $regexSearchTerm = urldecode(file_get_contents('php://input'));
    preg_match("/currTerm=(\d\/\d{4})/", $regexSearchTerm, $arrTerm);
    $currTerm = $arrTerm[1];
    //
    /*
    $all = file_get_contents('php://input');
    print_r($all);
    print('<hr/>import=' . $import);
    print('<br/>viewall=' . $viewall);
    print('<br/>currCode=' . $currCode);
    print('<br/>currTerm=' . $currTerm);
    print('<br/>stuYear=' . $stuYear . '<br/>');
    */
    if($viewall=='true'){
    ///////////////////////////////////////////////////////////////////////////////
        $result  = "<b>[VIEWALL]</b>";
        $result .= '<br/>import=' . $import;
        $result .= '<br/>viewall=' . $viewall;
        $result .= '<br/>currCode=' . $currCode;
        $result .= '<br/>currTerm=' . $currTerm;
        $result .= '<br/>stuYear=' . $stuYear;
?>
<form method="POST" action="">
    <div class="form-group input-group" style="width:50%;min-width:250px;">
        <div class="form-group" style="padding-bottom:30px;">
        <label>ปีการศึกษา</label>
<?php
        $this_db->exec("set names tis620");
        $sql = "SELECT distinct(TERM_YEAR) FROM `data_curr_room`;";
        $res = $this_db->prepare($sql);
        $res->execute();
        $res->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($res as $row)
        {
            $TERM_YEAR[] = $row['TERM_YEAR'];
        }
        if(count($TERM_YEAR)==0){
            print "ไม่พบข้อมูล";
        }else{
            print "<select class='form-control' name='currTerm'>";
            for($i=0;$i<=count($TERM_YEAR)-1;$i++){
                $x=$i;
                print "<option value='".$TERM_YEAR[$i]."'";
                if($TERM_YEAR[$i]==$currTerm){ print "selected";
                    //$selectCurrTerm = substr($currTerm, 0, strlen($currTerm) - 5);
                }
                print ">".$TERM_YEAR[$i]."</option>";
            }
            print "</select>";
        }
?>
        </div>
    </div>
    
    <div class="form-group input-group" style="width:50%;min-width:250px;">
        <div class="form-group" style="padding-bottom:30px;">
        <label>หลักสูตร</label>
<?php
        $sql = "SELECT * FROM `curr_xml_import`";
        $res = $this_db->prepare($sql);
        $this_db->exec("set names tis620");
        $res->execute();
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row_count = $res->rowCount();
        //if(count($xml_arr_currID)==0){
        //    print "ไม่พบข้อมูล";
        //}else{
        //$sql = "SELECT * FROM `curr_xml_import`";
        //$res = $this_db->prepare($sql);
        //$res->execute();
        //$res->setFetchMode(PDO::FETCH_ASSOC);//$row_count = $res->rowCount();
        foreach ($res as $row)
        {
            $xml_arr_currID[] = $row['CURR_ID'];
            $xml_arr_currXML[] = $row['CURR_TH'];
        }
        if(count($xml_arr_currID)==0){
            print "ไม่พบข้อมูล";
        }else{
            print "<select class='form-control' name='currCode'>";
            for($i=0;$i<=count($xml_arr_currID)-1;$i++){
                $x=$i;
                print "<option value='".$xml_arr_currID[$i]."'";
                if($xml_arr_currID[$i]==$currCode){ print "selected"; $selectCurrName = $xml_arr_currXML[$i];}
                print ">".$xml_arr_currID[$i]." ". $xml_arr_currXML[$i] ."</option>";
            }
            print "</select>"; 
        }
        //}
?>
        </div>
        <div class="form-group input-group" style="width:50%;min-width:250px;">
            <div class="form-group" style="padding-bottom:30px;">
            <label>ชั้นปี</label>
            <select class="form-control" name="stuYear">
            <?php // ?>
            <?php
                for($i=1;$i<=5;$i++){
                    print "<option value='$i'";
                    if($i==$stuYear){ print "selected";}
                    print ">ชั้นปีที่ $i</option>";
                }
            ?>
            </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">ค้นหา</button>
    </div>
</form>
<?php
    if($currCode!='' && $currTerm!=''){
        if($_COOKIE['level']==3){ // teacher
            $sql = "SELECT * FROM data_curr_room WHERE CURR_NO = '".$currCode."' AND TERM_YEAR = '$currTerm' AND TEACHER LIKE '%".$_COOKIE['username']."%';";
        }else{
            $sql = "SELECT * FROM data_curr_room WHERE CURR_NO = '".$currCode."' AND TERM_YEAR = '$currTerm' AND STD_YEAR = $stuYear;"; 
            //print $sql;
        }
        $res = $this_db->prepare($sql);
        $res->execute();
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row_count = $res->rowCount();
        if($row_count == 0){
            print "ไม่พบข้อมูล";
            return;
        }
        print "<hr/>";
        print "<h4>$currCode $selectCurrName - ปีการศึกษา $currTerm ชั้นปีที่ $stuYear</h4>";
?>
        <table id='editTable' class='table table-striped table-bordered table-hover'>
            <thead>
                <tr>
                <th width=5%>ลำดับ</th>
                <th width=5%>รหัสวิชา</th>
                <th width=15%>ชื่อวิชา</th>
                <th width=12%>ห้องเรียน</th>
                <th width=5%>ตอน</th> <!-- ตอนเรียน -->
                <th width=8%>อาจารย์</th>
                <th width=10%>วัน</th>
                <th width=10%>เวลา</th>
                <th width=10%>เช็คอิน<br/> ล่วงหน้า (Min)</th>
                <!--<th width=9%>ปีการศึกษา</th>-->
                <th width=10%>การจัดการ</th>
                <!--  ระยะเวลาติ๊กบัตรก่อนเข้าเรียน -->
                </tr>
            </thead>
<?php
    foreach ($res as $row){
        print "<tr selectThis='" . $row['ID_CURR'] . "'>\n";
        print "    <td>" . $row['ID_CURR'] . "</td>\n";
        print "    <td>" . $row['COUSE_NO'] . "</td>\n";
        print "    <td style='font-size:12px;'>" . $row['COURSE_NAME'] . "</td>\n";
?>
<td>
<select class='form-control' name='opRoom'>
    <?php
        include("include/_room_list.php");
        foreach ($arrRoom as $valArrRoom){ ?>
            <option <?php if($row['ROOM_NO']==$valArrRoom){print "value='$valArrRoom' selected";} ?>><?php print $valArrRoom; ?></option>
        <?php }
    ?>
</select>
</td>
<?php
            print "    <td>" . $row['SEC'] . "</td>\n";
            print "    <td><input type='text' name='txtTeacher' value='" . $row['TEACHER'] . "' class='valueDB' style='width:100%'/></td>\n";
?>
<td>
<select class='form-control' name='opDay'>
    <option <?php if($row['DAY']=='M'){print "value='M' selected";} ?>>MON</option>
    <option <?php if($row['DAY']=='T'){print "value='T' selected";} ?>>TUE</option>
    <option <?php if($row['DAY']=='W'){print "value='W' selected";} ?>>WED</option>
    <option <?php if($row['DAY']=='H'){print "value='H' selected";} ?>>THU</option>
    <option <?php if($row['DAY']=='F'){print "value='F' selected";} ?>>FRI</option>
</select>
</td>
<?php
            print "    <td><input type='text' name='txtTeachTime' value='" . $row['TEACH_TIME'] . "' class='valueDB'/></td>\n";
            print "    <td><input type='text' name='txtTeachTimeLate' value='" . $row['TEACH_TIME_LATE'] . "' class='valueDB'/></td>\n";
            //print "    <td style='text-align:center;'>" . $row['TERM_YEAR'] . "</td>\n";
                                // class='btn btn-default'
            print "    <td><button class='btn btn-primary edit'><i class='fa fa-refresh'></i> Update</button></td>\n";
            print "</tr>\n";
        }
        echo "</table>";
        /////
        print "<div id='editResult'></div>";  // DEBUG RESULT
        /////
    }
    ///////////////////////////////////////////////////////////////////////////////
    }
    if($import=='true' && ($level==1 || $level==2)){
    ///////////////////////////////////////////////////////////////////////////////
        $result  = "<b>[IMPORT]</b>";
        $result .= '<br/>import=' . $import;
        $result .= '<br/>viewall=' . $viewall;
        $result .= '<br/>currCode=' . $currCode;
        $result .= '<br/>currTerm=' . $currTerm;
        $result .= '<br/>stuYear=' . $stuYear;
?>
<form method="POST" action="CourseManage.php?import=true">
<input type='hidden' value='true' name='import'/>
<?php
    $sql = "SELECT * FROM `curr_xml_import`";// $tbl_name";
    $this_db->exec("set names tis620");
    $res = $this_db->prepare($sql);
    $res->execute();
    $res->setFetchMode(PDO::FETCH_ASSOC);//$row_count = $res->rowCount();
    foreach ($res as $row)
    {
        $xml_arr_currID[] = $row['CURR_ID'];
        $xml_arr_currXML[] = $row['CURR_TH'];
    }
    if(count($xml_arr_currID)==0){
        print "ไม่พบข้อมูล";
    }else{
?>
    <!--
    <div class="form-group input-group" style="width:50%;min-width:250px;">
        <div class="form-group" style="padding-bottom:30px;">
        <label>ภาควิชา</label>
        <select class="form-control" name="stuDept">
            <option value="0204">คอมพิวเตอร์ศึกษา</option>
            <option value="0201">ครุศาสตร์เครื่องกล</option>
            <option value="0202">ครุศาสตร์ไฟฟ้า</option>
            <option value="0203">ครุศาสตร์โยธา</option>
        </select>
        </div>
    </div>
    -->
    <div style='font-size:22px;'>ปีการศึกษาปัจจุบัน : 
    <?php
        //  01/08 - 31/12
        //  01/01 - 31/07
        //$currentTERM = date("m");
        if($currentTERM >= '08' && $currentTERM <= '12'){
            print "ภาคต้น";
        }
        else if($currentTERM >= '01' && $currentTERM <= '05'){
            print "ภาคปลาย";
        }
        else {
            print "ภาคฤดูร้อน";
        }
    ?>
    </div><br/>
    <div class="form-group input-group" style="width:50%;min-width:250px;">
        <div class="form-group" style="padding-bottom:30px;">
        <label>กรุณาเลือกหลักสูตรที่ต้องการดึงข้อมูลตารางเรียน</label>
        <select class="form-control" name="currCode">
<?php
        for($i=0;$i<=count($xml_arr_currID)-1;$i++){
            $x=$i;
            print "<option value='$xml_arr_currID[$i]'>$xml_arr_currID[$i] $xml_arr_currXML[$i]</option>";
        }
        echo "</select>";
        echo "</div>";
        echo "</div>";
    }
?>
    <div class="form-group input-group" style="width:50%;min-width:250px;">
        <div class="form-group" style="padding-bottom:30px;">
        <label>ชั้นปี</label>
        <select class="form-control" name="stuYear">
            <option value="1">ชั้นปีที่ 1</option>
            <option value="2">ชั้นปีที่ 2</option>
            <option value="3">ชั้นปีที่ 3</option>
            <option value="4">ชั้นปีที่ 4</option>
            <option value="5">ชั้นปีที่ 5</option>
        </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">นำเข้าข้อมูล</button>
</form>
<?php
        include("include/_klogic_room_number.php");
        if($import!='' && $currCode!='' && $stuYear!=''){
//print "5555=> ".$currCode;
            $tbl_name_currImport = "data_curr_room";
            $query = $this_db->prepare( "SELECT *
                        FROM $tbl_name_currImport 
                        WHERE CURR_NO = $currCode AND STD_YEAR = $stuYear");
                       //WHERE CURR_NO = :CURR_NO" );
            //$query->bindParam(':CURR_NO', $currCode);
            $query->execute();
            if( $query->rowCount() > 0 ) { # If rows are found for query
                $result = "[DEBUG] - courseCurr already exists!";
                $result .= "<br/>[DEBUG] - Insert Failure! : ".$currCode;
                // EXISTS
            }
            else {
                foreach (array_combine($arrCurrNameFinal, $arrCurrDescFinal) as $name => $desc) {
                    if(preg_match("/(\w.\d+)\s+(\w)\s+(\d+:\d+-\d+:\d+)\s+(.+)\s+(.+)\s+/",$desc,$displayMatch1)) {
                        $sec   = $displayMatch1[1];
                        $day   = $displayMatch1[2];
                        $time  = $displayMatch1[3];
                        $teach = $displayMatch1[4];
                        $room  = $displayMatch1[5];
                    }
                    if(preg_match("/(\d+) (.*) (\d)\((\d-\d)\)/",$name,$displayMatch2)) {
                        $curNo   = $displayMatch2[1];
                        $curName = $displayMatch2[2];
                        $credit  = $displayMatch2[3];
                    }
                    // INSERT
                    //print $sql."<br/>";
                    $TEACH_TIME_LATE = 30;
                    $this_stmt = $this_db->prepare("INSERT INTO $tbl_name_currImport (CURR_NO, COUSE_NO, COURSE_NAME, CREDIT, ROOM_NO, SEC, TEACHER, TEACH_TIME, TEACH_TIME_LATE, DAY, TERM_YEAR, STD_YEAR)
                    VALUES (:CURR_NO, :COUSE_NO, :COURSE_NAME, :CREDIT, :ROOM_NO, :SEC, :TEACHER, :TEACH_TIME, :TEACH_TIME_LATE, :DAY, :TERM_YEAR, :STD_YEAR)");
                    $this_stmt->bindParam(':CURR_NO',           $currCode);
                    $this_stmt->bindParam(':COUSE_NO',          $curNo);
                    $this_stmt->bindParam(':COURSE_NAME',       $curName);
                    $this_stmt->bindParam(':CREDIT',            $credit);
                    $this_stmt->bindParam(':ROOM_NO',           $room);
                    $this_stmt->bindParam(':SEC',               $sec);
                    $this_stmt->bindParam(':TEACHER',           $teach);
                    $this_stmt->bindParam(':TEACH_TIME',        $time);
                    $this_stmt->bindParam(':TEACH_TIME_LATE',   $TEACH_TIME_LATE); // default 30 min
                    $this_stmt->bindParam(':DAY',               $day);
                    $this_stmt->bindParam(':TERM_YEAR',         $term_year_course);
                    $this_stmt->bindParam(':STD_YEAR',          $stuYear);
                
                    // insert a row
                    if ($this_stmt->execute()) {
                        $result .= "<br/>[DEBUG] - Insert Success! : ".$currCode;
                    }else{
                        $result = "[DEBUG] - Insert Failure! : ".$currCode;
                    }
                }
            }
        }
    ///////////////////////////////////////////////////////////////////////////////
    }
    if($currCode!='' && $stuYear=='' && $viewall==''){
    ///////////////////////////////////////////////////////////////////////////////
        # SEARCH
        $result  = "<b>[SEARCH]</b>";
        $result .= '<br/>import=' . $import;
        $result .= '<br/>viewall=' . $viewall;
        $result .= '<br/>currCode=' . $currCode;
        $result .= '<br/>currTerm=' . $currTerm;
        $result .= '<br/>stuYear=' . $stuYear;
        if($_COOKIE['level']==3){ // teacher
            $sql = "SELECT * FROM data_curr_room WHERE CURR_NO = '".$currCode."' AND TEACHER LIKE '%".$_COOKIE['username']."%';";
        }else{
            $sql = "SELECT * FROM data_curr_room WHERE CURR_NO = '".$currCode."';";     
        }
        
        $res = $this_db->prepare($sql);
        $res->execute();
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row_count = $res->rowCount();
        if($row_count == 0){
            print "ไม่พบข้อมูล";
            return;
        }
?>
        <table id='editTable' class='table table-striped table-bordered table-hover'>
            <thead>
                <tr>
                <th width=5%>ลำดับ</th>
                <th width=5%>รหัสวิชา</th>
                <th width=15%>ชื่อวิชา</th>
                <th width=12%>ห้องเรียน</th>
                <th width=5%>ตอน</th> <!-- ตอนเรียน -->
                <th width=8%>อาจารย์</th>
                <th width=10%>วัน</th>
                <th width=10%>เวลา</th>
                <th width=10%>เช็คอิน<br/> ล่วงหน้า (Min)</th>
                <!--<th width=9%>ปีการศึกษา</th>-->
                <th width=10%>การจัดการ</th>
                <!--  ระยะเวลาติ๊กบัตรก่อนเข้าเรียน -->
                </tr>
            </thead>
<?php
    foreach ($res as $row){
        print "<tr selectThis='" . $row['ID_CURR'] . "'>\n";
        print "    <td>" . $row['ID_CURR'] . "</td>\n";
        print "    <td>" . $row['COUSE_NO'] . "</td>\n";
        print "    <td style='font-size:12px;'>" . $row['COURSE_NAME'] . "</td>\n";
?>
<td>
<select class='form-control' name='opRoom'>
    <?php
        include("include/_room_list.php");
        foreach ($arrRoom as $valArrRoom){ ?>
            <option <?php if($row['ROOM_NO']==$valArrRoom){print "value='$valArrRoom' selected";} ?>><?php print $valArrRoom; ?></option>
        <?php }
    ?>
</select>
</td>
<?php
            print "    <td>" . $row['SEC'] . "</td>\n";
            print "    <td><input type='text' name='txtTeacher' value='" . $row['TEACHER'] . "' class='valueDB' style='width:100%'/></td>\n";
?>
<td>
<select class='form-control' name='opDay'>
    <option <?php if($row['DAY']=='M'){print "value='M' selected";} ?>>MON</option>
    <option <?php if($row['DAY']=='T'){print "value='T' selected";} ?>>TUE</option>
    <option <?php if($row['DAY']=='W'){print "value='W' selected";} ?>>WED</option>
    <option <?php if($row['DAY']=='H'){print "value='H' selected";} ?>>THU</option>
    <option <?php if($row['DAY']=='F'){print "value='F' selected";} ?>>FRI</option>
</select>
</td>
<?php
            print "    <td><input type='text' name='txtTeachTime' value='" . $row['TEACH_TIME'] . "' class='valueDB'/></td>\n";
            print "    <td><input type='text' name='txtTeachTimeLate' value='" . $row['TEACH_TIME_LATE'] . "' class='valueDB'/></td>\n";
            //print "    <td style='text-align:center;'>" . $row['TERM_YEAR'] . "</td>\n";
                                // class='btn btn-default'
            print "    <td><button class='btn btn-primary edit'><i class='fa fa-refresh'></i> Update</button></td>\n";
            print "</tr>\n";
        }
        echo "</table>";
        /////
        print "<div id='editResult'></div>";  // DEBUG RESULT
        /////
    ///////////////////////////////////////////////////////////////////////////////
    }
    if($viewall!='true' && $import!='true' && $currCode==''){
    ///////////////////////////////////////////////////////////////////////////////
        print "กรุณากรอกหมายเลขหลักสูตร";
        $result = "No post data yet.";
    ///////////////////////////////////////////////////////////////////////////////
    }
?>
                        </div>
                    </div>
                </div>
                  <!-- End  Kitchen Sink -->
<script>
    function open_win() {
        window.open('include/addTerm.php', '_blank','toolbar=0,location=no,menubar=0,height=800,width=800');
    }
</script>
<?php
    $exec = new cSQL();
    $res = $exec->getSQL("SELECT * FROM `term`");
?>
                <div class="panel panel-default">
                    <div class="panel-heading">ข้อมูลภาคเรียนการศึกษา</div>
                    <div class="panel-body">
                    <?php if($_COOKIE['level']==1 || $_COOKIE['level']==2){ ?>
                        <div class="table-responsive">
                        <button class="btn btn-default" onclick="open_win(1)"><i class="fa fa-edit "></i> เพิ่ม/แก้ไขข้อมูล</button><p></p>
                    <?php } ?>
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable( {
            "order": [[ 2, "asc" ]] // asc | desc
        } );
    } );
</script>
<table class="table table-striped table-bordered table-hover delTable" id="dataTables-example">
    <thead>
        <tr>
            <!--<th width=5%>ID</th>-->
            <th width=5%>เทอม</th>
            <th width=5%>ปีการศึกษา</th>
            <th width=5%>วันเริ่มภาคเรียน</th>
            <th width=5%>วันสิ้นสุดภาคเรียน</th>
        </tr>
    </thead>
<?php
    $TimeZoneNameFrom="UTC";
    $TimeZoneNameTo="Asia/Bangkok";
    foreach ($res as $row)
    {
        $time_sDate    = (int)$row['START_DATE'];
        $time_sDate = date_create(date('r', $time_sDate), new DateTimeZone($TimeZoneNameFrom))
        ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("d M Y");
        $time_eDate    = (int)$row['END_DATE'];
        $time_eDate = date_create(date('r', $time_eDate), new DateTimeZone($TimeZoneNameFrom))
        ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("d M Y");
        print "<tr>\n";
        //print "<td>".$row['ID']."</td>\n";
        print "<td>".$row['TERM']."</td>\n";
        print "<td>".$row['YEAR']."</td>\n";
        print "<td>".$time_sDate."</td>\n";
        print "<td>".$time_eDate."</td>\n";
        print "</tr>\n";
    }
?>
</table>
                        </div>
                    </div>
                </div>
<!-- ###############################################################################  -->
<!-- ###############################################################################  -->
                 <hr />
            </div>
        </div>
        <?php require("include/_FOOTER.php"); ?>
</body>
</html>