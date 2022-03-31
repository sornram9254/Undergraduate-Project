             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
     <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
         <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    

<!-- ##### FOR DEBUGGING ##### -->
<style>
    .debug_dialog{
        position:fixed;
        width:300px;
        height:auto;
        right:10px;
        top:80px;
        z-index:9999;
        /*opacity: 0.1 !important;*/
    }
    .debug_dialog:hover{
        /*opacity: 1 !important;*/
    }
</style>
<!-- ######################### -->
<?php if($_COOKIE['level']==1){ ?>
<!--  TOGGLE -->
<div id="iosToggle">
<div style="right:0;bottom:40px;margin-bottom:0;margin-right:80px;position:fixed;">Debug mode</div>
<div class="iosToggle" style="right:0;bottom:0;margin-bottom:0;margin-right:85px;position:fixed;">
    <link href="assets/css/toggle.css" rel="stylesheet" />
    <div class="switch">
      <input id="cmn-toggle-1" class="cmn-toggle cmn-toggle-round" type="checkbox" onclick="getValue()">
      <label for="cmn-toggle-1"></label>
      </div>
    <div class="switch">
      <input id="cmn-toggle-4" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" onclick="getValue()">
      <label for="cmn-toggle-4"></label>
    </div>
    <div class="switch">
      <input id="cmn-toggle-7" class="cmn-toggle cmn-toggle-yes-no" type="checkbox" onclick="getValue()">
      <label for="cmn-toggle-7" data-on="Yes" data-off="No"></label>
    </div>
</div>
</div>
<!--  TOGGLE -->
<div class="debug_dialog alert alert-danger fade in" id="debug_dialog" style="display:none;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?php echo "<b>FOR DEBUGING</b>"; ?>
    <br/>
<pre>
<?php echo $result; ?>
</pre>
<?php echo "<b>IP_ADDR_LIST</b> //in develop"; ?>
<pre>
<?php print $_SERVER['SERVER_ADDR']; ?> - SERVER
<?php print get_ip_address(); ?> - CLIENT</pre>
</div>
<?php }else{} ?>
<!-- ######################### -->