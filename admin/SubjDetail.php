<?php
    require("include/_chkCookie.php");
    require("include/PAGE_LISTS.php");
    if($_COOKIE['level']==0){$pageID = 4;}else{$pageID = 5;}
    if($_COOKIE['level']==0){
        $title = $pageListIcon[$pageID+1][0];
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
                        url: "include/_CHECKIN_DELETE.php",
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
<!-- ##################################################################################  -->
                 <hr />
                 <form method="GET" action="#">
                    <!-- SEARCH -------->
                    <div class="form-group input-group" style="width:300px">
                        <input type="text" name="stdID" class="form-control" placeholder="กรุณากรอกข้อมูล">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                 </form>
                    <!-- SEARCH -------->
                  <!--   Kitchen Sink -->
                    <div class="panel panel-default">
                        <div class="panel-heading">ข้อมูลการเข้าเรียน</div>
                        <div class="panel-body">
                            <div class="table-responsive" style='overflow:hidden'>
                                <?php include("_.php"); ?>
                            </div>
                        </div>
                    </div>

<iframe src="manualTeachCheckin.php" style="border:none;width:100%;height:640px;"></iframe>
                  <!-- End  Kitchen Sink -->
                 <hr />
            </div>
        </div>
        <?php require("include/_FOOTER.php"); //print $sql; ?>
</body>
</html>
