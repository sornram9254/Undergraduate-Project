<?php
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
    $pageID = 10;
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
                           url: "_UID_DELETE.php",
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
            <td width=50  style=color:red;><b>ID</b></td>
            <td width=120 style=color:red;><b>UID</b></td>
            <td width=120 style=color:red;><b>CITIZENID</b></td>
            <td width=120 style=color:red;><b>TIME</b></td>
            <!--
            <td width=120 style=color:red;><b>IS_MIFARE</b></td>
            -->
            <td width=120 style=color:red;><b>ROOM</b></td>
            <td width=120 style=color:red;><b>DELETE</b></td>
        </tr>
    <?php
    $host="localhost";
    $user="root";
    $pass="";
    $db_name="stdcheckdb"; 
    $table = "card_uid_citizenid";
    $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
    $sql = "SELECT * FROM $table";
    foreach ($this_db->query($sql) as $row)
    {
        $time = (int)$row['TIME'];
        $TimeZoneNameFrom="UTC";
        $TimeZoneNameTo="Asia/Bangkok";
        $time = date_create(date('r', $time), new DateTimeZone($TimeZoneNameFrom))
        ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("Y-m-d H:i:s");
        print "<tr selectThis='" . $row['ID'] . "'>\n";
        print "    <td>" . $row['ID'] . "</td>\n";
        print "    <td>" . $row['UID'] . "</td>\n";
        print "    <td>" . $row['CITIZENID'] . "</td>\n";
        print "    <td>" . $time . "</td>\n";
        //print "    <td>" . $row['IS_MIFARE'] . "</td>\n";
        print "    <td>" . $row['ROOM'] . "</td>\n";
        print "    <td><button class='btn btn-danger delete'>DELETE</button></td>\n";
        //print "    <td><button class='btn btn-danger'><i class='fa fa-pencil'></i> Delete</button></td>\n";
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
