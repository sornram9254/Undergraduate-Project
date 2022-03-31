<?php
    // init
    error_reporting(E_ERROR | E_PARSE);
    header('Content-Type: text/html; charset=utf-8');
    $curr_code = $_GET['curr_code'];

    $xml_string = "http://klogic.kmutnb.ac.th:8080/kris/curri/showXML.jsp?currCode=" . $curr_code;
    $xmldata = @file_get_contents($xml_string);
    $xml = simplexml_load_string($xmldata) or die("Error: Cannot create object");

///////////////////////////////////////////////////////////////////////////////////////

    $courseArr = @array();
    $courseCount = $xml->Courses->Course->count();
    for($k=0;$k<$courseCount;$k++){
        #echo $xml->Courses->Course[$k]->attributes() . " - ";
        #echo $xml->Courses->Course[$k]->NameEng  . " - ";
        #echo $xml->Courses->Course[$k]->NameThai . " - ";
        #echo $xml->Courses->Course[$k]->ShrtName . " - ";
        #echo $xml->Courses->Course[$k]->DescEng  . " - ";
        #echo $xml->Courses->Course[$k]->DescThai . " - ";   
        #echo $xml->Courses->Course[$k]->Flag     . " - ";
        #echo $xml->Courses->Course[$k]->Type     . " - ";
        #echo $xml->Courses->Course[$k]->SU       . " - ";
        #echo $xml->Courses->Course[$k]->Level    . " - ";
        #echo $xml->Courses->Course[$k]->Crd      . " - ";
        #echo $xml->Courses->Course[$k]->Crd_Lec  . " - ";
        #echo $xml->Courses->Course[$k]->Crd_Lab  . " - ";
        #echo $xml->Courses->Course[$k]->No_Hlec  . " - ";
        #echo $xml->Courses->Course[$k]->No_Hlab  . "\r\n\n";

        $courseNumber = $xml->Courses->Course[$k]->attributes();
        $courseNameEng = $xml->Courses->Course[$k]->NameEng;
        $courseArr[trim($courseNumber)]  = $courseNameEng ;
    }

///////////////////////////////////////////////////////////////////////////////////////

    $termCount = $xml->Plans->Plan->YearSem->count();
    for($i=0;$i<$termCount;$i++){
        //===================================================
        $attr = $xml->Plans->Plan->YearSem[$i]->attributes();
        echo "Year: " . $attr['year'] . "\r\n";
        echo "Term: " . $attr['sem'] . "\r\n";
        //===================================================
        $courseCount = $xml->Plans->Plan->YearSem[$i]->Course->count();
        for($j=0;$j<$courseCount;$j++){
            $courseID = $xml->Plans->Plan->YearSem[$i]->Course[$j]->Display;

            if (strpos($courseID, 'X') !== false) { // ถ้าเจอ string แสดงว่าเป็นวิชาเลือกภาษา/เลือกเสรี
                echo "FREE";
            }else{
                echo $courseID;
            }

            echo $courseArr[trim($courseID)];
            echo $xml->Plans->Plan->YearSem[$i]->Course[$j]->Crd       . " - ";
            echo $xml->Plans->Plan->YearSem[$i]->Course[$j]->No_Hlec   . " - ";
            echo $xml->Plans->Plan->YearSem[$i]->Course[$j]->No_Hlab;
            echo "\r\n";
        }
        echo "===========================================================\r\n";
    }
    #print_r($courseArr);
    #print $courseArr['020413104'];

///////////////////////////////////////////////////////////////////////////////////////

    #echo $xml->Info->NameEng . "\n";    
    #echo $xml->Info->NameThai . "\n";
    #echo $xml->Info->Level . "\n";
    #echo $xml->Info->LevelText . "\n";
    #echo $xml->Info->Degree . "\n";
    #echo $xml->Info->DegreeText . "\n";
    #echo $xml->Info->Fac . "\n";
    #echo $xml->Info->FacText . "\n";
    #echo $xml->Info->Dept . "\n";
    #echo $xml->Info->DeptText . "\n";
    #echo $xml->Info->Div . "\n";
    #echo $xml->Info->DivText . "\n";
    #echo $xml->Info->BeginYear . "\n";
    #echo $xml->Info->BeginSem . "\n";
    #echo $xml->Info->CurrType . "\n";
    #echo $xml->Info->CurrTypeText . "\n";
    #echo $xml->Info->Edition . "\n";
    #echo $xml->Info->Certify . "\n";
    #echo $xml->Info->Comment;

?>
