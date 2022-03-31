<?php
//header("Content-type: text/xml; charset=utf-8");
header("Content-type: text/xml");
isset($_REQUEST['currCode']) ? $currCode = $_REQUEST['currCode'] : $currCode = '';
$xml_arr_currID=array();
$xml_arr_currXML=array();
$host="localhost";
$user="root";
$pass="";
$db_name="stdcheckdb"; 
$tbl_name = "curr_xml_import";
$this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
$this_db->exec("set names tis620");
$sql = "SELECT * FROM `curr_xml_import` WHERE `CURR_ID`=".$currCode;// $tbl_name";
$res = $this_db->prepare($sql);
$res->execute();
$res->setFetchMode(PDO::FETCH_ASSOC);//$row_count = $res->rowCount();
foreach ($res as $row)
{
    $xml_output = $row['CURR_XML'];
    #$xml_arr_currID[] = $row['CURR_ID'];// $row['CURR_ID']
    #$xml_arr_currXML[] = $row['CURR_XML'];// $row['CURR_ID']
}
echo $xml_output;
#echo $xml_arr_currID[0];
#echo $currCode;
#print_r($xml_arr_currID);
#print_r($xml_arr_currXML);
?> 