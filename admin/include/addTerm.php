<?php if($_COOKIE['level']==1 || $_COOKIE['level']==2){ ?>
<meta charset="utf-8" />
<link href="../assets/css/bootstrap.css" rel="stylesheet" />
<link href="../assets/css/font-awesome.css" rel="stylesheet" />
<link href="../assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
<script type="text/javascript" src="../assets/js/jquery.min.js"></script>


<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap-datetimepicker.th.js"></script>
<?php
    require("SQL.php");
    require("_chkCookie.php");
    require("_chkPermission.php");
    require("dbconnect.php");
    
    $term       =  (isset($_POST['term'])) ? $_POST['term']   : '';
    $year       =  (isset($_POST['year'])) ? $_POST['year']   : '';
    $start_date =  (isset($_POST['start_date'])) ? $_POST['start_date']   : '';
    $end_date   =  (isset($_POST['end_date'])) ? $_POST['end_date']   : '';
?>
<div class="panel panel-default">
    <div class="panel-heading">กรุณากรอกข้อมูลให้ครบทุกช่อง</div>
    <div class="panel-body">
        <div class="table-responsive">
            <form method="POST" action="addTerm.php">
                <div class="form-group input-group" style="min-width:240px;">
                    <div class="form-group" style="padding-bottom:30px;">
                        <label>เทอม</label>
                            <select name="term" class="form-control" required>
                                <option value="">--- กรุณาเลือก ---</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                    </div>
                </div>
                <div class="form-group input-group" style="min-width:240px;">
                    <div class="form-group" style="padding-bottom:30px;">
                        <label>ปีการศึกษา</label>
                        <select name="year" class="form-control" required>
                            <option value="">--- กรุณาเลือก ---</option>
                            <?php
                                for($i=2559;$i<=2600;$i++){
                                    print "<option value='".$i."'>".$i."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <label>วันเริ่มภาคเรียน</label>
                <div class="form-group input-group" style="max-width:500px;">
                    <span class="input-group-addon"><i class="fa fa-list-alt"></i></span>
                    <div class="input-group date form_date col-md-5" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd">
                        <input class="form-control" type="text" value="" placeholder="วันเริ่มภาคเรียน" name="start_date" readonly required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
<script type="text/javascript">
	$('.form_date').datetimepicker({
        language:  'th',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
</script>   
                </div>
                <label>วันสิ้นสุดภาคเรียน</label>
                <div class="form-group input-group" style="max-width:500px;">
                    <span class="input-group-addon"><i class="fa fa-list-alt"></i></span>
                    <div class="input-group date form_date col-md-5" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd">
                        <input class="form-control" type="text" value="" placeholder="วันสิ้นสุดภาคเรียน" name="end_date" readonly required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
<script type="text/javascript">
	$('.form_date').datetimepicker({
        language:  'th',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
</script>   
                </div>
                <div class="form-group input-group" style="width:50%;min-width:250px;">
                    <button type="submit" class="btn btn-primary" onclick="">ส่งข้อมูล</button>
                    <button type="reset" class="btn btn-default">ล้างข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    if($term!='' && $year!='' && $start_date!=''){
        $exec = new cSQL();
        $res = $exec->getSQL("SELECT * FROM `term` WHERE TERM = $term AND YEAR = $year;");
        $row = $res->fetch();
        $row_count = $res->rowCount();
        if( $row_count > 0 ) { # If rows are found for query
            $result = "[DEBUG] - User already exists!";
            $result .= "<br/>[DEBUG] - Insert Failure!";
        }
        else {
            $result = "[DEBUG] - User not found!";
            $this_stmt = $this_db->prepare("INSERT INTO `term` (TERM,YEAR,START_DATE,END_DATE)
                        VALUES (:TERM, :YEAR, :START_DATE, :END_DATE)");
            $this_stmt->bindParam(':TERM', $term);
            $this_stmt->bindParam(':YEAR', $year);
            $this_stmt->bindParam(':START_DATE', strtotime($start_date));
            $this_stmt->bindParam(':END_DATE', strtotime($end_date));
            // insert a row
            if ($this_stmt->execute()) {
                $result .= "<br/>[DEBUG] - Insert Success!";
                print "<script>alert('Insert Success');</script>";
                print "<script>window.close();</script>";
            }else{
                $result = "[DEBUG] - Insert Failure!";
                print "<script>alert('Insert Failure');</script>";
                print "<script>window.close();</script>";
            }
        }
    }
} else{}
?>
