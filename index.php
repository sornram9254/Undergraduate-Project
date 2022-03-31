<?php
    require("check.php");
?>

<!DOCTYPE html>
<html>
	
<head>
	<title>Login</title>
        <?php //require("admin/include/_INCLUDE.php"); ?>
		<meta charset="utf-8">
		<link href="assets/css/style.css" rel='stylesheet' type='text/css' />
        
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        
        
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<link href='assets/css/css.css' rel='stylesheet' type='text/css'>
        
        <script type="text/javascript"> 
            $(function(){
                $("#menu1Link").click(function(){
                    $("#stdID").val("");
                    $("#stdCitizen").val("");
                });
                $("#menu2Link").click(function(){
                    $("#user").val("");
                    $("#pass").val("");
                });
            });
            
            
        </script>
</head>
<body>
    <div class="bg"></div>
				<div class="login-form">
					<div class="head">
						<img src="assets/images/logo.jpg" alt=""/>
						
					</div>
				<form method="POST" name="csrf_form" action="#">
                    <!-- ##################### -->
                    <ul class="nav nav-pills">
                      <li class="active">
                          <a data-toggle="tab" id="menu1Link" href="#menu1">สำหรับอาจารย์/เจ้าหน้าที่</a></li>
                      <li>
                          <a data-toggle="tab" id="menu2Link" href="#menu2">สำหรับนักศึกษา</a></li>
                    </ul>

                    <div class="tab-content">
                      <div id="menu1" class="tab-pane fade in active"> <!-- TEACHER/ADMIN -->
                        <li>
                            <input type="text" placeholder="USERNAME" name="user" id="user" autocomplete="off" autofocus="true"><a class=" icon user"></a>
                        </li>
                        <li>
                            <input type="password" placeholder="PASSWORD" name="pass" id="pass" autocomplete="off"><a class=" icon lock"></a>
                        </li>
                      </div>
                      <div id="menu2" class="tab-pane fade"> <!-- STUDENT -->
                        <li>
                            <input type="text" placeholder="Student ID" name="stdID" id="stdID" autocomplete="off"><a class=" icon user"></a>
                        </li>
                        <li>
                            <input type="text" placeholder="Citizen ID / Password" name="stdCitizen" id="stdCitizen" autocomplete="off"><a class=" icon lock"></a>
                        </li>
                      </div>
                    </div>
                    
                    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
					<div class="p-container">
                                <input type="submit" value="SIGN IN" >
                                <div class="clear"></div>
					</div>
                    <!-- ##### FOR DEBUGGING ##### -->
                    <div class="alert alert-danger fade in"
                        style="position:fixed;
                               width:auto;
                               max-width:400px;
                               height:auto;
                               right:10px;
                               top:10px;
                               ">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?php echo "<b>" . $result . "</b>"; ?>
                        <br/>
                        <pre><?php print_r($_POST); ?></pre>
                        
                        <?php if($result=='Password is valid!'){
                            echo "<b>PASS_FRM_DB</b>";
                            echo "<pre>";
                            print_r($hash);
                            echo "</pre>";
                        }?>
                    </div>
                    <!-- ######################### -->
				</form>
			</div>
</body>
</html>