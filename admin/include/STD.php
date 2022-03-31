<?php
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
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
    <script type="text/javascript" src="assets/js/jquery-1.10.2.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('table#delTable td button.delete').click(function()
            {
                if (confirm("Are you sure you want to delete this row?"))
                {
                    var id = $(this).parent().parent().attr('selectThis');
                    var data = 'selectID=' + id ;
                    var parent = $(this).parent().parent();
                    $.ajax(
                    {
                           type: "POST",
                           url: "_STD_DELETE.php",
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
                    <!-- SEARCH -------->
                 </form>
                  <!--   Kitchen Sink -->
                    <div class="panel panel-default">
                        <div class="panel-heading">สวัสดีนะเราเอง .___.</div>
                        <div class="panel-body">
                            <div class="table-responsive">
    <div id="result"></div>
    <table border=1 id="delTable" class='table table-striped table-bordered table-hover'>
        <tr>
            <td width=120 style=color:red;><b>DELETE</b></td>
            <td width=50  style=color:red;><b>ID</b></td>
            <td width=120 style=color:red;><b>STU_CODE</b></td>
            <!--
            <td width=120 style=color:red;><b>UID</b></td>
            <td width=120 style=color:red;><b>ID_CARD</b></td>
            -->
            <td width=120 style=color:red;><b>STU_FIRST_NAME_THAI</b></td>
            <td width=120 style=color:red;><b>STU_LAST_NAME_THAI</b></td>
            <!--
            <td width=120 style=color:red;><b>STU_FIRST_NAME_ENG</b></td>
            <td width=120 style=color:red;><b>STU_LAST_NAME_ENG</b></td>
            <td width=120 style=color:red;><b>SEX</b></td>
            -->
            <td width=120 style=color:red;><b>CURR_CODE</b></td>
            <!--
            <td width=120 style=color:red;><b>CURR_NAME_THAI</b></td>
            <td width=120 style=color:red;><b>FAC_NAME_THAI</b></td>
            <td width=120 style=color:red;><b>DEPT_NAME_THAI</b></td>
            <td width=120 style=color:red;><b>DIV_NAME_THAI</b></td>
            -->
            <td width=120 style=color:red;><b>DIV_SHRT_NAME</b></td>
            <!--
            <td width=120 style=color:red;><b>ROUND</b></td>
            <td width=120 style=color:red;><b>STU_ST_DESC</b></td>
            -->
            <td width=120 style=color:red;><b>LEVEL_DESC</b></td>
        </tr>
    <?php
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb"; 
    $table = "data_std_detail_tb";
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
    $this_db->exec("set names utf8");
    $sql = "SELECT * FROM $table";
    foreach ($this_db->query($sql) as $row)
    {
        print "<tr selectThis='" . $row['ID_STD_DETAIL'] . "'>\n";
        print "    <td><button class='btn btn-danger delete'>DELETE</button></td>\n";
        print "    <td>" . $row['ID_STD_DETAIL'] . "</td>";
        print "    <td>
<a href='StudentDetail.php?stdInfo=" . $row['STU_CODE'] . "' target='_blank'>
        ". $row['STU_CODE'] ."</a></td>";
//        print "    <td>" . $row['UID'] . "</td>";
//        print "    <td>" . $row['ID_CARD'] . "</td>";
        print "    <td>" . $row['STU_FIRST_NAME_THAI'] . "</td>";
        print "    <td>" . $row['STU_LAST_NAME_THAI'] . "</td>";
//        print "    <td>" . $row['STU_FIRST_NAME_ENG'] . "</td>";
//        print "    <td>" . $row['STU_LAST_NAME_ENG'] . "</td>";
//        print "    <td>" . $row['SEX'] . "</td>";
        print "    <td>
<a href='CourseFull.php?courseInfo=" . $row['CURR_CODE'] . "' target='_blank'>
        ". $row['CURR_CODE'] ."</a></td>";
//        print "    <td>" . $row['CURR_NAME_THAI'] . "</td>";
//        print "    <td>" . $row['FAC_NAME_THAI'] . "</td>";
//        print "    <td>" . $row['DEPT_NAME_THAI'] . "</td>";
//        print "    <td>" . $row['DIV_NAME_THAI'] . "</td>";
        print "    <td>" . $row['DIV_SHRT_NAME'] . "</td>";
//        print "    <td>" . $row['ROUND'] . "</td>";
//        print "    <td>" . $row['STU_ST_DESC'] . "</td>";
        print "    <td>" . $row['LEVEL_DESC'] . "</td>";
        print "</tr>\n";
    }
    ?>
</table>
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
