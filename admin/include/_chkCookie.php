<?php
	if(isset($_COOKIE['username'])) {  //vuln
		// if login
	}
	else {
		// echo "There is no cookie.";	
        header("location:../");
	}
?>