<?php
        print <<<HTML
<div class="panel panel-default">
    <div class="panel-heading">ข่าวสาร</div>
    <div class="panel-body">
HTML;
    //$f = file_get_contents("http://ced.kmutnb.ac.th/kmutnb/DataNews01.php");
    // echo htmlspecialchars($f);
    //$slideFTE = file_get_contents("http://fte.kmutnb.ac.th/slide/index.php",false);
    // http://kmutnb.ac.th/slide/index.php
    $url = @get_headers("http://fte.kmutnb.ac.th/slide/index.php", 1);
    $httpStatus = preg_match_all("/(\d{3}) OK/", $url[0], $regexStatus);
    $regexStatus = implode(",", $regexStatus[1]);
    if($regexStatus=='200' || $regexStatus=='302'){
        print "<iframe src='http://fte.kmutnb.ac.th/slide/index.php' style='border:none;width:100%;height:300px;margin-left:10%' marginheight='0'></iframe>";
    }else{
        print "ไม่สามารถเชื่อมต่อระบบเครือข่ายภายนอกได้";
    }
    print "<hr/>";
?>
<ul class="nav nav-tabs">
    <li class="active"><a href="#news" data-toggle="tab">ข่าวประชาสัมพันธ์</a></li>
    <li class=""><a href="#work" data-toggle="tab">ข่าวสมัครงาน</a></li>
    <li class=""><a href="#research" data-toggle="tab">ข่าวทุน/วิจัย</a></li>
    <li class=""><a href="#training" data-toggle="tab">ข่าวอบรม</a></li>
    <li class=""><a>|</a></li>
    <!---->
    <li class=""><a href="#vec1" data-toggle="tab">ข่าวอาชีวศึกษา (vec.go.th)</a></li>
    <li class=""><a href="#vec2" data-toggle="tab">ข่าวอาชีวศึกษา (dek-d.com)</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade active in" id="news">
<iframe src="include/news/fteRSS1.php" style="border:none;width:100%;height:640px;"></iframe>
    </div>
    <div class="tab-pane fade" id="work">
<iframe src="include/news/fteRSS2.php" style="border:none;width:100%;height:640px;"></iframe>
    </div>
    <div class="tab-pane fade" id="research">
<iframe src="include/news/fteRSS3.php" style="border:none;width:100%;height:640px;"></iframe>
    </div>
    <div class="tab-pane fade" id="training">
<iframe src="include/news/fteRSS4.php" style="border:none;width:100%;height:640px;"></iframe>
    </div>
    <!---->
    <div class="tab-pane fade" id="vec1">
<iframe src="include/news/vecRSS1.php" style="border:none;width:100%;height:640px;"></iframe>
    </div>
    <div class="tab-pane fade" id="vec2">
<iframe src="include/news/vecRSS2.php" style="border:none;width:100%;height:640px;"></iframe>
    </div>
</div>
<?php
        print <<<HTML
    </div>
</div>
HTML;
?>