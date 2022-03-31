<?php
    //$currCode = '55020164'; // 54020414 // 55020164
    //$stuYear = '5'; //3 //5
    if($currCode!="" || $stuYear!=""){
        $postdata_openSection = http_build_query(
            array(
                'facCode'    => '02',
                'deptCode'   => '0204',
                'selectedBy' => 'byCurr',
                'currCode'   => $currCode
            )
        );
        $opts_openSection = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata_openSection
            )
        );
        $context_openSection  = stream_context_create($opts_openSection);
        $result_openSection = file_get_contents('http://klogic.kmutnb.ac.th:8080/kris/tess/dataQuerySelector.jsp?query=openSectionTab', false, $context_openSection);
        if (preg_match_all("/(\d+\/\d+)<\/b><\/font>/i", $result_openSection,$result_term_year)) {
            $term_year_course = $result_term_year[1];
        }
        $term_year_course = implode($term_year_course);
        //print $term_year_course;
        
        $result_openSection = preg_replace("/<td width=\"\d+%\" valign=\"top\" align=\"right\">\d+<\/td>/","",$result_openSection);
        $result_openSection= preg_replace("/(\d+ \w.+\(\d-\d\))/","\{$1",$result_openSection);
        $result_openSection= str_replace("\\","",$result_openSection);
        // CURR_ID_NAME|SEC|DAY|TIME|TEACHER|ROOM
        // <\w+|(.*?)>(.*?)<\/\w+> | <\w+|(.*?)>(\w+|\s+|\d+) | <\w+|(.*?)>(.*+)
        if (preg_match_all("/(<b>(.*?)<\/b>|<td.*?>(.*?)<\/td>|<tr><td>\d+-\s+(.*?)\d+\s+\w+|<\/td>)/", $result_openSection,$matches_out1)) { // <b>(\d+)<\/b>
            $srcHtml      = $matches_out1[1];
        }
        $regexResult = array();
        foreach($srcHtml as $i => $value) {
            $value = str_replace("&nbsp;","",$value);
            $value = preg_replace("/(\d+)-\s+(\d+)\s+(\w+)/","$1-$2$3",$value);
            $value = preg_replace("/<\w+>|<\w+ (.*?)>|<\/\w+>/","",$value);
            array_push($regexResult, $value);
        }
        $regexResult = str_replace("{","}{",$regexResult);
        $regexResult = array_splice($regexResult,22); //($arr,start,end) | end=optional
        $regexResultEmptyRemoved = array_filter($regexResult);
        $arr_openSection = array_values($regexResultEmptyRemoved);
        $arr_openSection[0] = substr($arr_openSection[0], 1);
        array_push($arr_openSection, '}');
        $xxx = implode('|',$arr_openSection);
        if (preg_match_all("/\{(.*?)\}/", $xxx,$XYZ)) {
            $arrCourseDetail = $XYZ[1];
        }
        ///////////////////////////////////////////////////////////////
        $postdata_studentTab = http_build_query(
            array(
                'facCode'       => '02',
                'currCode'      => $currCode,
                'stuType'       => 'R',
                'stuYear'       => $stuYear,
                'stuRound'      => 'R',
                'stuGroup'      => 'A',
                'timePrecision' => '4'
            )
        );
        $opts_studentTab = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata_studentTab
            )
        );
        $context_studentTab  = stream_context_create($opts_studentTab);
        $result_studentTab = file_get_contents('http://klogic.kmutnb.ac.th:8080/kris/tess/dataQuerySelector.jsp?query=studentTab', false, $context_studentTab);
        if (preg_match_all("/<br>(.*):\s+(\d\w+)\s(.*)\s+<\/b><\/td><\/tr>/", $result_studentTab,$matches_out1_studentTab)) {
            $xxxx = $matches_out1_studentTab[2];
        }
        if (preg_match_all("/<\/td>\s+<\/tr>\s+<tr valign=\"top\">\s+<td>(\d+)<\/td>/", $result_studentTab,$matches_out2_studentTab)) {
            $courseNo = $matches_out2_studentTab[1];
        }
        $xxxx = implode($xxxx); // array to string
        $stdCodeCurr = $currCode."-".$xxxx;
        $arrCurrMatch = array();
        foreach($arrCourseDetail as $currMatch) {
            if(preg_match("/$stdCodeCurr/",$currMatch)) {
                //echo "found: =>".$currMatch."\n\n";
                array_push($arrCurrMatch, $currMatch);
            }
        }
        $arrCurrMatch = preg_replace("/\|/","\n",$arrCurrMatch);
        $arrCurrMatch = preg_replace("/((\w.\d+)\n\w\n(\d+:\d+-\d+:\d+))/","\n$1",$arrCurrMatch);
        $arrCurrMatch = preg_replace("/\n\n/","\n\n[",$arrCurrMatch);
        $arrCurrMatch = preg_replace("/(\d{8}-\d\w{2})\n\n/","$1]\n\n",$arrCurrMatch);
        $arrCurrMatchTotal = count($arrCurrMatch)-1;
        for($i=0;$i<=$arrCurrMatchTotal;$i++){
            $arrCurrMatch[$i] = $arrCurrMatch[$i]."]";
        }
        $arrCurrMatch = preg_replace("/(\d{8}-(.{3}))\n]/","$1]\n",$arrCurrMatch);
        $arrCurrMatch = preg_replace("/\n\n/","\n",$arrCurrMatch);
        $arrCurrNameFinal = array();
        $arrCurrDescFinal = array();
        foreach($arrCurrMatch as $index => $value){   //(\d+ (.*?))\n
            if(preg_match("/(\d+ (.*?))\n/",$arrCurrMatch[$index],$value1)) {
                array_push($arrCurrNameFinal, $value1[1]);
            }
            if(preg_match("/\[([^]]+)$stdCodeCurr\]/",$arrCurrMatch[$index],$value2)) {
                array_push($arrCurrDescFinal, $value2[1]);
            }
        }
        //print_r($arrCurrNameFinal);
        //print_r($arrCurrDescFinal);
    }else{
        // NOP
    }
?>
