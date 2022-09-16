<?php
  // Connexion base de donnée pour récupération infos

  @$id = clean_field($_REQUEST["id"]); // Récupération ID dans l'URL
  if (!$id) // Si pas d'id, notre profil
  {
    $id = $uid;
  }

  sqlconnect();

  if ($id)
  {
    $sql = "select *"
      ." from app_user"
      ." where user_id = \"$id\"";
    $rs = sqlexec($sql);
    if ($row = sqlrow($rs))
    {
      $user_id = $row["user_id"];
      $user_firstname = $row["user_firstname"];
      $user_lastname = $row["user_lastname"];
      $user_pseudo = $row["user_pseudo"];
      $user_city = $row["user_city"];
      $user_bio = $row["user_bio"];
      $user_banner = $row["user_banner"];
      $user_picture = $row["user_picture"];
      $user_firstdate = $row["user_firstdate"];
      $user_gender = $row["user_gender"];
    }
    else
    {
      $id = 0;
    }
  }
  else
  {
    $user_firstname = "";
    $user_lastname = "";
    $user_pseudo = "";
    $user_city = "";
    $user_bio = "";
    $user_picture = "";
    $user_banner = "";
    $user_firstdate = "";
    $user_gender = "";
  }

  if ($id)
  {
    if (!$key)
    {
      $key = "actu";
    }

    //Requete Visite profil
    if ($uid)
    {
        $sqli = "insert into app_visit set"
           ." visit_visitor_id = \"$uid\""
           .", visit_user_id = \"$user_id\""
           .", visit_date = now()";

        sqlexec($sqli);
    }
    //affichage bannière
    if (!empty($user_banner)/* && isset($_SESSION['user_picture'])*/)
    {
        print " <div id=\"container_profile\" class=\"w3-card\" style=\"background-image: url(/public/banners/$user_banner)\"> ";
    }
    else
    {
        print " <div id=\"container_profile\" class=\"w3-card\" style=\"background-image: url(/public/banners/defaults/Default-banner.png)\"> ";  //Si pas de bannière, ont en affiche une manuellement.
    }
    //Fin affichage bannière
      print " <div id=\"information_bar\"> ";

          //liens
          print "<ul>";
              print "<li class=\"".($key=="actu"?"active":"")."\"><a href=\"/page/$page/$id/actu\">Actualités</a></li>";
              print "<li class=\"".($key=="bio"?"active":"")."\"><a href=\"/page/$page/$id/bio\">Bio</a></li>";
              print "<li class=\"".($key=="friends"?"active":"")."\"><a href=\"/page/$page/$id/friends\">Amis</a></li>";
              print "<li class=\"".($key=="medias"?"active":"")." w3-hide-small w3-disabled\"><a href=\"/page/$page/$id/medias\">Médias</a></li>";
          print "</ul>";
          //profil
          print " <div id=\"profile_picture\"> ";
                //affichage avatar
                if (!empty($user_picture)/* && isset($_SESSION['user_picture'])*/)
                {
                    print "<img src=\"/public/avatars/$user_picture\" id=\"Profile_picture\" class=\"\"  alt=\"Avatar\">";
                }
                else
                {
                    print "<img src=\"/public/avatars/defaults/Default-avatar.jpg\" id=\"Profile_picture\" class=\"\"  alt=\"Avatar\">";  //Si pas d'avatar, ont en affiche un manuellement.
                }
                //Fin affichage avatar
          print " </div> ";
          print " <div id=\"profile_container_name\"> ";
              print " <p id=\"Profile_name\">$user_firstname $user_lastname</p> ";
              print " <p id=\"Profile_pseudo\">@$user_pseudo</p> ";
          print " </div> ";
      print " </div> ";
    print " </div> ";



    switch ($key)
    {
      case "actu":
      {
          include "profilactu.php";
      }
    }
    switch ($key)
    {
      case "bio":
      {
          include "profilbio.php";
      }
    }
    switch ($key)
    {
      case "friends":
      {
          include "profilfriends.php";
      }
    }
    switch ($key)
    {
      case "medias":
      {
          include "profilmedias.php";
      }
    }
  }
  else
  {
    print "Oups ! La page de profil n'existe pas";
  }
  sqldisconnect();


?>
<script>
    $(function(){
        $(".btnpos").click(function(e){
            localStorage.setItem("windowscroll", $(window).scrollTop());
        });
        var pos = localStorage.getItem("windowscroll", 0);
        if (pos > 0)
        {
            localStorage.setItem("windowscroll", 0);
            $(window).scrollTop(pos);
        }
    });
</script>
