<head>
<meta charset="utf-8" />
<link href="../../assets/css/bootstrap.css" rel="stylesheet" />
</head>
    <link href="../../assets/css/bootstrap.css" rel="stylesheet" />
    <link href="../../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <script src="../../assets/js/jquery-1.10.2.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>
    <script src="../../assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="../../assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
         <!-- CUSTOM SCRIPTS -->
    <script src="../../assets/js/custom.js"></script>
<!-------------------------------------------->
<?php
    header('Content-type: text/html; charset=utf8');
    class fteFeed{
        public function vec(){
            $httpUrl = 'http://www.vec.go.th/Default.aspx?tabid=97&cid=0';
            $url = @file_get_contents($httpUrl);
            $header = @get_headers("http://fte.kmutnb.ac.th/slide/index.php", 1);
            $httpStatus = preg_match_all("/(\d{3}) OK/", $header[0], $regexStatus);
            $regexStatus = implode(",", $regexStatus[1]);
            if($regexStatus=='200' || $regexStatus=='302'){
                if (preg_match_all("/<a class=\"veclist2\" href='(.+)' title=\".+\">(.+)<\/a><\/div>/", $url,$matches)) {
                    $link   = $matches[1];
                    $title  = $matches[2];
                }
            }else{
                print "<br/>ไม่สามารถเชื่อมต่อระบบเครือข่ายภายนอกได้";exit;
            }
            return array(@$link,@$title);
        }
    }
    $feed = new fteFeed();
    $vec    = $feed->vec();
    $link   = $vec[0]; // link
    $title  = $vec[1]; // title
/////////////////////
$fetch = null;
?>

<div class="panel panel-default">
<div class="panel-body">
<table class="table" id="dataTables-example">
    <thead>
        <tr>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
<?php
for($i=0;$i<count($title);$i++){
                print "<tr>";
                print "<td>
<img src='../../assets/img/arrow.png'/>
                </td>";
                print "<td>
<a href='http://www.vec.go.th/$link[$i]' target='_blank'>$title[$i]</a>
                </td>";
                print "</tr>";
}
?>

    </tbody>
</table>
</div>
</div>