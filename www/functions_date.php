<?php
  function tow()
  {
    return @strftime("%H:%M:%S", mktime());
  }

  function dow()
  {
    return @strftime("%Y-%m-%d", mktime());
  }

  function now3()
  {
    $t = microtime(true);
    $micro = sprintf("%03d",($t - floor($t)) * 1000);
    $d = new DateTime(date("Y-m-d H:i:s.".$micro, $t) );
    return substr($d->format("Y-m-d H:i:s.u"), 0, 23);
  }

  function hms($sec)
  {
    $hour = floor(($sec)/3600);
    $minute =floor(($sec)/60)%60;
    $second = ($sec)-($hour*3600+$minute*60);
    return (date("H:i:s",strtotime($hour.":".$minute.":".$second)));
  }

  function now($details="")
  {
    return @strftime("%Y-%m-%d %H:%M:%S", mktime());
  }

  function year_of($md)
  {
    return substr($md, 0, 4);
  }

  function month_of($md)
  {
    return substr($md, 5, 2);
  }

  function day_of($md)
  {
    return substr($md, 8, 2);
  }

  function fd2md($d, $fmt="")
  {
    if ($d!="")
    {
      return substr($d,6,4)."-".substr($d,3,2)."-".substr($d,0,2)
        .($fmt == "HMS"?" ".substr($d,11,8):"")
        .($fmt == "HM"?" ".substr($d,11,5):"")
      ;
    }
    else
    {
      return "";
    }
  }

  function md2fd($d, $fmt="")
  {
    if ($d!="")
    {
      return substr($d,8,2)."/".substr($d,5,2)."/".substr($d,0,4)
        .($fmt == "HMS"?" ".substr($d,11,8):"")
        .($fmt == "HM"?" ".substr($d,11,5):"")
      ;
    }
    else
    {
      return "";
    }
  }

  function md2first($d)
  {
    return substr($d, 0, 7)."-01";
  }

  function first_day_of_week($md)
  {
    $first = md2monday($md);
    return $first;
  }

  function last_day_of_week($md)
  {
    $first = md2monday($md);
    $last = php2md(strtotime("$first"." + 6 day"));
    $last = substr($last, 0, 10);
    return $last;
  }

  function first_day_of_month($md)
  {
    $first = substr($md, 0, 7)."-01";
    return $first;
  }

  function last_day_of_month($md)
  {
    $first = first_day_of_month($md);
    $last = php2md(strtotime("$first"." + 1 month - 1 day"));
    $last = substr($last, 0, 10);
    return $last;
  }

  function first_day_of_quarter($md)
  {
    $month = substr($md, 5, 2);
    $month = 3*floor(($month-1)/3)+1;
    while (strlen($month)<2) { $month ="0".$month; }
    $first = substr($md, 0, 4)."-".($month)."-01";
    return $first;
  }

  function last_day_of_quarter($md)
  {
    $first = first_day_of_quarter($md);
    $last = php2md(strtotime("$first"." + 3 month - 1 day"));
    $last = substr($last, 0, 10);
    return $last;
  }

  function first_day_of_year($md)
  {
    $first = substr($md, 0, 4)."-01-01";
    return $first;
  }

  function last_day_of_year($md)
  {
    $first = substr($md, 0, 4)."-12-31";
    return $first;
  }

  function time2plus($time, $n)
  {
    return date("H:i", strtotime("$n minutes", md2php("1970-01-01 $time", "HMS")));
  }

  function md2plus($d, $n)
  {
    $d = date("Y-m-d", strtotime("+$n day", md2php($d)));
    return $d;
  }

  function md2plussec($d, $n)
  {
    $d = date("Y-m-d H:i:s", strtotime("+$n second", md2php($d)));
    return $d;
  }

  function md2monday($d)
  {
    $d1 = md2php($d)-(86400*(strftime("%u", md2php($d))-1)); // lundi
    return strftime("%Y-%m-%d", $d1);
  }

  function md2monday2($d)
  {
    return strftime("%Y-%m-%d"
      ,strtotime("- ".(strftime("%u", md2php($d))-1)." day", md2php($d)));
  }

  function php2md($d)
  {
    return @strftime("%Y-%m-%d %H:%M:%S", $d);
  }

  function md2php($d)
  {
    if ($d!="")
    {
      return @mktime(substr($d, 11, 2)
        , substr($d, 14, 2)
        , substr($d, 17, 2)
        , substr($d, 5, 2)
        , substr($d, 8, 2)
        , substr($d, 0, 4));
    }
    else
    {
      return "";
    }
  }

  function md2time($d)
  {
    return md2php($d);
  }

  function time2diff($t1, $t2)
  {
    @$sec = md2time("1970-01-01 ".$t2) - md2time("1970-01-01 ".$t1);
    return $sec;
  }

  function md2diff($d1, $d2)
  {
    @$sec = md2time($d2) - md2time($d1);
    return $sec;
  }

  function isoweek($adate)
  {
    $day = @date("d", $adate);
    $month = @date("m", $adate);
    $year = @date("Y", $adate);
    $week = @strftime("%V", @mktime(0, 0, 0, $month, $day, $year));
    if ($week > "0")
    {
      $isoweek = substr("0" . $week, -2);
    }
    else
    {
      $isoweek = isoweek(@mktime(0, 0, 0, 12, 31, $year - 1));
    }
    return $isoweek;
  }


  //Affichage temps passé depuis la publication (Comme Facebook)

    function time_ago($timestamp){

      date_default_timezone_set("Europe/Paris");
      $time_ago        = strtotime($timestamp);
      $current_time    = time();
      $time_difference = $current_time - $time_ago;
      $seconds         = $time_difference;

      $minutes = round($seconds / 60); // value 60 is seconds
      $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
      $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;
      $weeks   = round($seconds / 604800); // 7*24*60*60;
      $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
      $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

      if ($seconds <= 60){

        return "À l'instant";

      } else if ($minutes <= 60){

        if ($minutes == 1){

          return "il y a une minute";

        } else {

          return "$minutes minutes";

        }

      } else if ($hours <= 24){

        if ($hours == 1){

          return "il y a une heure";

        } else {

          return "$hours heures";

        }

      } else if ($days <= 7){

        if ($days == 1){

          return "Hier";

        } else {

          return "$days jours";

        }

      } else if ($weeks <= 4.3){

        if ($weeks == 1){

          return "il y a une semaine";

        } else {

          return "$weeks semaines";

        }

      } else if ($months <= 12){

        if ($months == 1){

          return "il y a un mois";

        } else {

          return "$months mois";

        }

      } else {

        if ($years == 1){

          return "il y a un an";

        } else {

          return "$years ans";

        }
      }
    }


?>
