<?php
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
    $pageID = 4;
    $title = $pageListIcon[$pageID][0];
    $pageName = basename($_SERVER['PHP_SELF']);
    
    require("include/_chkPermission.php");
    isset($_REQUEST['stdInfo']) ? $stdInfo = $_REQUEST['stdInfo'] : $stdInfo = '';
    isset($_REQUEST['viewall']) ? $viewall = $_REQUEST['viewall'] : $viewall = '';
    isset($_REQUEST['cReport']) ? $cReport = $_REQUEST['cReport'] : $cReport = '';
    
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb"; 
    $table = "data_std_detail_tb";
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
    $this_db->exec("set names utf8");
    require_once("include/SQL.php");
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?=$title?></title>
    <?php require("include/_INCLUDE.php"); ?>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {            // table id=''  | class='delete'
            $('table.delTable td button.delete').click(function()
            {
                if (confirm("Are you sure you want to delete this row?"))
                {
                    var id = $(this).parent().parent().attr('selectThis'); // ID
                    var data = 'selectID=' + id ; // $_GET[]
                    var parent = $(this).parent().parent();
                    $.ajax(
                    {
                        type: "POST",
                        url: "include/_STD_DELETE.php",
                        data: data,
                        cache: false,
                        success: function(output)
                        {
                            if(output!=0){
                                $('div#result').text("Record has been successfully deleted.");
                                parent.fadeOut('slow', function() {$(this).remove();});
                            }else{
                                $('div#result').text("Oops, something went wrong. Please try again later.");
                            }
                        }
                    });	
                }
            });
        });
        function open_win() {
            window.open('report/StudentDetail_report.php', '_blank','toolbar=0,location=no,menubar=0,height=400');
        }
    </script>
</head>
<body>
    <?php require("include/_HEAD.php"); ?>
    <?php require("include/_SidebarMenu.php"); ?>
    
    <script>
        $(document).ready(function(){
            $("#txtShowHide_teacher").text("[คลิกเพื่อซ่อน/แสดง]");
            $("#showHide_teacher").click(function(){
                $(".tableShowHide_teacher").toggle(300);
            });
            ////////
            $("#txtShowHide_student").text("[คลิกเพื่อซ่อน/แสดง]");
            $("#showHide_student").click(function(){
                $(".tableShowHide_student").toggle(300);
            });
        });
    </script>
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
                <form method="GET" action="StudentDetail.php">
                <!-- SEARCH -------->
                <div class="form-group input-group" style="width:300px">
                    <input type="text" name="stdInfo" class="form-control" placeholder="กรุณากรอกหมายเลขประจำตัวนักศึกษา">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
                <!-- SEARCH -------->
                </form>
                
                <!--<form method='get' style="float:left;">-->
                <form method='get' style="float:left;">
                    <input type='submit' class='btn btn-default btn-sm' value='ดูข้อมูลทั้งหมด'/>
                    <input type='hidden' value='true' name='viewall'/>
                </form>
                <!--<form method='get'>
                    <input type='submit' class='btn btn-default btn-sm btnReport' value='สร้างรายงาน'/>
                    <input type='hidden' value='true' name='cReport'/>
                </form>>-->
                <button class='btn btn-default btn-sm btnReport' onclick="open_win(1)">สร้างรายงาน</button>
                <br/><br/>
                <div id="divReport"></div>
                  <!--   Kitchen Sink -->
                    <div class="panel panel-default">
                        <div class="panel-heading">รายชื่อสมาชิก</div>
                        <div class="panel-body">
                            <div class="table-responsive" style='overflow:hidden'>
               <!-- ################################ -->
