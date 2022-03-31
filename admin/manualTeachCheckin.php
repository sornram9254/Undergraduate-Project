<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>NOP</title>
    <?php
        require("include/_INCLUDE.php");
        require("include/dbconnect.php");
        require("include/SQL.php");
        require("include/_chkPermission.php");
    ?>
<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<link href="assets/css/font-awesome.css" rel="stylesheet" />
<link href="assets/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-datetimepicker.th.js"></script>
    
    
</head>
<body>
    <script>
        $(document).ready(function(){
            $("#txtShowHide").text("[คลิกเพื่อซ่อน/แสดง]");
            $("#showHide").click(function(){
                $(".tableShowHide").toggle(300);
            });
            /////////
            $("#stdList_late").click(function(){
                $(".txtStdList_late").toggle(300);
            });
            $("#stdList_ontime").click(function(){
                $(".txtStdList_ontime").toggle(300);
            });
            $("#stdList_over").click(function(){
                $(".txtStdList_over").toggle(300);
            });
        });
    </script>
<?php
    isset($_REQUEST['viewall']) ? $viewall = $_REQUEST['viewall'] : $viewall = '';
    isset($_REQUEST['insert']) ? $insert = $_REQUEST['insert'] : $insert = '';
    
    isset($_REQUEST['CurrNo']) ? $CurrNo = $_REQUEST['CurrNo'] : $CurrNo = '';
    isset($_REQUEST['CourseNum']) ? $CourseNum = $_REQUEST['CourseNum'] : $CourseNum = '';
    isset($_REQUEST['Sec']) ? $Sec = $_REQUEST['Sec'] : $Sec = '';
    isset($_REQUEST['RoomNo']) ? $RoomNo = $_REQUEST['RoomNo'] : $RoomNo = '';
    isset($_REQUEST['DateTime']) ? $DateTime = $_REQUEST['DateTime'] : $DateTime = '';
    isset($_REQUEST['DateTimeEnd']) ? $DateTimeEnd = $_REQUEST['DateTimeEnd'] : $DateTimeEnd = '';
    isset($_REQUEST['Term']) ? $Term = $_REQUEST['Term'] : $Term = '';
    isset($_REQUEST['Year']) ? $Year = $_REQUEST['Year'] : $Year = '';
    ///////////////////////////////////////
    $late     =0;   // สาย
    $ontime   =0;   // ทัน
    $over     =0;   // เกิน
    $overtime =0;   // นอกช่วงเวลา
    
    isset($_REQUEST['l_room'])    ? $l_room    = $_REQUEST['l_room']    : $l_room    = '';
    isset($_REQUEST['l_teacher']) ? $l_teacher = $_REQUEST['l_teacher'] : $l_teacher = '';
    isset($_REQUEST['l_year'])    ? $l_year    = $_REQUEST['l_year']    : $l_year    = '';
    isset($_REQUEST['l_term'])    ? $l_term    = $_REQUEST['l_term']    : $l_term    = '';
    isset($_REQUEST['l_course'])  ? $l_course  = $_REQUEST['l_course']  : $l_course  = '';
    isset($_REQUEST['f_submit'])  ? $f_submit  = $_REQUEST['f_submit']  : $f_submit  = '';

    isset($_REQUEST['late']) ? $late = $_REQUEST['late'] : $late = '';
    isset($_REQUEST['ontime']) ? $ontime = $_REQUEST['ontime'] : $ontime = '';
    isset($_REQUEST['over']) ? $over = $_REQUEST['over'] : $over = '';
    isset($_REQUEST['overtime']) ? $overtime = $_REQUEST['overtime'] : $overtime = '';
    isset($_REQUEST['TEACH_TIME_start']) ? $TEACH_TIME_start = $_REQUEST['TEACH_TIME_start'] : $TEACH_TIME_start = '';
    isset($_REQUEST['TEACH_TIME_end']) ? $TEACH_TIME_end = $_REQUEST['TEACH_TIME_end'] : $TEACH_TIME_end = '';

    function status($strStatus){
        if($strStatus=='late'){
            return "<p style='font-size:30px;margin-top:-15px;'>
                        <span class='label label-warning'>สาย</span>
                    </p>";
        }
        if($strStatus=='ontime'){
            return "<p style='font-size:30px;margin-top:-15px;'>
                        <span class='label label-success'>ทัน</span>
                    </p>";
        }
        if($strStatus=='over'){
            return "<p style='font-size:30px;margin-top:-15px;'>
                        <span class='label label-danger'>เกินเวลา</span>
                    </p>";
        }
    }
    function course($strCourse,$l_course){
        if($strCourse==$l_course){
            $exec = new cSQL();
            $resCourse = $exec->getSQL("SELECT COURSE_NAME FROM `data_curr_room` WHERE COUSE_NO = '".$strCourse."';");
            $rowCourseName = implode(",", $resCourse->fetch());
            $resCourse = null;
            return $rowCourseName;
        }else{
            return "";
        }
    }
    function termStart($rowTerm, $rowYear){
        $exec = new cSQL();
        $sql = "SELECT START_DATE FROM `term` WHERE TERM = $rowTerm AND YEAR = $rowYear LIMIT 1;";
        $res = $exec->getSQL($sql);$sql = null;
        $row = $res->fetch();
        $start_date = @implode(",", $row);
        return $start_date;
    }
    function datediffInWeeks($date1, $date2){
        if($date1 > $date2) return datediffInWeeks($date2, $date1);
        $first = DateTime::createFromFormat('Y-d-m', $date1);
        $second = DateTime::createFromFormat('Y-d-m', $date2);
        return floor($first->diff($second)->days/7);
    }
    function getTime($time,$option){
        $TimeZoneNameFrom="UTC";
        $TimeZoneNameTo="Asia/Bangkok";
        $timeDay = date_create(date('r',(int)$time), new DateTimeZone($TimeZoneNameFrom))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("D");
        $timeDayNo = date_create(date('r',(int)$time), new DateTimeZone($TimeZoneNameFrom))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("d");
        $timeMonth = date_create(date('r',(int)$time), new DateTimeZone($TimeZoneNameFrom))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("m");
        $timeYear = date_create(date('r',(int)$time), new DateTimeZone($TimeZoneNameFrom))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("Y");
        $timeHour = date_create(date('r',(int)$time), new DateTimeZone($TimeZoneNameFrom))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("G"); // H 07:00 | G 7:00
        $timeMinute = date_create(date('r',(int)$time), new DateTimeZone($TimeZoneNameFrom))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("i");
        if($option=="Day"){
            if($timeDay=='Mon'){
                $timeDay = 'M';
            }
            elseif($timeDay=='Tue'){
                $timeDay = 'T';
            }
            elseif($timeDay=='Wed'){
                $timeDay = 'W';
            }
            elseif($timeDay=='Thu'){
                $timeDay = 'H';
            }
            elseif($timeDay=='Fri'){
                $timeDay = 'F';
            }
            elseif($timeDay=='Sat'){
                $timeDay = 'S';
            }
            return $timeDay;
        }
        if($option=="DayNo"){
            return $timeDayNo;
        }
        if($option=="Month"){
            return $timeMonth;
        }
        if($option=="Year"){
            return $timeYear;
        }
        if($option=="Hour"){
            return $timeHour;
        }
        if($option=="Minute"){
            return $timeMinute;
        }
    }
