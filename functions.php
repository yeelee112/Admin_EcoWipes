<?php
    function shortifyCurrencyXtream($var)
    {
        $var = doubleval($var);
        $print_str = "";

        // if($var>=1000000000){
        //   $print_str.=round($var/1000000000)."b";
        //   $var=$var%1000000000;
        // }
        if ($var >= 1000000) {
            if ($print_str != "") $print_str .= " ";
            $print_str .= round($var / 1000000) . ".";
            $var = $var % 1000000;
        }
        if ($var >= 1000) {
            if ($print_str != "") $print_str .= "";
            $print_str .= ($var / 1000) . "k";
        }
        return $print_str;
    }

    function getDateInMonth($month, $year)
    {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates_month = array();

        for ($i = 1; $i <= $num; $i++) {
            $mktime = mktime(0, 0, 0, $month, $i, $year);
            $date = date("d/m", $mktime);
            $dates_month[] = $date;
        }

        return $dates_month;
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'năm',
            'm' => 'tháng',
            'w' => 'tuần',
            'd' => 'ngày',
            'h' => 'giờ',
            'i' => 'phút',
            's' => 'giây',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' trước' : 'Vừa xong';
    };
    
    function after_last ($string, $inthat)
    {
        if (!is_bool(strripos($inthat, $string)))
        return substr($inthat, strripos($inthat, $string)+strlen($string));
    };

    if (!isset($_SESSION)) {
		session_start();
	}
	if (!isset($_SESSION['adminName']) && !isset($_SESSION['adminEmail'])) {
		header("Location: /account/sign-in");
	}

    $logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";

    if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
        $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
    }

    if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
        $_SESSION['adminName'] = NULL;
        $_SESSION['adminEmail'] = NULL;
        unset($_SESSION['adminName']);
        unset($_SESSION['adminEmail']);

        header("Location: /");
    }
?>
