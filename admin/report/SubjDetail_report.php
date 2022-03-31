<html moznomarginboxes mozdisallowselectionprint>
<script>
    window.print();
    window.onfocus=function(){ window.close();}
</script>
<style>
    @media print {
    @page { margin: 10px; }
      body { margin: 1.6cm; }
         * { font-size:12px; }
    }
    h1,h2,h3,h4,h5,h6{
        text-align: center;
    }
</style>
<meta charset="utf-8" />
<link href="../assets/css/bootstrap.css" rel="stylesheet" />
<?php
    require("../include/_chkPermission.php");
    require("../include/_INCLUDE.php");
    require("../include/SQL.php");
    $exec = new cSQL();
    
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
?>
<!-- ################################################################################################-->
<?php
    $res = $exec->getSQL("SELECT * FROM `card_uid_citizenid`");
    $rowArr = $res->fetch();
    $row = implode(",", $rowArr);
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
        array_push($arr_ROOM,$arrROOM);
        $sql = "SELECT * FROM `data_curr_room`
                WHERE ROOM_NO LIKE '%".$arrROOM."%'
                AND DAY = '".$Day."'
                AND CURR_NO = '".$row_CURR_CODE."'
                AND (TEACH_TIME LIKE '%".$Hour.':00'."-%' OR TEACH_TIME LIKE '%".$HourBefore.':00'."-%');";
        $res = $exec->getSQL($sql);$sql = null;
        $count = $res->rowCount();
        $row_COUSE = $res->fetch();
        $COUSE_NO = $row_COUSE['COUSE_NO'];
        $TEACH_TIME = $row_COUSE['TEACH_TIME'];
        array_push($arr_COUSE_NO,$COUSE_NO);
        preg_match_all("/(\d+:\d+)-(\d+:\d+)/", $TEACH_TIME, $m_TEACH_TIME);
        for($i=0;$i<1;$i++){
            @$TEACH_TIME_start = $m_TEACH_TIME[1][$i];
        }
        for($i=0;$i<1;$i++){
            @$TEACH_TIME_end = $m_TEACH_TIME[2][$i];
        }
        $rowTerm = $row_COUSE['TERM_YEAR'];
        $rowYear = $row_COUSE['STD_YEAR'];
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
        preg_match("/(\d)\/(\d+)/", $rowTerm, $output_array);
        $termF = @$output_array[1];
        $termL = @$output_array[2];
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
?>
<!-- #####################################################################################################-->
<?php
if($f_submit!=''){    
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
<br/>
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
        }
    }
}
    function msg($string=""){
        return "<span style='font-size:26px;'>".$string."</span><br/>\n";
    }
    function statusSummary($arrStatus){
        $status = implode(",", array_unique($arrStatus));
        return $status;
    }
?>
<?php
    if($f_submit!=''){
?>
<?php
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
<h3>รายงานการเข้าเรียนของนักศึกษา</h3>
<table class="table table-striped table-bordered table-hover delTable " id="dataTables-example">
    <thead>
        <tr>
            <th width='200px'>เลขประจำตัวนักศึกษา</th>
            <th width='200px'>เข้าเรียนทัน/สัปดาห์</th>
            <th width='200px'>เข้าเรียนสาย/สัปดาห์</th>
            <th width='200px'>ขาดเรียน/สัปดาห์</th>
            <th width='200px'>รวมสัปดาห์ที่เข้าเรียน</th>
        <tr>
    </thead>
<?php
    foreach ($summary as $stdID => $data) {
        $ontime = isset($data['ontime']) ? $data['ontime'] : 0;
        $late   = isset($data['late'])   ? $data['late']   : 0;
        $over   = isset($data['over'])   ? $data['over']   : 0;
        $total  = $ontime+$late+$over;
        print "<tr>\n";
        print "    <td><a href='StudentDetail.php?stdInfo=".$stdID."' target='_blank'>".$stdID."</a></td>\n";
        print "    <td>".$ontime."</td>\n";
        print "    <td>".$late."</td>\n";
        if($total!=$last_week){
            $fixOver  = $last_week-$total; // 0,1,1
            $fixTotal = $total+$fixOver;
            print "    <td>".$fixOver."</td>\n";
            print "    <td>".$fixTotal."</td>\n";
        }else{
            print "    <td>".$over."</td>\n";
            print "    <td>".$total."</td>\n";
        }
        print "</tr>\n";
    }
?>
</table>
<?php
    }
?>