<?php
    error_reporting(E_ERROR | E_PARSE);
    header('Content-Type: text/html; charset=utf-8');
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
    $pageID = 3;
    if($_COOKIE['level']==0){
        $title = $pageListIconSTD[$pageID][0];
    }else{
        $title = $pageListIcon[$pageID][0];
    }
    $pageName = basename($_SERVER['PHP_SELF']);
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
                 <form method="GET" action="CourseDetail.php">
                    <!-- SEARCH -------->
                    <div class="form-group input-group" style="width:300px">
                        <input type="text" name="courseInfo" class="form-control" placeholder="กรุณากรอกข้อมูล">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                 </form>
                <?php if($_COOKIE['level']==0){}else{ ?>
                <form method='GET'>
                    <input type='submit' class='btn btn-default btn-sm' value='ดูข้อมูลทั้งหมด'/>
                    <input type='hidden' value='true' name='viewall'/>
                </form><br/><?php } ?>
                    <!-- SEARCH -------->
<?php
    require("include/SQL.php");
    $execStd = new cSQL();
    $resStd = $execStd->getSQL("SELECT CURR_CODE FROM `data_std_detail_tb` WHERE STU_CODE=$_COOKIE[username];");
    $rowStd = $resStd->fetch();
    $CURR_CODE = implode(",", $rowStd);
    isset($_REQUEST['courseInfo']) ? $courseInfo = $_REQUEST['courseInfo'] : $courseInfo = '';
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb"; 
    $tbl_name = "curr_xml_import";
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
    $this_db->exec("set names tis620");
    $sql = "SELECT CURR_TH,CURR_EN FROM `curr_xml_import` WHERE CURR_ID = '$courseInfo' ";
    if($_COOKIE['level']==0){
        $sql = "SELECT CURR_TH,CURR_EN FROM `curr_xml_import` WHERE CURR_ID = '$CURR_CODE' ";
    }
    $res = $this_db->prepare($sql);
    $res->execute();
    $res->setFetchMode(PDO::FETCH_ASSOC);//$row_count = $res->rowCount();
    isset($_REQUEST['currid']) ? $currid = $_REQUEST['currid'] : $currid = '';
    foreach ($res as $row)
    {
        $txtCurr = $row['CURR_TH'];
    }
?>
<h3>
<?php
$exec = new cSQL();
$res = $exec->getSQL("SELECT CURR_TH FROM `curr_xml_import` WHERE CURR_ID = '".$courseInfo."'","tis620");
$row = implode(",", $res->fetch());
print $courseInfo." ".$row;
?>
</h3>
                  <!--   Kitchen Sink -->
                    <div class="panel panel-default">
                    <?php
                        //if($courseInfo!=''){
                        //    print "<div class='panel-heading'>โครงสร้างตารางเรียน : $txtCurr</div>";
                        //}else{
                            if($_COOKIE['level']==0){
                                print "<div class='panel-heading'>โครงสร้างตารางเรียน : $txtCurr</div>";
                            }else{
                                print "<div class='panel-heading'>$title</div>";
                            }
                        //}
                    ?>
                        <div class="panel-body">
                            <div class="table-responsive">
<?php
    isset($_REQUEST['viewall']) ? $viewall = $_REQUEST['viewall'] : $viewall = '';
    if($viewall=='true'){
        $db_name="stdcheckdb"; 
        $tbl_name = "curr_xml_import";
        $sql = "SELECT * FROM `curr_xml_import`";// $tbl_name";
        $res = $this_db->prepare($sql);
        $res->execute();
        $res->setFetchMode(PDO::FETCH_ASSOC);//$row_count = $res->rowCount();
        isset($_REQUEST['currid']) ? $currid = $_REQUEST['currid'] : $currid = '';
        foreach ($res as $row)
        {
        #$xml_output = $row['CURR_XML'];// $row['CURR_ID']
            #$xml_output .= $row['CURR_ID'];// $row['CURR_ID']
            $xml_arr_currID[] = $row['CURR_ID'];
            $xml_arr_currXML[] = $row['CURR_TH'];
        }
        if(count($xml_arr_currID)==0){
            print "ไม่พบข้อมูล";
        }else{
            print "<h4>พบข้อมูลหลักสูตรจำนวน " . count($xml_arr_currID) . " หลักสูตร</h4>";
            for($i=0;$i<=count($xml_arr_currID)-1;$i++){
                $x=$i;
                print "<a href='http://$_SERVER[HTTP_HOST]/project/admin/CourseDetail.php?courseInfo=$xml_arr_currID[$i]' target='_blank'>".$xml_arr_currID[$i]." ". $xml_arr_currXML[$i] ."</a><br/>";
            }
        }
    }