<?php
    #$std_id = "5402041420259";
    #$std_id = $stdInfo;
    //$json_string = "http://202.28.17.14/api4reg/api/index.php/GetStudentInfo/" . $std_id;
    //$jsondata = @file_get_contents($json_string);
    //$obj = json_decode($jsondata, true);
    ////////////////////////////////////// VIEW_ALL_RECORD
    if($viewall=='true'){
        $result = 'VIEW_ALL_RECORD';
        $sql = "SELECT * FROM $table";
    }
    else{
        $result = 'ERROR_STD_NOT_FOUND<br/>';
        $result .= 'std_id: ' . $stdInfo;
        $sql = "SELECT * FROM data_std_detail_tb WHERE STU_CODE = '$stdInfo';";
    }
    $res = $this_db->prepare($sql);
    $res->execute();
    $res->setFetchMode(PDO::FETCH_ASSOC);
    $row_count = $res->rowCount();

    if($row_count==0 && ($stdInfo=='' && $viewall!='true')){
        $result = 'No post data yet.';
        print "กรุณากรอบหมายเลขประจำตัวนักศึกษา หรือชื่อผู้ใช้งาน";
        //print var_dump($row_count);
    }else if($row_count == 0 && $stdInfo!=''){
        $exec = new cSQL();
        $res = $exec->getSQL("SELECT * FROM `login` WHERE USER = '".$stdInfo."';");
        $rCount = $res->rowCount();
        if($rCount>0){
?>
<div class="panel-heading" id='showHide_teacher' style='background:#f5f5f5;border:1px solid #ddd !important;margin-top:10px;'>รายชื่ออาจารย์
<div style='float:right;color:red;' id='txtShowHide_teacher'></div>
</div>
<div class='tableShowHide_teacher'>
<table class="table table-striped table-bordered table-hover delTable" id="dataTables-example">
    <thead>
        <tr>
            <td width=5%  style=color:red;><b>ลำดับ</b></td>
            <td width=5% style=color:red;><b>ชื่อผู้ใช้</b></td>
            <td width=10% style=color:red;><b>อีเมลล์</b></td>
            <td width=10% style=color:red;><b>Delete</b></td>
        </tr>
    </thead>
<?php
            foreach ($res as $row)
            {
                print "<tr selectThis='" . $row['ID'] . "'>\n";
                print "    <td>" . $row['ID'] . "</td>";
                print "    <td>" . $row['USER'] . "</td>";
                if($row['EMAIL']==""){
                    print '<td>-</td>';
                }else{
                    print "    <td>" . $row['EMAIL'] . "</td>";
                }
                print "    <td>
                            <button class='btn btn-danger delete'><i class='fa fa-pencil'></i> Delete</button>
                           </td>\n";
                print "</tr>\n";
            }
            echo '</table></div>';
        }else{
            print "ไม่พบข้อมูล";
        }
    }else{
?>
<div class="panel-heading" id='showHide_student' style='background:#f5f5f5;border:1px solid #ddd !important;'>รายชื่อนักศึกษา
<div style='float:right;color:red;' id='txtShowHide_student'></div>
</div>
<div class='tableShowHide_student' <?php if($viewall!="" && $stdInfo==""){print "style='display:none;'";} ?>>
<table class="table table-striped table-bordered table-hover delTable" id="dataTables-example">
    <thead>
        <tr>
            <td width=5%  style=color:red;><b>ลำดับ</b></td>
            <td width=5% style=color:red;><b>รหัสนักศึกษา</b></td>
            <td width=10% style=color:red;><b>ชื่อ</b></td>
            <td width=10% style=color:red;><b>นามสกุล</b></td>
            <td width=8% style=color:red;><b>รหัสหลักสูตร</b></td>
            <td width=5% style=color:red;><b>สาขา</b></td>
            <td width=8% style=color:red;><b>ระดับการศึกษา</b></td>
            <?php if($_COOKIE['level']==1||$_COOKIE['level']==2){ ?>
                <td width=10% style=color:red;><b>Delete</b></td>
            <?php } ?>
        </tr>
    </thead>
<?php
        foreach ($res as $row)
        {
            print "<tr selectThis='" . $row['ID_STD_DETAIL'] . "'>\n";
            print "    <td>" . $row['ID_STD_DETAIL'] . "</td>";
            print "    <td>
                    <a href='StudentDetail.php?stdInfo=" . $row['STU_CODE'] . "' target='_blank'>
            ". $row['STU_CODE'] ."</a></td>";
            print "    <td>" . $row['STU_FIRST_NAME_THAI'] . "</td>";
            print "    <td>" . $row['STU_LAST_NAME_THAI'] . "</td>";
            print "    <td>
                    <a href='CourseFull.php?courseInfo=" . $row['CURR_CODE'] . "' target='_blank'>
            ". $row['CURR_CODE'] ."</a></td>";
            print "    <td>" . $row['DIV_SHRT_NAME'] . "</td>";
            print "    <td>" . $row['LEVEL_DESC'] . "</td>";
            if($_COOKIE['level']==1||$_COOKIE['level']==2){
                print "    <td>
                            <button class='btn btn-danger delete'><i class='fa fa-pencil'></i> Delete</button>
                           </td>\n";
                print "</tr>\n";
            }
        }
        echo '</table></div>';
        print "<div id='editResult'></div>";  // DEBUG RESULT
    }
?>
<!-- ################################ -->
<!-- ################################ -->
<!-- ################################ -->
<?php
    $table = "login";
    $result = new cSQL();
    $res = $result->getSQL("SELECT * FROM $table");
if(($_COOKIE['level']==1||$_COOKIE['level']==2) && $stdInfo==''){
if($viewall!=""){
?>
<div class="panel-heading" id='showHide_teacher' style='background:#f5f5f5;border:1px solid #ddd !important;margin-top:10px;'>รายชื่ออาจารย์
<div style='float:right;color:red;' id='txtShowHide_teacher'></div>
</div>
<div class='tableShowHide_teacher' style='display:none;'>
<table class="table table-striped table-bordered table-hover delTable" id="dataTables-example">
    <thead>
        <tr>
            <td width=5%  style=color:red;><b>ลำดับ</b></td>
            <td width=5% style=color:red;><b>ชื่อผู้ใช้</b></td>
            <td width=10% style=color:red;><b>อีเมลล์</b></td>
            <td width=10% style=color:red;><b>Delete</b></td>
        </tr>
    </thead>
<?php
        foreach ($res as $row)
        {
            print "<tr selectThis='" . $row['ID'] . "'>\n";
            print "    <td>" . $row['ID'] . "</td>";
            print "    <td>" . $row['USER'] . "</td>";
            if($row['EMAIL']==""){
                print '<td>-</td>';
            }else{
                print "    <td>" . $row['EMAIL'] . "</td>";
            }
            print "    <td>
                        <button class='btn btn-danger delete'><i class='fa fa-pencil'></i> Delete</button>
                       </td>\n";
            print "</tr>\n";
        }
        echo '</table></div>';
        print "<div id='editResult'></div>";  // DEBUG RESULT
    }
}
?>
<!-- ################################ -->
<!-- ################################ -->
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
