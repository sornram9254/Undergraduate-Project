<?php
//header('Content-Type: text/html; charset=utf-8');
$host="localhost";
$user="root";
$pass="";
$db_name="stdcheckdb"; 
$tbl_name = "curr_xml_import";
$this_db =  new PDO("mysql:dbname=$db_name;host=$host", $user, $pass);
$this_db->exec("set names tis620");
isset($_REQUEST['XMLcurrCode']) ? $XMLcurrCode = $_REQUEST['XMLcurrCode'] : $XMLcurrCode = '';
//////////////////////////////////////////////////
require("include/listCurr.php");
//////////////////////////////////////////////////
if($facCode=='02'){ # คณะครุศาสตร์อุตสาหกรรม
?><br/>
<select name="deptCode" onChange="form.submit()" class="form-control" style="width:30%;min-width:200px;">
<option value="0"    <?php  if($deptCode=='0') {echo 'selected';} ?>>
- - - เลือกภาควิชา - - -</option>
<?php
    for($i=0;$i<=$arrDeptSize;$i++){
        print "<option value='".$listDept[$i][1]."' ";
        if($deptCode==$listDept[$i][1]){echo 'selected';}
        print ">".$listDept[$i][0]."</option>";
    }
}
?>
</select>
<?php
//////////////////////////////////////////////////
//////////////////////////////////////////////////
if($deptCode==$deptCode && $deptCode!=0){
?><br/>
<select name="currCode" onChange="form.submit()" class="form-control" style="width:30%;min-width:200px;">
<option value="0"        <?php  if($currCode=='0') {echo 'selected';} ?>>
- - - เลือกหลักสูตร - - -</option>
<?php
    //<option value="54020414" if($currCode=='54020414') {echo 'selected';}>54020414 หลักสูตรครุศาสตร์อุตสาหกรรมบัณฑิต สาขาวิชาเทคโนโลยีคอมพิวเตอร์ (CED)</option>
    for($i=0;$i<=$arrDescSize;$i++){
        if($listCurr[$i][1]==$deptCode){
            print "<option value='".$listCurr[$i][5]."' ";
            if($currCode==$listCurr[$i][5]){echo 'selected';}
            print ">";
            print $listCurr[$i][3];
            print "</option>";
        }
    }
?>
</select>
<?php
}
//////////////////////////////////////////////////
//////////////////////////////////////////////////
echo '<br/>';
// Check Token
#try
#{
#    NoCSRF::check( 'csrf_token', $_POST, true, 60*10, false );
    try{
        if($XMLcurrCode!=''){
            $xml_string = "http://klogic.kmutnb.ac.th:8080/kris/curri/showXML.jsp?currCode=" . $XMLcurrCode;
            ////////////////////////////////
            $xml_data = @file_get_contents($xml_string);
            $xml = simplexml_load_string($xml_data);
            $CURR_TH = $xml->Info->NameThai;
            $CURR_EN = $xml->Info->NameEng;
            //$CURR_EN = $xml->Info->DivText;
            ////////////////////////////////
            $query = $this_db->prepare( "SELECT *
                         FROM $tbl_name 
                         WHERE CURR_ID = :CURR_ID" );
            $query->bindParam(':CURR_ID', $XMLcurrCode);
            $query->execute();
            if( $query->rowCount() > 0 ) { # If rows are found for query
                $result = "[DEBUG] - CURR already exists!";
                $result .= "<br/>[DEBUG] - Insert Failure!";
            }
            else {
                $result = "[DEBUG] - CURR not found!";
                // prepare sql and bind parameters
                $this_stmt = $this_db->prepare("INSERT INTO $tbl_name (CURR_ID,CURR_TH,CURR_EN,CURR_XML)
                VALUES (:CURR_ID, :CURR_TH, :CURR_EN, :CURR_XML)");
                $this_stmt->bindParam(':CURR_ID', $XMLcurrCode);
                $this_stmt->bindParam(':CURR_TH', $CURR_TH);
                $this_stmt->bindParam(':CURR_EN', $CURR_EN);
                $this_stmt->bindParam(':CURR_XML', $xml_data);
                // insert a row
                if ($this_stmt->execute()) {
                    $result .= "<br/>[DEBUG] - Insert Success!";
                }else{
                    $result .= "<br/>[DEBUG] - Insert Failure!";
                }
            }
        }else{
            $result = "NOP<br/>".$currCode;
        }
    }
    catch(PDOException $e)
    {
        //echo "Error: " . $e->getMessage();
        $result = "[DEBUG] - Error: " . $e->getMessage();
    }
    $this_db = null;
#}
#catch ( Exception $e )
#{
#    // CSRF attack detected
#    $result = "[DEBUG] - " . $e->getMessage();
#}
#// Generate CSRF token to use in form hidden field
#$token = NoCSRF::generate( 'csrf_token' );
?>
    
<?php
    if($facCode!=0 && $deptCode!=0 && $currCode!=0){
?>
<form method='POST' action='CourseFull.php'>
    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
    <button type="submit" class="btn btn-default">นำเข้าข้อมูล</button>
    <input type='hidden' name='XMLcurrCode' value='<?php echo $currCode; ?>' />
</form>
<?php } ?>