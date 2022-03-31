<?php
    error_reporting(E_ERROR | E_PARSE);     //  รอแก้ 
    header('Content-Type: text/html; charset=utf-8');
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
    $pageID = 2;
    $title = $pageListIcon[$pageID][0];
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
                 <form method="GET" action="CourseFull.php">
                 <!--<form method="POST" action="CourseFull.php">-->
                    <!-- SEARCH -------->
                    <div class="form-group input-group" style="width:300px">
                        <input type="text" name="courseInfo" class="form-control" placeholder="กรุณากรอกข้อมูล">
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
<?php
$level = $_COOKIE['level'];
if($level==1 || $level==2){ ?>
<td>
<!--<form method='POST'>-->
<form method='GET'>
    <input type='submit' class='btn btn-default btn-sm' value='นำเข้าข้อมูล'/>
    <input type='hidden' value='true' name='import'/>
</form>
                        </td>
<?php }else {} ?>
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

                  <!--   Kitchen Sink -->
                    <div class="panel panel-default">
                        <div class="panel-heading">รายวิชาที่มีในหลักสูตร</div>
                        <div class="panel-body">
                            <div class="table-responsive" style='overflow:hidden'>
<?php
    require("include/SQL.php");
    $execStd = new cSQL();
    $resStd = $execStd->getSQL("SELECT CURR_CODE FROM `data_std_detail_tb` WHERE STU_CODE=$_COOKIE[username];");
    $rowStd = $resStd->fetch();
    $CURR_CODE = implode(",", $rowStd);
    isset($_REQUEST['courseInfo']) ? $courseInfo = $_REQUEST['courseInfo'] : $courseInfo = '';
    isset($_REQUEST['import']) ? $import = $_REQUEST['import'] : $import = '';
    isset($_REQUEST['viewall']) ? $viewall = $_REQUEST['viewall'] : $viewall = '';
    //////////////////////////////
    isset($_REQUEST['facCode']) ? $facCode = $_REQUEST['facCode'] : $facCode = '0';
    isset($_REQUEST['deptCode']) ? $deptCode = $_REQUEST['deptCode'] : $deptCode = '0';
    isset($_REQUEST['currCode']) ? $currCode = $_REQUEST['currCode'] : $currCode = '0';
    $curr_code = $courseInfo;
    //$xml_string = "http://klogic.kmutnb.ac.th:8080/kris/curri/showXML.jsp?currCode=" . $curr_code;
    $xml_string = "http://127.0.0.1/project/admin/include/XML.php?currCode=" . $curr_code;
    $xmldata = @file_get_contents($xml_string);
    $xml = simplexml_load_string($xmldata); //or die("กรุณากรอกหมายเลขหลักสูตร");
    $result = 'curr_code: ' . $curr_code;
    if($import=='true'){
        // IMPORT
        if($level==1||$level==2){
?>
<form method="POST">
<input type='hidden' value='true' name='import'/>
<select name="facCode" onChange="form.submit()" class="form-control" style="width:30%;min-width:200px;">
<option value="0" <?php  if($facCode=='0') {echo 'selected';} ?>>
- - - เลือกคณะ - - -</option>
<option value="02" <?php if($facCode=='02'){echo 'selected';} ?>>
คณะครุศาสตร์อุตสาหกรรม</option>
</select>
<?php require("include/curr_faculty_dept.php"); ?>


</form>
<?php
        }else{}
    }
    else if($viewall=='true'){
#########################################
        $host="localhost";
        $user="root";
        $pass="";
        $db_name="stdcheckdb"; 
        $tbl_name = "curr_xml_import";
        $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
        $this_db->exec("set names tis620");
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
                print "<a href='http://$_SERVER[HTTP_HOST]/project/admin/CourseFull.php?courseInfo=$xml_arr_currID[$i]' target='_blank'>".$xml_arr_currID[$i]." ". $xml_arr_currXML[$i] ."</a><br/>";
            }
        }
