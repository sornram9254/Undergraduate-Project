<style>
@media only screen and (max-width: 500px){
    .scrollToTop,.iosToggle {
        display:none;
    }
    
}
.scrollToTop{
	height:80px;
    width:80px;
	text-align:center; 
	font-weight: bold;
	color: #444;
    right:0;
    bottom:0;
    margin-bottom: 0;
    position:fixed;
    z-index:999;
	background: url('assets/img/arrow_up.png') no-repeat;
    background-size: 100%;
    opacity: 0.3;
    transition: opacity .5s ease-in-out;
    -moz-transition: opacity .5s ease-in-out;
    -webkit-transition: opacity .5s ease-in-out;
    }
.scrollToTop:hover{
    opacity: 1;
}
.scrollToTop, .scrollToTop:hover,.scrollToTop:focus{
	text-decoration:none;
    outline:none;
    outline:0;
}

</style>
    <script type="text/javascript">
        function getValue() {
            var checkedValue = document.querySelector('.cmn-toggle:checked').value;
            if(checkedValue=='on'){
                document.getElementById("debug_dialog").style.display = 'block';
                document.getElementById("iosToggle").style.opacity = '0';
                document.getElementById("iosToggle").style.transition = 'visibility 0s 1s, opacity 1s linear';
            }else{
                //document.getElementById("debug_dialog").style.display = 'none';
            }
            document.getElementById("iosToggle").style.visibility = 'hidden';
        }
    </script>
<?php
    session_start();
    require("include/ip.php");
    include("include/nocsrf.php");
    $result = 'No post data yet.';
?>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0;/*position:fixed;*/z-index:999;width:100%">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <!-- <span class="sr-only">Toggle navigation</span> -->
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><?php echo $_COOKIE['username']; ?></a> 
            </div>
<!-------->
<div style="color: white;
padding: 15px 50px 5px 50px;
float: left;
font-size: 16px;
font-weight: bold;
">
Department of Computer Education - Faculty of Technical Education
</div>
<!-------->
<div style="color: white;
padding: 15px 50px 5px 50px;
float: right;
font-size: 16px;">
<!--
    <span id="date_time"></span>
-->
<script type="text/javascript">window.onload = date_time('date_time');</script>&nbsp;
<a href="logout.php" class="btn btn-danger square-btn-adjust">Logout</a>
</div>
        </nav>
<a href="#" class="scrollToTop" title="Scroll To Top"></a>