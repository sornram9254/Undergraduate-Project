<?php
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
    $pageID = 7;
    $title = $pageListIcon[$pageID][0];
    $pageName = basename($_SERVER['PHP_SELF']);
    
    require("include/_chkPermission.php");
    
    isset($_REQUEST['currCode']) ? $currCode = $_REQUEST['currCode'] : $currCode = '';
    isset($_REQUEST['stuYear']) ? $stuYear = $_REQUEST['stuYear'] : $stuYear = '';
    isset($_REQUEST['arrCurrNameFinal']) ? $arrCurrNameFinal = $_REQUEST['arrCurrNameFinal'] : $arrCurrNameFinal = '';
    isset($_REQUEST['arrCurrDescFinal']) ? $arrCurrDescFinal = $_REQUEST['arrCurrDescFinal'] : $arrCurrDescFinal = '';
    
    isset($_REQUEST['roomNumber']) ? $roomNumber = $_REQUEST['roomNumber'] : $roomNumber = '';
    isset($_REQUEST['viewall']) ? $viewall = $_REQUEST['viewall'] : $viewall = '';
    isset($_REQUEST['import']) ? $import = $_REQUEST['import'] : $import = '';
    isset($_REQUEST['stdID']) ? $stdID = $_REQUEST['stdID'] : $stdID = '';
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?=$title?></title>
    <?php require("include/_INCLUDE.php"); ?>
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
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
                 <form method="GET" action="#">
                    <!-- SEARCH -------->
                    <div class="form-group input-group" style="width:300px">
                        <input type="text" name="roomNumber" class="form-control" placeholder="กรุณากรอกข้อมูล">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                    <!-- SEARCH -------->
                 </form>
<form method='GET'>
<!--<form method='POST'>-->
    <input type='submit' class='btn btn-default btn-sm' value='ดูข้อมูลทั้งหมด'/>
    <input type='hidden' value='true' name='viewall'/>
</form>
                <br/>
<!--+++++++-->
<script>
    $(document).ready(function(){
        $("#showHide").click(function(){
            $("#tableShowHide").toggle(300);
        });
    });
</script>
<!--+++++++-->
                  <!--   Kitchen Sink -->
        <?php if($viewall=='true'){ ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" id='showHide'>รายชื่ออุปกรณ์ที่ผูกกับห้องเรียน
                        <div style='float:right;color:red' id='txtShowHide'></div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
<?php
    include("include/_klogic_room_number.php");
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb"; 
    $table = "data_curr_room";
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host;charset=utf8", $user, $pass);
    $this_db->exec("set names utf8");
    $sql = null;    
    if($viewall=='true'){
        $sql = "SELECT r.ID_CURR, r.COUSE_NO, r.COURSE_NAME, r.ROOM_NO, d.DEVICE_NAME, d.DEVICE_INSTANT_ID
        FROM ".$table." r
        INNER JOIN device d ON r.ROOM_NO = d.ROOM;";
?>
<script>
    $(document).ready(function(){
        $("#txtShowHide").text("[คลิกเพื่อซ่อน/แสดง]");
    });
</script>
<?php
    }
    else{
        $result = 'ERROR_STD_NOT_FOUND<br/>';
        $sql = "SELECT * FROM $table WHERE COUSE_NO='$roomNumber';";
    }
#########################################
    // arrCurrNameFinal
    // arrCurrDescFinal
    // print $currCode . " : ". $stuYear;
#########################################
    $res = $this_db->prepare($sql);
    $res->execute();
    $res->setFetchMode(PDO::FETCH_ASSOC);
    $row_count = $res->rowCount();
#########################################
    if($import!='true' && $viewall!='true'){
        $result = 'No post data yet.';
    }else if($row_count == 0 && ($roomNumber!='' && $import!='true')){
        $result = 'ERROR_STD_NOT_FOUND';
    }else if($viewall=='true'){
?>
        <table id='tableShowHide' class='table table-striped table-bordered table-hover' style='display:none;'>
            <thead>
                <tr>
                <th width=5%>ลำดับ</th>
                <th width=8%>รหัสวิชา</th>
                <th width=15%>ชื่อวิชา</th>
                <th width=8%>ห้องเรียน</th>
                <th width=10%>DEVICE_NAME</th>
                <th width=10%>DEVICE_INSTANT_ID</th>
                </tr>
            </thead>
<?php ///////////////////////////////////////////////////////////////////////////////////////
        foreach ($res as $row){
            print "<tr>\n";
            print "    <td>" . $row['ID_CURR'] . "</td>\n";
            print "    <td>" . $row['COUSE_NO'] . "</td>\n";
            print "    <td style='font-size:12px;'>" . $row['COURSE_NAME'] . "</td>\n";
            print "    <td>" . $row['ROOM_NO'] . "</td>\n";
            print "    <td style='font-size:12px;'>" . $row['DEVICE_NAME'] . "</td>\n";
            print "    <td style='font-size:12px;'>" . $row['DEVICE_INSTANT_ID'] . "</td>\n";
            print "</tr>\n";
        }
        echo "</table>";
    }
?>
                            </div>
                        </div>
                    </div>
        <?php }else{} ?>
                  <!-- End  Kitchen Sink -->
