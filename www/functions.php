<?php
  function activate_links_internal($matches)
  {
    $link_displayed = (strlen($matches[0]) > 150) ? substr( $matches[0], 0, 50).'…'.substr($matches[0], -10) : $matches[0];
    return '<a href="'.$matches[0].'" title="'.$matches[0].'" target="_blank">'.$link_displayed.'</a>';
  }

  function activate_links($text)
  {
    $pattern = "`(?:http://|https://|ftp://)([,./?=&#_~@:!%\[\]\+\-a-z-A-Z0-9é]*)`";
    $text = preg_replace_callback($pattern, 'activate_links_internal', $text);
    return $text;
  }

  function redirect($url)
  {
    //header("location:$url");
    if (true)
    {
      print "<script>";
      print "document.location.href=\"$url\";";
      print "</script>";
    }
    else
    {
      print "Redirect vers : $url";
    }
  }

  function clean_field($field) // V4 (2019-08)
  {
    $field = str_replace("\\", "\\\\", $field);
    while (strpos($field, "  ")!==false)
    {
      $field = str_replace("  ", " ", $field);
    }
    $field = str_replace("\"", "&quot;", $field);
    $field = trim($field);
    return $field;
  }

  function dump($x)
  {
    print "<pre>";
    var_dump($x);
    print "</pre>";
  }

  function combo_array($tab, $aid)
  {
    $res = "";
    for ($i=0;$i<sizeof($tab);$i++)
    {
      $id = $tab[$i][0];
      $text = $tab[$i][1];
      $res .= "<option value=\"$id\"".($id==$aid?" selected":"")."/>";
      $res .= "$text\n";
    }
    return $res;
  }

  function radio_array($name, $tab, $aid)
  {
    $res = "";
    for ($i=0;$i<sizeof($tab);$i++)
    {
      $id = $tab[$i][0];
      $text = $tab[$i][1];
      $res .= "<label for=\"${name}_$i\">";
      $res .= "<input type=\"radio\" name=\"$name\" id=\"${name}_$i\""
        ." value=\"$id\"".($id==$aid?" checked":"")." class=\"double\"/>";
      $res .= "&nbsp;$text\n";
      $res .= "</label>\n";
    }
    return $res;
  }

  function ResizeImageCenterCrop($aImage, $aWidth, $aHeight)
  {
    if (substr($aImage, -4) == ".png")
    {
      $src_img = imagecreatefrompng($aImage);
    }
    else if (substr($aImage, -4) == ".gif")
    {
      $src_img = imagecreatefromgif($aImage);
    }
    else
    {
      $src_img = imagecreatefromjpeg($aImage);
    }
    $dst_img = imagecreatetruecolor($aWidth, $aHeight);
    $_xx = imagesx($src_img);
    $_yy = imagesy($src_img);
    // Center from the original picture
    if ($_xx >= $_yy * ($aWidth / $aHeight))
    {
      $_y = 0;
      $_h = $_yy;
      $_x = round(($_xx - $_yy * ($aWidth / $aHeight)) / 2);
      $_w = $_yy * ($aWidth / $aHeight);
    }
    else
    {
      $_x = 0;
      $_w = $_xx;
      $_y = round(($_yy - $_xx / ($aWidth / $aHeight)) / 2);
      $_h = $_xx / ($aWidth / $aHeight);
    }
    // Write picture
    imagecopyresampled($dst_img, $src_img, 0, 0, $_x, $_y
      , $aWidth, $aHeight, $_w, $_h);
    if (substr($aImage, -4) == ".png")
    {
      imagepng($dst_img, $aImage, 9);
    }
    else if (substr($aImage, -4) == ".gif")
    {
      imagegif($dst_img, $aImage);
    }
    else
    {
      imagejpeg($dst_img, $aImage, 100);
    }
    imagedestroy($src_img);
    imagedestroy($dst_img);
  }

/*  function replace_links($field){
      return str_replace("$field", "<a href='$field'>$field</a>", $field);
  }

function make_links_clickable($article_text, $class='links', $target='_blank'){
    return preg_replace('!((http\:\/\/|ftp\:\/\/|https\:\/\/)|www\.)([a-zA-Z0-9.?&_/])', '<a class="'.$class.'" href="//$3" target="'.$target'"></a>', $article_text);
}
*/

?>