<?php
$listCurr = array(
0  => array("ครุศาสตร์ไฟฟ้า", "0202" ,"วิศวกรรมไฟฟ้า TE","Electrical Engineering TE"  ,"4","55020214"),
1  => array("ครุศาสตร์ไฟฟ้า", "0202" ,"วิศวกรรมไฟฟ้า TTE","Electrical Engineering TTE"  ,"2-3","55020242"),
2  => array("ครุศาสตร์โยธา", "0203" ,"วิศวกรรมโยธาและการศึกษา","Civil Engineering and Education"  ,"5","56020325"),
3  => array("ครุศาสตร์โยธา", "0203" ,"วิศวกรรมโยธาและการศึกษา","Civil Engineering and Education"  ,"5","58020325"),
4  => array("ครุศาสตร์เครื่องกล", "0201","วิศวกรรมการผลิตและอุตสาหการ","Production and Industrial Engineering"  ,"4"  ,"55020164"),
5  => array("ครุศาสตร์เครื่องกล", "0201","วิศวกรรมแมคคาทรอนิกส์","Mechatronics Engineering"  ,"4","55020134"),
6  => array("ครุศาสตร์เครื่องกล", "0201","วิศวกรรมเครื่องกล","Mechanical Engineering"  ,"4","55020114"),
7  => array("ครุศาสตร์เครื่องกล", "0201","วิศวกรรมเครื่องกลการผลิตและแมคคาทรอนิกส์","Mechanical Production and Mechatronics Engineering"  ,"4","xxxxxxxx"),
8  => array("คอมพิวเตอร์ศึกษา", "0204","เทคโนโลยีคอมพิวเตอร์","Computer Technology CED"  ,"4","54020414"),
9  => array("คอมพิวเตอร์ศึกษา", "0204","เทคโนโลยีคอมพิวเตอร์","Computer Technology CED5Y"  ,"5","59020415"),
10  => array("คอมพิวเตอร์ศึกษา", "0204","เทคโนโลยีคอมพิวเตอร์","Computer Technology TCT 2.5Y"  ,"2-3","54020412"),
11  => array("คอมพิวเตอร์ศึกษา", "0204","เทคโนโลยีคอมพิวเตอร์","Computer Technology TCT 3Y"  ,"3","59020413"),
);
$listDesc = array(
    0 => "ภาควิชา",
    1 => "รหัสภาควิชา",
    2 => "สาขาวิชา (ภาษาไทย)",
    3 => "สาขาวิชา (ภาษาอังกฤษ)",
    4 => "หลักสูตร(ปี)",
    5 => "รหัสหลักสูตร",
);
$listDept = array(
    0 => array("ครุศาสตร์ไฟฟ้า","0202"),
    1 => array("ครุศาสตร์โยธา","0203"),
    2 => array("ครุศาสตร์เครื่องกล","0201"),
    3 => array("คอมพิวเตอร์ศึกษา","0204"),
);
//////////////////////////////////////////
$arrDeptSize = count($listDept)-1;
$arrDescSize = count($listCurr)-1;
$arrCurrSize = count($listDesc)-1;
?>