<!-- ##################################################################################  -->
<script type="text/javascript">
	$(document).ready(function()
	{            // table id=''  | class='delete'
		$('table#delTable td button.delete').click(function()
		{
			if (confirm("Are you sure you want to delete this row?"))
			{
                var id = $(this).parent().parent().attr('selectThis'); // ID
                var data = 'selectID=' + id ; // $_GET[]
                var parent = $(this).parent().parent();
                $.ajax(
                {
                    type: "POST",
                    url: "include/_DEVICE_DELETE.php",
                    data: data,
                    cache: false,
                    success: function(output)
                    {
                        if(output!=0){
                            $('div#editResult').text("Record has been successfully deleted.");
                            parent.fadeOut('slow', function() {$(this).remove();});
                        }else{
                            $('div#editResult').text("Oops, something went wrong. Please try again later.");
                        }
                    }
                });
            }
        });
    });
</script>
                  <!--   Kitchen Sink -->
                    <div class="panel panel-default">
                        <div class="panel-heading">รายชื่ออุปกรณ์</div>
                        <div class="panel-body">
                            <div class="table-responsive">
<?php
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb"; 
    $table = "device";
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host;charset=utf8", $user, $pass);
    $this_db->exec("set names utf8");
    $sql = null;
    if($viewall!='' || $roomNumber != ''){
        if($viewall=='' && $roomNumber != ''){
            $result = "SEARCH : ".$roomNumber;
            $sql = "SELECT DEVICE_ID, DEVICE_NAME, DEVICE_INSTANT_ID, ROOM
            FROM ".$table." WHERE ROOM='$roomNumber';";
        }
        if($viewall=='true'){
            $result = "VIEW_ALL";
            $sql = "SELECT DEVICE_ID, DEVICE_NAME, DEVICE_INSTANT_ID, ROOM
            FROM ".$table.";";
        }
#########################################
        $res = $this_db->prepare($sql);
        $res->execute();
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row_count = $res->rowCount();
#########################################
        if($row_count==0 && $roomNumber!=''){
            print "ไม่พบข้อมูล";
        }else{
?>
            <table id='delTable' class='table table-striped table-bordered table-hover'>
                <thead>
                    <tr>
                        <th width=5%>ลำดับ</th>
                        <th width=10%>DEVICE_NAME</th>
                        <th width=10%>DEVICE_INSTANT_ID</th>
                        <th width=3%>ห้องเรียน</th>
                        <?php
                        $level = $_COOKIE['level'];
                        if($level==1 || $level==2){
                            echo "<th width=2%>DELETE</th>";
                        }else{}
                        ?>
                    </tr>
                </thead>
<?php ///////////////////////////////////////////////////////////////////////////////////////
            foreach ($res as $row){
                print "<tr selectThis='" . $row['DEVICE_ID'] . "'>\n";
                print "    <td>" . $row['DEVICE_ID'] . "</td>\n";
                print "    <td style='font-size:12px;'>" . $row['DEVICE_NAME'] . "</td>\n";
                print "    <td style='font-size:12px;'>" . $row['DEVICE_INSTANT_ID'] . "</td>\n";
                print "    <td>" . $row['ROOM'] . "</td>\n";
                if($level==1 || $level==2){
                print "    <td>
        <button class='btn btn-danger delete'><i class='fa fa-pencil'></i> Delete</button>
                           </td>\n";
                }else{}
                print "</tr>\n";
            }
            echo "</table>";
            /////
            print "<div id='editResult'></div>";  // DEBUG RESULT
            /////
        }
    }
    if($viewall=='' && $roomNumber == ''){
        $result = "กรุณากรอกข้อมูล";
        print "กรุณากรอกข้อมูล";
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