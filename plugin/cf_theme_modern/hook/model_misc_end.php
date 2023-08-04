<?php
exit;

function return_bytes($val) {
    $val = trim($val);
    if(empty($val)){return '';}
    if(is_numeric($val)){
        return $val;
    }else {
        $key    = ['0','1','2','3','4','5','6','7','8','9'];
        $num = '';
        for ($i = 0;$i < strlen($val);$i++) {
            if (in_array($val[$i],$key)){
                $num .= $val[$i];
            }
        }
        $vtype = strtolower($val[strlen($val)-1]);
        $num=intval($num);
        switch($vtype) {
            // Use 'G' after PHP 5.1.0
            case 'g':
                $num = $num * 1024 * 1024 * 1024;
                break;
            case 'm':
                $num = $num * 1024 * 1024;
                break;
            case 'k':
                $num = $num * 1024;
                break;
            default :
                break ;
        }
        return $num ;
    }
    
    return $val ;
}

?>