?>
<div class="panel panel-default">
    <div class="panel-heading">การเรียนการสอนนอกเวลา</div>
    <div class="panel-body">
        <div class="table-responsive" style='overflow:hidden'>
<!--#################################################################-->
<form method='get' style="float:left;margin-right:8px;">
    <input type='submit' class='btn btn-default btn-sm' value='ดูข้อมูลทั้งหมด'/>
    <input type='hidden' value='true' name='viewall'/>
</form>
<?php if($level!=0){ ?>
<form method='get'>
    <input type='submit' class='btn btn-default btn-sm' value='เพิ่มข้อมูล'/>
    <input type='hidden' value='true' name='insert'/>
</form><?php } ?><hr/>
<?php
    if($viewall!="" && $insert==""){
?>
<form method="GET">
<select name="l_course" class="form-control" style="width:15%;min-width:142px;float:left;margin-right:10px;">
    <option value="0">
    - - - วิชา - - -</option>
    <?php
        $arrCouse = array();
        $resultSQL = new cSQL();
        $res = $resultSQL->getSQL("SELECT man.ID,man.COURSE_NO,cr.COURSE_NAME
                                   FROM manualteachcheckin man
                                   INNER JOIN data_curr_room cr
                                   ON cr.COUSE_NO = man.COURSE_NO;");
    foreach ($res as $row){
        print "<option value='".$row['COURSE_NO']."'>".$row['COURSE_NAME']."</option>";
    }
    ?>
</select>
<!---->
<select name="l_term" class="form-control" style="width:15%;min-width:142px;float:left;margin-right:10px;">
    <option value="0">  - - - เทอม - - -</option>
    <option value='1'>1</option>
    <option value='2'>2</option>
</select>
<!---->
<select name="l_year" class="form-control" style="width:15%;min-width:142px;float:left;margin-right:10px;">
    <option value="0"> - - - ปีการศึกษา - - -</option>
        <?php for($i=2558;$i<=2570;$i++){
            print "<option value='".$i."'>".$i."</option>";
        }?>
    </select>
    <input type='submit' class='btn btn-default btn-sm' value='ค้นหา'/>
</form>
<!--------------------------------------------------------------------->
<?php
    }
    if($CourseNum!='' && $Sec!='' && $RoomNo!='' && $DateTime!='' && $DateTimeEnd!=''){
        $DateTime = strtotime('-5 hours', strtotime($DateTime)); // fix timezone err
        $DateTimeEnd = strtotime('-5 hours', strtotime($DateTimeEnd)); // fix timezone err
        $TimeZoneNameFrom="UTC";
        $TimeZoneNameTo="Asia/Bangkok";
        $timeS = date_create(date('r', $DateTime), new DateTimeZone($TimeZoneNameFrom))
        ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("H:i");
        $timeE = date_create(date('r', $DateTimeEnd), new DateTimeZone($TimeZoneNameFrom))
        ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("H:i");
        $tDay = getTime($DateTime,'Day');
        $time = $timeS."-".$timeE;
        // add
        $this_stmt = $this_db->prepare("INSERT INTO `manualTeachCheckin` (CURR_NO,COURSE_NO,SEC,ROOM,TEACH_TIME,DAY,TERM,YEAR)
        VALUES (:CURR_NO,:COURSE_NO,:SEC,:ROOM,:TEACH_TIME,:DAY,:TERM,:YEAR)");
        $this_stmt->bindParam(':CURR_NO', $CurrNo);
        $this_stmt->bindParam(':COURSE_NO', $CourseNum);
        $this_stmt->bindParam(':SEC', $Sec);
        $this_stmt->bindParam(':ROOM', $RoomNo);
        $this_stmt->bindParam(':TEACH_TIME',     $time);
        $this_stmt->bindParam(':DAY', $tDay);
        $this_stmt->bindParam(':TERM', $Term);
        $this_stmt->bindParam(':YEAR', $Year);
        if ($this_stmt->execute()) {
            //result .= "<br/>[DEBUG] - Insert Success!";
            print "<script>alert('Insert Success!')</script>";
        }else{
            //$result = "[DEBUG] - Insert Failure!";
            print "<script>alert('Insert Failure!')</script>";
        }
?>
<?php
    }
//<!--------------------------------------------------------------------->
    if($l_course!='' && $l_term!='' && $l_year!=''){
    $exec = new cSQL();
    $res = $exec->getSQL("SELECT COURSE_NAME FROM `data_curr_room` WHERE COUSE_NO = '".$l_course."' LIMIT 1;");
    $row = implode(",", $res->fetch());
    print "<div style='font-size:18px;'>".$l_course." : ".$row."</div>";
?>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
<?php
    $res = $exec->getSQL("SELECT * FROM `card_uid_citizenid`");
    //$rowArr = $res->fetch();
    //$row = implode(",", $rowArr);
    ////////////////////////////
    $arr_ID          = array();
    $arr_STD_ID      = array();
    $arr_TIME        = array();
    $arr_ROOM        = array();
    $arr_COUSE_NO    = array();
    $arr_STATUS      = array();
    $arr_WEEK        = array();
    $arr_SEC         = array();
    $arr_START_TERM  = array();
    ////////////////////////////   
    foreach ($res as $row)
    {
        $arrID = $row['ID'];
        $arrUID = $row['UID'];
        $arrCITIZENID = $row['CITIZENID'];
        $arrTIME = $row['TIME'];
        $arrIS_MIFARE = $row['IS_MIFARE'];
        $arrROOM = $row['ROOM'];
        array_push($arr_ID,$arrID);
        //--------------------------------------------------------------
        $exec = new cSQL();
        $res = $exec->getSQL("SELECT STU_CODE,CURR_CODE,DIV_SHRT_NAME,ROUND
                              FROM `data_std_detail_tb`
                              WHERE UID = '".$arrUID."'
                              OR ID_CARD = '".$arrCITIZENID."';");
        $rowSTD = $res->fetch();
        $row_STU_CODE  = $rowSTD['STU_CODE'];
        $row_CURR_CODE = $rowSTD['CURR_CODE'];
        $row_DIV       = $rowSTD['DIV_SHRT_NAME'];
        $row_ROUND     = $rowSTD['ROUND'];
        $res = null;
        //--------------------------------------------------------------
        array_push($arr_STD_ID,$row_STU_CODE);
        $Day   = getTime($arrTIME,'Day');   $DayNo = getTime($arrTIME,'DayNo');
        $Month = getTime($arrTIME,'Month');  $Year = getTime($arrTIME,'Year');
        $Hour  = getTime($arrTIME,'Hour'); $Minute = getTime($arrTIME,'Minute');
        $HourBefore = $Hour+1;      //////// BEFORE TEACH TIME
        array_push($arr_TIME,$Day.' '.$DayNo.'/'.$Month.'/'.$Year.' - '.$Hour.':'.$Minute);
        array_push($arr_ROOM,$arrROOM); //data_curr_room
        $sql = "SELECT * FROM `manualteachcheckin`
                WHERE ROOM LIKE '%".$arrROOM."%'
                AND DAY = '".$Day."'
                AND CURR_NO = '".$row_CURR_CODE."'
                AND (TEACH_TIME LIKE '%".$Hour.':00'."-%' OR TEACH_TIME LIKE '%".$HourBefore.':00'."-%');";
        $res = $exec->getSQL($sql);$sql = null;
        $count = $res->rowCount();
        $row_COUSE = $res->fetch();
        $COUSE_NO = $row_COUSE['COURSE_NO'];
        $TEACH_TIME = $row_COUSE['TEACH_TIME'];
        array_push($arr_COUSE_NO,$COUSE_NO);
        preg_match_all("/(\d+:\d+)-(\d+:\d+)/", $TEACH_TIME, $m_TEACH_TIME);
        for($i=0;$i<1;$i++){
            @$TEACH_TIME_start = $m_TEACH_TIME[1][$i];
        }
        for($i=0;$i<1;$i++){
            @$TEACH_TIME_end = $m_TEACH_TIME[2][$i];
        }
        //////////////////////////////////////////////////////////////////////////////////////////////
        $resLate = $exec->getSQL("SELECT TEACH_TIME_LATE FROM `data_curr_room` WHERE COUSE_NO = '".$COUSE_NO."'");
        $rowLate = @implode(",", $resLate->fetch());
        
        $time    =  $Hour.':'.$Minute;
        $before_TEACH_TIME = ($TEACH_TIME_start-1).":00";
        $TEACH_TIME_LATE = date('H:i', strtotime('+'.$rowLate.' minutes', strtotime($TEACH_TIME_start)));
        
        $stdCheckinTime = strtotime($time);
        $beforeTeach    = strtotime($before_TEACH_TIME);
        $teachLate      = strtotime($TEACH_TIME_LATE);
        $teachStart     = strtotime($TEACH_TIME_start);
        $teachEnd       = strtotime($TEACH_TIME_end);
        
        if($stdCheckinTime > $teachStart && $stdCheckinTime <= $teachLate){
            $late++;
            array_push($arr_STATUS,"late");
        }elseif($stdCheckinTime <= $teachStart && $stdCheckinTime > $beforeTeach){
            $ontime++;
            array_push($arr_STATUS,"ontime");
        }elseif($stdCheckinTime > $beforeTeach && $stdCheckinTime < $teachEnd){
            $over++;
            array_push($arr_STATUS,"over");
        }else{
            $overtime++;
            array_push($arr_STATUS,"overtime");
        }
        //////////////////////////////////////////////////////////////////////////////////////////////
        $exec = new cSQL();
        $termF = @$row_COUSE['TERM_YEAR'];
        $termL = @$row_COUSE['STD_YEAR'];
        $sql = "SELECT START_DATE FROM `term` WHERE TERM = '$termF' AND YEAR = '$termL' LIMIT 1;";
        $res = $exec->getSQL($sql);$sql = null;
        $start_date = @implode(",", $res->fetch());
        $count = $res->rowCount();
        try{
            $TimeZoneNameFrom="UTC";
            $TimeZoneNameTo="Asia/Bangkok";
            $time_    = (int)$row['TIME'];
            $time_ = date_create(date('r', $time_), new DateTimeZone($TimeZoneNameFrom))
            ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("Y-d-m");
            $time_s    = @$start_date;
            $time_s = date_create(date('r', $time_s), new DateTimeZone($TimeZoneNameFrom))
            ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("Y-d-m");
            $date = new DateTime($time_s);
            $dateTitle = date_create(date('r', (int)$row['TIME']), new DateTimeZone($TimeZoneNameFrom))
            ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("d-M-Y");
        }catch(Exception $e){}
        $weekNum = datediffInWeeks($time_, $time_s);
        if($count > 0){
            array_push($arr_WEEK,$weekNum);
        }else{array_push($arr_WEEK,0);} // fix Undefined offset
        array_push($arr_SEC,$row_DIV."-".$row_ROUND);
        if($time_s!="1970-01-01"){
            array_push($arr_START_TERM, $time_s);
        }else{array_push($arr_START_TERM,0);} // fix Undefined offset
    }
    print '<br/><button class="btn btn-default btn-sm btnReport" onclick="open_win(1)">สร้างรายงาน</button><br/><br/>';
    $arrKey = array();
    foreach ($arr_STATUS as $key => $val) {
        if ($val == "overtime") {
            array_push($arrKey,$key);
        }
    }
    foreach ($arr_COUSE_NO as $key => $val) {
        if ($val == "") {
            array_push($arrKey,$key);
        }
    }
    $arrKeyUnique = array_unique($arrKey);
    // function
    foreach($arrKeyUnique as $key){
        unset($arr_ID[$key]);
        unset($arr_STD_ID[$key]);
        unset($arr_TIME[$key]);
        unset($arr_ROOM[$key]);
        unset($arr_COUSE_NO[$key]);
        unset($arr_STATUS[$key]);
        unset($arr_WEEK[$key]);
        unset($arr_SEC[$key]);
        unset($arr_START_TERM[$key]);
    }
?>    
<div class="table-responsive">
<div class="panel-heading" id='showHide' style='background:#f5f5f5;border:1px solid #ddd !important;'>รายชื่อการเข้าเรียนของนักศึกษา
<div style='float:right;color:red;' id='txtShowHide'></div>
</div>
<div class='tableShowHide' style='display:none;'>
<table class="table table-striped table-bordered table-hover fullData " id="dataTables-example">
    <thead> <!-- อาจารย์    |    ตอนเรียน   -->
    <tr>
        <th width='50px' id='debug'>ID</th>
        <th width='50px'>รหัสนักศึกษา</th>
        <th width='50px' id='debug'>วันเวลา</th>
        <th width='50px' id='debug'>ห้องเรียน</th>
        <!--<th width='100px'>สัปดาห์</th>-->
        <th width='100px'>รายวิชา</th>
        <th width='100px'>ตอนเรียน</th>
        <th width='100px'>สถานะ</th>
        <th width='100px' id='debug'>[debug] - START_TERM</th>
    </tr>
    </thead>
<?php
    $summary_Index       = array();
    $summary_ID          = array();
    $summary_STD_ID      = array();  
    $summary_TIME        = array();
    $summary_ROOM        = array();
    $summary_WEEK        = array();
    $summary_courseName  = array();
    $summary_SEC         = array();
    $summary_STATUS      = array();
    $summary_START_TERM  = array();
    foreach ($arr_ID as $index => $value){
        $courseName = course($arr_COUSE_NO[$index],$l_course);
        if($courseName!=""){
            array_push($summary_Index,$index);
            array_push($summary_ID,$arr_ID[$index]);
            array_push($summary_STD_ID,$arr_STD_ID[$index]);
            array_push($summary_TIME,$arr_TIME[$index]);
            array_push($summary_ROOM,$arr_ROOM[$index]);
            array_push($summary_WEEK,$arr_WEEK[$index]);
            array_push($summary_courseName,$courseName);
            array_push($summary_SEC,$arr_SEC[$index]);
            array_push($summary_STATUS,$arr_STATUS[$index]);
            array_push($summary_START_TERM,$arr_START_TERM[$index]);
            
            print "<tr>\n";
            print "   <td id='debug'>".$arr_ID[$index]."</td>\n";
            print "   <td>".$arr_STD_ID[$index]."</td>\n";
            print "   <td id='debug'>".$arr_TIME[$index]."</td>\n";
            print "   <td id='debug'>".$arr_ROOM[$index]."</td>\n";
            //print "   <td>".$arr_WEEK[$index]."</td>\n";
            print "   <td>".$courseName."</td>\n"; // fix  Undefined variable
            print "   <td>".$arr_SEC[$index]."</td>\n";
            print "   <td>".status($arr_STATUS[$index])."</td>\n";
            print "   <td id='debug'>".$arr_START_TERM[$index]."</td>\n";
            print "</tr>\n";
        }
    }
    print "</table></div></div>";
    ///////////////////////////////////////////////////////////
    $arrSummary  = array();
    $last_week   = end($arr_WEEK);

    foreach($summary_STD_ID as $index => $value){
        if($summary_STD_ID[$index]==$value && $summary_STATUS[$index]=="ontime"){
            array_push($arrSummary,array($value,"ontime"));
        }
        if($summary_STD_ID[$index]==$value && $summary_STATUS[$index]=="late"){
            array_push($arrSummary,array($value,"late"));
        }
        if($summary_STD_ID[$index]==$value && $summary_STATUS[$index]=="over"){
            array_push($arrSummary,array($value,"over"));
        }
    }

    $summary = [];
    foreach ($arrSummary as $row) {
        if (!isset($summary[$row[0]][$row[1]])) {
            $summary[$row[0]][$row[1]] = 0;
        }
        $summary[$row[0]][$row[1]] += 1;
    }
?>
<h3>รายงานการเข้าเรียนของนักศึกษา<!--<br/>สัปดาห์ 1 ถึงสัปดาห์ที่ <?//=$last_week;?>--></h3>
<table class="table table-striped table-bordered table-hover delTable " id="dataTables-example">
    <thead>
        <tr>
            <th width='80px'>เลขประจำตัวนักศึกษา</th>
            <th width='60px'>ทัน/สัปดาห์</th>
            <th width='60px'>สาย/สัปดาห์</th>
            <th width='60px'>ขาด/สัปดาห์</th>
            <th width='60px'>สรุปยอด</th>
            <!--<th width='200px'>เปอร์เซ็นต์การเข้าเรียน<br/>ทัน/สาย/ขาด</th>-->
        <tr>
    </thead>
<?php
$sumOntime=0;
$sumLate=0;
$sumOver=0;
$sumTotal=0;
    foreach ($summary as $stdID => $data) {
        $ontime = isset($data['ontime']) ? $data['ontime'] : 0;
        $late   = isset($data['late'])   ? $data['late']   : 0;
        $over   = isset($data['over'])   ? $data['over']   : 0;
        $total  = $ontime+$late+$over;
        print "<tr>\n";
        print "    <td><a href='StudentDetail.php?stdInfo=".$stdID."' target='_blank'>".$stdID."</a></td>\n";
        print "    <td>".$ontime."</td>\n";
        print "    <td>".$late."</td>\n";
        ///
        if($ontime!=0){$sumOntime++;}
        if($late!=0){$sumLate++;}
        ///
        if($total!=$last_week){
            $fixOver  = $last_week-$total; // 0,1,1
            $fixTotal = $total+$fixOver;
            if($last_week==0){
                print "    <td>".$over."</td>\n";
            }else{
                print "    <td>".$fixOver."</td>\n";
            }
            if($last_week==0){
                print "    <td>".$total."</td>\n";
            }else{
                print "    <td>".$fixTotal."</td>\n";
            }
            //print "    <td>".$fixTotal."</td>\n";
            if($over!=0){$sumOver++;}
            if($total!=0){$sumTotal++;}
        }else{
            print "    <td>".$over."</td>\n";
            print "    <td>".$total."</td>\n";
            if($over!=0){$sumOver++;}
            if($total!=0){$sumTotal++;}
        }
        @$percentOntime = ($ontime*100)/$fixTotal;
        @$percentLate   = ($late*100)/$fixTotal;
        @$percentOver   = ($fixOver*100)/$fixTotal;

        @$percentOntime = number_format($percentOntime, 1, '.', '');
        @$percentLate   = number_format($percentLate,   1, '.', '');
        @$percentOver   = number_format($percentOver,   1, '.', '');
/*
        print "    <td>";
        print "
<div class='progress' style='width:100%;'>";
///////////////////// fix percent bar
if($percentOntime!="0.0" && $percentLate=="0.0" && $percentOver=="0.0"){
    print "<div class='progress-bar progress-bar-success' role='progressbar' style='width:100%;'>100%</div>";
}
if($percentLate!="0.0" && $percentOntime=="0.0" && $percentOver=="0.0"){
    print "<div class='progress-bar progress-bar-warning' role='progressbar' style='width:100%;'>100%</div>";
}
if($percentOver!="0.0" && $percentLate=="0.0" && $percentOntime=="0.0"){
    print "<div class='progress-bar progress-bar-danger' role='progressbar' style='width:100%;'>100%</div>";
}
/////////////////////
        print "
    <div class='progress-bar progress-bar-success' role='progressbar' style='width:".$percentOntime."%;'>  
".$percentOntime."%  
    </div>  
    <div class='progress-bar progress-bar-warning' role='progressbar' style='width:".$percentLate."%;'>  
".$percentLate."%  
    </div>  
    <div class='progress-bar progress-bar-danger' role='progressbar' style='width:".$percentOver."%;'>  
".$percentOver."%  
    </div>  
</div>";
        print "    </td>\n";
*/
        print "</tr>\n";
    }
?>
</table>
<?php
$sumOntime;
$sumLate;
$sumOver;
?>
<!--
<div id="donut-example"></div>
-->
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.metisMenu.js"></script>
<script src="assets/js/morris/raphael-2.1.0.min.js"></script>
<script src="assets/js/morris/morris.js"></script>
<script src="assets/js/custom.js"></script>
<script>
    function open_win() {
        var l_room = document.getElementById("l_room").value;
        var l_teacher = document.getElementById('l_teacher').value;
        var l_year = document.getElementById('l_year').value;
        var l_term = document.getElementById('l_term').value;
        var l_course = document.getElementById('l_course').value;
        window.open('report/SubjDetail_report.php?l_room='+l_room+'&l_teacher='+l_teacher+'&l_year='+l_year+'&l_course='+l_course+'&f_submit=true', '_blank','toolbar=0,location=no,menubar=0,height=400');
    }
</script>
















































<!--------------------------------------------------------------------->
<?php
    }
    if($insert!="" && $viewall==""){
?>
<!--------------------------------------------------------------------->
<div class="table-responsive">
    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
        <div class="form-group input-group" style="width:50%;min-width:250px;">
            <label>หลักสูตร</label>
            <select name="CurrNo" class="form-control" required>
                <option value="">--- กรุณาเลือก ---</option>
<?php
                $exec = new cSQL();
                $res = $exec->getSQL("SELECT CURR_ID,CURR_TH FROM `curr_xml_import`",'tis620');
                $row = implode(",", $res->fetch());
                foreach ($res as $row)
                {
                    print "<option value='".$row['CURR_ID']."'>".$row['CURR_ID'].' '.$row['CURR_TH']."</option>";
                }
                
?>
            </select>
        </div>
        <label>รหัสวิชา</label>
        <div class="form-group input-group" style="width:50%;min-width:250px;">
            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
            <input name="CourseNum" class="form-control" required="true" placeholder="รหัสวิชา" type="text">
        </div>
        <label>ตอนเรียน</label>
        <div class="form-group input-group" style="width:50%;min-width:250px;">
            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
            <input name="Sec" class="form-control" required="true" placeholder="ตอนเรียน" type="text">
        </div>
        <label>ห้องเรียน</label>
        <div class="form-group input-group" style="width:50%;min-width:250px;">
            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
            <select name="RoomNo" class="form-control">
                <option value="0"> - - ห้องเรียน - -</option>
        <?php
            $res = $exec->getSQL("SELECT distinct(ROOM_NO) FROM data_curr_room WHERE ROOM_NO ORDER BY `data_curr_room`.`ROOM_NO` ASC;");
            //$rowArr = $res->fetch();
            //$row = implode(",", $rowArr);
            foreach ($res as $row){
                ?>
                <option value='<?php print $row['ROOM_NO'];?>' <?php if($l_room==$row['ROOM_NO']){echo 'selected';}?>><?php print $row['ROOM_NO'];?></option>
                <?php
            }
        ?>
            </select>
        </div>
        <label>วันเวลา</label>
                <div class="form-group input-group" style="width:50%;min-width:250px;">
                    <span class="input-group-addon"><i class="fa fa-list-alt"></i></span>
                    <div class="input-group date form_date col-md-5" data-date-format="dd MM yyyy hh:ii" data-link-format="dd-mm-yyyy hh:ii">
                        <input class="form-control" type="text" value="" placeholder="เวลาสอน" name="DateTime" readonly required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
<script type="text/javascript">
//    [ startView ]
//    0 or 'hour' for the hour view
//    1 or 'day' for the day view
//    2 or 'month' for month view (the default)
//    3 or 'year' for the 12-month overview
//    4 or 'decade' for the 10-year overview. Useful for date-of-birth datetimepickers.
	$('.form_date').datetimepicker({
        language:  'th',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 0,  // 1
		forceParse: 0,
        timezone: 'GMT'
    });
</script>   
                </div>
                <!-- -->
<div class="form-group input-group" style="width:50%;min-width:250px;">
                    <span class="input-group-addon"><i class="fa fa-list-alt"></i></span>
                    <div class="input-group date form_date col-md-5" data-date-format="dd MM yyyy hh:ii" data-link-format="dd-mm-yyyy hh:ii">
                        <input class="form-control" type="text" value="" placeholder="สิ้นสุดการสอน" name="DateTimeEnd" readonly required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
<script type="text/javascript">
//    [ startView ]
//    0 or 'hour' for the hour view
//    1 or 'day' for the day view
//    2 or 'month' for month view (the default)
//    3 or 'year' for the 12-month overview
//    4 or 'decade' for the 10-year overview. Useful for date-of-birth datetimepickers.
	$('.form_date').datetimepicker({
        language:  'th',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 0,  // 1
		forceParse: 0,
        timezone: 'GMT'
    });
</script>   
                </div>
                <!-- -->
        <div class="form-group input-group" style="width:50%;min-width:250px;">
            <label>เทอม</label>
            <select name="Term" class="form-control" required>
                <option value="">--- กรุณาเลือก ---</option>
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
        </div>
        <div class="form-group input-group" style="width:50%;min-width:250px;">
            <label>ปีการศึกษา</label>
            <select name="Year" class="form-control" required>
                <option value="">--- กรุณาเลือก ---</option>
                <?php for($i=2558;$i<=2570;$i++){
                    print "<option value='".$i."'>".$i."</option>";
                }?>
            </select>
        </div>
        <!-- --- -->
        <div class="form-group input-group" style="width:50%;min-width:250px;">
            <button type="submit" class="btn btn-primary" onclick="">ส่งข้อมูล</button>
            <button type="reset" class="btn btn-default">ล้างข้อมูล</button>
        </div>
    </form>
</div>
<?php
    }
?>
<!--#################################################################-->
        </div>
    </div>
</div>
</body>
</html>