#########################################
    //$curr_code = $_GET['curr_code'];
    $curr_code = $courseInfo;
    $xml_string = "http://127.0.0.1/project/admin/include/XML.php?currCode=" . $curr_code;
    $xmldata = @file_get_contents($xml_string);
    $xml = simplexml_load_string($xmldata); //or die("กรุณากรอกหมายเลขหลักสูตร");
    $result = 'curr_code: ' . $curr_code;
    if(!$xml && $viewall!='true'){
        if($_COOKIE['level']==0){
///////////////////////////////////////////////////////////////////////////////////////
    $xml_string = "http://127.0.0.1/project/admin/include/XML.php?currCode=" . $CURR_CODE;
    $xmldata = @file_get_contents($xml_string);
    $xml = simplexml_load_string($xmldata); //or die("กรุณากรอกหมายเลขหลักสูตร");
    $courseArr = @array();
    $courseCount = $xml->Courses->Course;
    for($k=0;$k<count($courseCount);$k++){
        $courseNumber = $xml->Courses->Course[$k]->attributes();
        $courseNameEng = $xml->Courses->Course[$k]->NameEng;
        $courseArr[trim($courseNumber)]  = $courseNameEng ;
    }
    /////////////////////////////////////////////////////
        }else{
            echo "กรุณากรอกหมายเลขหลักสูตร";
        }
        $result = 'No post data yet.';
    }
///////////////////////////////////////////////////////////////////////////////////////
    $courseArr = @array();
    $courseCount = $xml->Courses->Course;
    for($k=0;$k<count($courseCount);$k++){
        $courseNumber = $xml->Courses->Course[$k]->attributes();
        $courseNameEng = $xml->Courses->Course[$k]->NameEng;
        $courseArr[trim($courseNumber)]  = $courseNameEng ;
    }
///////////////////////////////////////////////////////////////////////////////////////
    $termCount = $xml->Plans->Plan->YearSem;
    for($i=0;$i<count($termCount);$i++){
        //===================================================
        $attr = $xml->Plans->Plan->YearSem[$i]->attributes();
        echo "<b style='color:blue;font-size:20px'>ปีที่  " . $attr['year'];
        echo " ภาคเรียนที่ " . $attr['sem'] . "</b>";
        //===================================================
        $courseCount = $xml->Plans->Plan->YearSem[$i]->Course->count();
        echo "<table class=\"table table-striped table-bordered table-hover\">";
        echo "     <thead>";
        echo "         <tr>";
        echo "             <th width=10%>ลำดับ</th>";
        echo "             <th width=20%>รหัสวิชา</th>";
        echo "             <th width=55%>ชื่อวิชา</th>";
        echo "             <th width=15%>หน่วยกิต</th>";
        echo "         </tr>";
        echo "     </thead>";
        $cnt = null;
        for($j=0;$j<$courseCount;$j++){
            $cnt++;
            $courseID = $xml->Plans->Plan->YearSem[$i]->Course[$j]->Display;
            if (strpos($courseID, 'X') !== false) { // ถ้าเจอ string แสดงว่าเป็นวิชาเลือกภาษา/เลือกเสรี
                $courseID = "<font color=red>วิชาเลือก/เสรี</font>";
            }
            echo "<tr>";
            //echo "    <td>XXX</td>";
            echo "    <td>".$cnt."</td>";
            echo "    <td>" . $courseID . "</td>";
            echo "    <td>" . $courseArr[trim($courseID)] . "</td>";
            echo "    <td>" . $xml->Plans->Plan->YearSem[$i]->Course[$j]->Crd;
            echo              "(" . $xml->Plans->Plan->YearSem[$i]->Course[$j]->No_Hlec . "-";
            echo              $xml->Plans->Plan->YearSem[$i]->Course[$j]->No_Hlab . ")</td>";
            echo "</tr>";
        }
            echo "</table>";
    }
?>
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