#########################################
    }
    else if(!$xml && $import!='true'){
        if($_COOKIE['level']==0){
            $xml_string = "http://127.0.0.1/project/admin/include/XML.php?currCode=" . $CURR_CODE;
            $xmldata = @file_get_contents($xml_string);
            $xml = simplexml_load_string($xmldata); //or die("กรุณากรอกหมายเลขหลักสูตร");
            echo '<table>';
            echo '<tr>';
            echo '  <td width=150><h4>หลักสูตร[EN]:</h4></td>';
            echo '  <td><h4>'.$xml->Info->NameEng.'</h4></td>';
            echo '</tr>';
            echo '<tr>';
            echo '  <td><h4>หลักสูตร[TH]:</h4></td>';
            echo '  <td><h4>'.$xml->Info->NameThai.'</h4></td>';
            echo '</tr>';
            echo '<tr>';
            echo '  <td><h4>คณะ:</h4></td>';
            echo '  <td><h4>'.$xml->Info->FacText.'</h4></td>';
            echo '</tr>';
            echo '<tr>';
            echo '  <td><h4>ภาควิชา:</h4></td>';
            echo '  <td><h4>'.$xml->Info->DeptText.'</h4></td>';
            echo '</tr>';
            echo '<tr>';
            echo '  <td><h4>สาขา:</h4></td>';
            echo '  <td><h4>'.$xml->Info->DivText.'</h4></td>';
            echo '</tr>';
            echo '<tr>';
            echo '  <td><h4>จำนวนหน่วยกิต:</h4></td>';
            echo '  <td><h4>'.$xml->Nodes->Node[0]->Crd.'</h4></td>';
            echo '</tr>';
            echo '</table>';
            echo "<table class='table table-striped table-bordered table-hover' id='dataTables-example'>";
            echo "    <thead>";
            echo "        <tr>";
            echo "            <th width=10%>ลำดับ</th>";
            echo "            <th width=10%>รหัสวิชา</th>";
            echo "            <th width=20%>ชื่อวิชาภาษาไทย</th>";
            echo "            <th width=20%>ชื่อวิชาภาษาอังกฤษ</th>";
            echo "            <th width=20%>คำอธิบายวิชาภาษาไทย</th>";
            echo "            <th width=20%>คำอธิบายวิชาภาษาอังกฤษ</th>";
            echo "        </tr>";
            echo "    </thead>";
            $courseCount = $xml->Courses->Course;
            $count = null;
            for($i=0;$i<count($courseCount);$i++){
                //===================================================
                    $count++;
                    echo "<tr>";
                    echo "    <td>" . $count . "</td>";
                    echo "    <td>" . $xml->Courses->Course[$i]->attributes() . "</td>";
                    echo "    <td>" . $xml->Courses->Course[$i]->NameThai . "</td>";
                    echo "    <td>" . $xml->Courses->Course[$i]->NameEng . "</td>";
                    echo "    <td>" . $xml->Courses->Course[$i]->DescThai . "</td>";
                    echo "    <td>" . $xml->Courses->Course[$i]->DescEng . "</td>";
                    //echo "    <td>" . "<font style='color:red'>&#9888; ไม่บอก ปล่อยให้งง &#9888;</font>" . "</td>";
                    //echo "    <td>" . "<font style='color:red'>&#9888; ไม่บอก ปล่อยให้งง &#9888;</font>" . "</td>";
                    echo "</tr>";
            }
            echo "</table>";
        }else{
            echo "กรุณากรอกหมายเลขหลักสูตร";
        }
        $result = 'No post data yet.';
    }
///////////////////////////////////////////////////////////////////////////////////////
    else{
        echo '<table>';
        echo '<tr>';
        echo '  <td width=150><h4>หลักสูตร[EN]:</h4></td>';
        echo '  <td><h4>'.$xml->Info->NameEng.'</h4></td>';
        echo '</tr>';
        echo '<tr>';
        echo '  <td><h4>หลักสูตร[TH]:</h4></td>';
        echo '  <td><h4>'.$xml->Info->NameThai.'</h4></td>';
        echo '</tr>';
        echo '<tr>';
        echo '  <td><h4>คณะ:</h4></td>';
        echo '  <td><h4>'.$xml->Info->FacText.'</h4></td>';
        echo '</tr>';
        echo '<tr>';
        echo '  <td><h4>ภาควิชา:</h4></td>';
        echo '  <td><h4>'.$xml->Info->DeptText.'</h4></td>';
        echo '</tr>';
        echo '<tr>';
        echo '  <td><h4>สาขา:</h4></td>';
        echo '  <td><h4>'.$xml->Info->DivText.'</h4></td>';
        echo '</tr>';
        echo '<tr>';
        echo '  <td><h4>จำนวนหน่วยกิต:</h4></td>';
        echo '  <td><h4>'.$xml->Nodes->Node[0]->Crd.'</h4></td>';
        echo '</tr>';
        echo '</table>';

        echo "<table class='table table-striped table-bordered table-hover' id='dataTables-example'>";
        echo "    <thead>";
        echo "        <tr>";
        echo "            <th width=10%>ลำดับ</th>";
        echo "            <th width=10%>รหัสวิชา</th>";
        echo "            <th width=20%>ชื่อวิชาภาษาไทย</th>";
        echo "            <th width=20%>ชื่อวิชาภาษาอังกฤษ</th>";
        echo "            <th width=20%>คำอธิบายวิชาภาษาไทย</th>";
        echo "            <th width=20%>คำอธิบายวิชาภาษาอังกฤษ</th>";
        echo "        </tr>";
        echo "    </thead>";
    ///////////////////////////////////////////////////////////////////////////////////////
        $courseCount = $xml->Courses->Course;
        $count = null;
        for($i=0;$i<count($courseCount);$i++){
            //===================================================
                $count++;
                echo "<tr>";
                echo "    <td>" . $count . "</td>";
                echo "    <td>" . $xml->Courses->Course[$i]->attributes() . "</td>";
                echo "    <td>" . $xml->Courses->Course[$i]->NameThai . "</td>";
                echo "    <td>" . $xml->Courses->Course[$i]->NameEng . "</td>";
                echo "    <td>" . $xml->Courses->Course[$i]->DescThai . "</td>";
                echo "    <td>" . $xml->Courses->Course[$i]->DescEng . "</td>";
                //echo "    <td>" . "<font style='color:red'>&#9888; ไม่บอก ปล่อยให้งง &#9888;</font>" . "</td>";
                //echo "    <td>" . "<font style='color:red'>&#9888; ไม่บอก ปล่อยให้งง &#9888;</font>" . "</td>";
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
