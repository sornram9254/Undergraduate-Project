<html moznomarginboxes mozdisallowselectionprint>
<script>
    window.print();
    window.onfocus=function(){ window.close();}
</script>
<style>
    @media print {
      @page { margin: 10px; }
      body { margin: 1.6cm; }
         * { font-size:12px; }
    }
    h1,h2,h3,h4,h5,h6{
        text-align: center;
    }
</style>
<meta charset="utf-8" />
<link href="../assets/css/bootstrap.css" rel="stylesheet" />
<?php
    require_once("../include/SQL.php");
    $table = "data_std_detail_tb";
    $result = new cSQL();
    $res = $result->getSQL("SELECT * FROM $table");
    $row_count = $res->rowCount();
?>
<h3>รายงานข้อมูลนักศึกษา</h3>

<table border=1 id="tbReport" class='table table-striped table-bordered table-hover'>
    <tr>
        <td width=5%  style=color:red;><b>ลำดับ</b></td>
        <td width=5% style=color:red;><b>รหัสนักศึกษา</b></td>
        <td width=10% style=color:red;><b>ชื่อ</b></td>
        <td width=10% style=color:red;><b>นามสกุล</b></td>
        <td width=8% style=color:red;><b>รหัสหลักสูตร</b></td>
        <td width=5% style=color:red;><b>สาขา</b></td>
        <td width=8% style=color:red;><b>ระดับการศึกษา</b></td>
    </tr>
<?php
    foreach ($res as $row)
    {
        print "<tr selectThis='" . $row['ID_STD_DETAIL'] . "'>\n";
        print "    <td>" . $row['ID_STD_DETAIL'] . "</td>";
        print "    <td>" . $row['STU_CODE'] ."</td>";
        print "    <td>" . $row['STU_FIRST_NAME_THAI'] . "</td>";
        print "    <td>" . $row['STU_LAST_NAME_THAI'] . "</td>";
        print "    <td>" . $row['CURR_CODE'] ."</td>";
        print "    <td>" . $row['DIV_SHRT_NAME'] . "</td>";
        print "    <td>" . $row['LEVEL_DESC'] . "</td>";
        print "</tr>\n";
    }
    echo '</table>';
    return;
?>
</html>