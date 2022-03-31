 <!-- /. NAV TOP  -->
            <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <!-- <img src="assets/img/find_user.png" class="user-image img-responsive" width="50%"/> -->
                        <?php
                            //$url = @get_headers("http://202.28.17.14/stdimages.php?stdcode=$_COOKIE[username]", 1);
                            //$httpStatus = preg_match_all("/(\d{3}) OK/", $url[0], $regexStatus);
                            //$regexStatus = implode(",", $regexStatus[1]);
                            if($_COOKIE['level']!=0){ #teacher|admin|department
                                    print "<img src='assets/img/admin.png' class='user-image img-responsive' width='50%'/>";
                            }else{ #student
                                $host="localhost";
                                $user="root";
                                $pass="";
                                $db_name="stdcheckdb"; 
                                $table = "stdlogin";
                                $this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
                                $stmt = $this_db->prepare("SELECT * FROM $table WHERE STDID='".$_COOKIE['username']."'"); 
                                $stmt->execute(); 
                                $row = $stmt->fetch();
                                if ($stmt->rowCount() > 0){
                                    print "<img src='".$row['PICTURE']."' class='user-image img-responsive' width='50%'/>";
                                }
                            }
                        ?>
					</li>
                    <!-- CHECK USER_LEVEL -->
                    <?php
                        // 0 = STD
                        // 1 = ROOT
                        // 2 = DEPARTMENT
                        // 3 = TEACHER
$pageListCount = count($pageListIcon);
if($_COOKIE['level']==3){
    $pageListCount = $pageListCount-1;
}
if ($_COOKIE['level']==1 || $_COOKIE['level']==2 || $_COOKIE['level']==3){
    for($count=1;$count<=$pageListCount;$count++){
        echo "<li>";
        echo "<a href='";
        echo $pageListIcon[$count][2];
        echo "' ";
            if($pageID==$count) echo "class=\"active-menu\""; echo ">";
        echo "<i class='fa fa-";
        echo $pageListIcon[$count][1];
        echo " fa-3x'></i>";
        echo $pageListIcon[$count][0];
        echo "</a>";
        echo "</li>";
    }
}else if($_COOKIE['level']==0){
    for($countSTD=1;$countSTD<=count($pageListIconSTD);$countSTD++){
        echo "<li>";
        echo "<a href='";
        echo $pageListIconSTD[$countSTD][2];
        echo "' ";
            if($pageID==$countSTD) echo "class=\"active-menu\""; echo ">";
        echo "<i class='fa fa-";
        echo $pageListIconSTD[$countSTD][1];
        echo " fa-3x'></i>";
        echo $pageListIconSTD[$countSTD][0];
        echo "</a>";
        echo "</li>";
    }
}
?>
                    <!-- ---------------- -->
                </ul>
            </div>
        </nav>