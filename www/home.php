<?php
  // dump($_POST);

  require_once("functions_date.php");
  sqlconnect();
  if ($uid)
  {
    $sql = "select user_id"
      .", user_firstname"
      .", user_lastname"
      .", user_pseudo"
      .", user_city"
      .", user_bio"
      .", user_picture"
      .", user_firstdate"
      ." from app_user"
      ." where user_id = \"$uid\"";
    $rs = sqlexec($sql);
    if ($row = sqlrow($rs))
    {
      $user_id = $row["user_id"];
      $user_firstname = $row["user_firstname"];
      $user_lastname = $row["user_lastname"];
      $user_pseudo = $row["user_pseudo"];
      $user_city = $row["user_city"];
      $user_bio = $row["user_bio"];
      $user_picture = $row["user_picture"];
      $user_firstdate = $row["user_firstdate"];
      $user_age = strftime("%B %Y", md2php($user_firstdate));
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
    $user_firstdate = "";
  }
//Début body
  print " <div class=\"w3-row\">";
//Début colonne gauche
    print " <div class=\"w3-col m3\">";
    if (!$uid)
    {
//Card nouveau sur SomeDevs ?
      print " <div id=\"Profil_card\" class=\"w3-container w3-card-4 w3-center \"> ";
        print " <h2>Nouveau sur SomeDevs ?</h2> ";
        print " <p>Inscrivez-vous et retrouvez tous les devs du coin !</p> ";
        print " <a id=\"lien_connexion\" href=\"/page/register\">S'inscrire</a> ";
        print " <p>En vous inscrivant, vous acceptez les Conditions d'Utilisation et la Politique de Confidentialité, incluant l'Utilisation de Cookies.</p> ";
      print " <div class=\"w3-container w3-center\"> ";
      print " </div> ";
      print " <div class=\"w3-container w3-center\"> ";
      print " </div> ";
      print " </div> ";
    }
    if ($uid)
    {
//Card Profil
      print " <div id=\"Profil_card\" class=\"w3-card-4 w3-margin w3-center w3-hide-small \"> ";
      //affichage avatar
        //if (file_exists("/avatar/". $_SESSION['id'] . "/". $_SESSION['user_picture']) &&  isset($_SESSION['user_picture']))
        if (!empty($user_picture)/* && isset($_SESSION['user_picture'])*/)
        {
            print "<a href=\"/page/profil/$user_id\"><img id=\"Profil_card_picture\" class=\"w3-hide-small w3-circle w3-center \" style=\"height:100px;\" src=\"/public/avatars/$user_picture\" alt=\"Avatar\"></a>";
        }
        else
        {
            print "<a href=\"/page/profil/$user_id\"><img id=\"Profil_card_picture\" class=\"w3-hide-small w3-circle w3-center \" style=\"height:100px;\"  src=\"/public/avatars/defaults/Default-avatar.jpg\" alt=\"Avatar\"></a>";  //Si pas d'avatar, ont en affiche un manuellement.
        }
      //Fin affichage avatar
        print " <div class=\"w3-container w3-center\"> ";
            print " <a href=\"/page/profil/$user_id\" id=\"Profil_card_name\">$user_firstname $user_lastname</a> ";
            print " <p id=\"Profil_card_pseudo\">@$user_pseudo</p> ";
            print " <p class=\"w3-left-align\" id=\"Profil_card_bio\">$user_bio</p> ";
        print " </div> ";
        print " <div class=\"w3-container w3-left-align\"> ";
            print " <p  id=\"Profil_card_calendar\"><img class=\"Profil_card_icon\" src=\"/images/calendrier.png\"  alt=\"icon calendrier\">Inscrit en $user_age</p> ";
            print " <p id=\"Profil_card_city\"><img class=\"Profil_card_icon\" src=\"/images/location.png\"  alt=\"icon location\">$user_city</p> ";
        print " </div> ";
      print " </div> ";
    }
//Fin colonne gauche
    print " </div> ";
//Début colonne millieu
    print "<div class=\"w3-col m6\">";
        if ($uid)
        {
        //bloc Commencer un post
                print "<div id=\"Starting_post\" class=\"w3-container w3-center\">";
                    print "<div id=\"Starting_post_container_button\" class=\"w3-container w3-left-align\">";
                        //affichage avatar
                        if (!empty($user_picture)/* && isset($_SESSION['user_picture'])*/)
                        {
                            print "<a href=\"/page/profil/$user_id\"><img src=\"/public/avatars/$user_picture\" id=\"Starting_post_avatar\" class=\" w3-circle w3-hover-none\" style=\"height:60px;\"  alt=\"Avatar\"></a>";
                        }
                        else
                        {
                            print "<a href=\"/page/profil/$user_id\"><img src=\"/public/avatars/defaults/Default-avatar.jpg\" id=\"Starting_post_avatar\" class=\" w3-circle w3-hover-none\" style=\"height:60px;\" alt=\"Avatar\"></a>";  //Si pas d'avatar, ont en affiche un manuellement.
                        }
                        //Fin affichage avatar
                        print "<a href=\"/page/post\" id=\"Starting_post_create\" class=\"w3-bar-item w3-button w3-left-align\">Quoi de neuf, $user_firstname ?</a>\n";
                    print "</div>";

                    print "<div id=\"Starting_post_container_icon\" class=\"w3-container w3-center\">";
                        print "<div class=\"w3-container w3-disabled\"> ";
                            print "<p id=\"Starting_post_picture\"><img class=\"Starting_post_icon\" src=\"/images/add-picture.png\"  alt=\"icon ajout photo\">Photo</p>";
                        print "</div>";
                        print "<div class=\"w3-container w3-disabled\"> ";
                            print "<p id=\"Starting_post_video\"><img class=\"Starting_post_icon\" src=\"/images/add-video.png\"  alt=\"icon ajout vidéo\">Vidéo</p>";
                        print "</div>";
                        print "<div class=\"w3-container w3-disabled w3-hide-small\"> ";
                            print "<p id=\"Starting_post_event\"><img class=\"Starting_post_icon\" src=\"/images/add-events.png\"  alt=\"icon ajout Événement\">Événement</p>";
                        print "</div>";
                        print "<div class=\"w3-container w3-disabled w3-hide-small\"> ";
                            print "<p id=\"Starting_post_article\"><img class=\"Starting_post_icon\" src=\"/images/add-file.png\"  alt=\"icon ajout article\">Rédiger un article</p>";
                        print "</div>";
                    print "</div>";
                print "</div>";
        }
        //bloc Fil d'actualité (feed)
        // J'aime une publication
        @$btnlike = clean_field($_POST["btnlike"]);
        if ($btnlike)
        {
          $error = "";
          if (!$error)
          {
            $is_like = sqlone("select like_id"
                ." from app_like"
                ." where like_user_id = \"$uid\""
                ." and like_article_id = \"$btnlike\""
            );
            if ($is_like)
            {
              $error = "Vous aimez déjà";
            }
          }
          if (!$error)
          {
            $sqli = "insert into app_like set"
              ."  like_user_id = \"$uid\""
              .", like_article_id = \"$btnlike\""
              .", like_datetime = now()"
            ;
            sqlexec($sqli);
            //redirect("/page/$page");
          }
        }
        // Je n'aime plus une publication
        @$btndislike = clean_field($_POST["btndislike"]);
        if ($btndislike)
        {
          $sqld = "DELETE
            FROM app_like
            WHERE like_user_id = \"$uid\"
            AND like_article_id = \"$btndislike\"";
          sqlexec($sqld);
        }
        // Delete une publication
        @$btndeletearticle = clean_field($_POST["btndeletearticle"]);
        if ($btndeletearticle)
        {
          // Supprimer la photo de l'article si elle existe
          // ***

          $sqld = "DELETE
            FROM app_article
            WHERE article_user_id = \"$uid\"
            AND article_id = \"$btndeletearticle\"";
          sqlexec($sqld);

          // Supprimer aussi tous les éléments liés à l'article
          // ***
        }
        // Partager une publication
        @$btnshare = clean_field($_POST["btnshare"]);
        if ($btnshare)
        {
            $error = "";
            /*
            if (!$error)
            {
                $is_share = sqlone("select share_id"
                    ." from app_share"
                    ." where share_user_id = \"$uid\""
                    ." and share_article_id = \"$btnshare\""
                );
                if ($is_share)
                {
                    $error = "Vous partagez déjà";
                }
            }
            */
            if (!$error)
            {
              $article_id = $btnshare;

              // Récupérer les infos de l'article à partager
              list($article_author_id, $article_text, $article_picture)
                = sqlthree("select article_author_id, article_text, article_picture"
                  ." from app_article where article_id = \"$article_id\""
                );
              // Dupliquer l'article
              $sqli = "insert into app_article set"
                ." article_user_id = \"$uid\""
                .", article_author_id = \"$article_author_id\""
                .", article_text = \"$article_text\""
                .", article_picture = \"$article_picture\""
                .", article_share_id = \"$article_id\""
                .", article_datetime = now()"
              ;
              sqlexec($sqli);
            }
        }

        include "pagination.php";

            $sql = "select * from app_user U"// Requete affichage des articles
              ." left join app_article A on A.article_user_id = U.user_id"
              ." where article_user_id = U.user_id"
              ." order by article_datetime DESC"
              ." limit ".($pagesize * ($p-1)).", $pagesize"
            ;
            $rs = sqlexec($sql);
            while ($row = sqlrow($rs))
            {
              $user_id = $row["user_id"];
              $user_picture = $row["user_picture"];
              $user_firstname = $row["user_firstname"];
              $user_lastname = $row["user_lastname"];
              $user_pseudo = $row["user_pseudo"];
              $user_super = $row["user_super"];
              $article_id = $row["article_id"];
              $article_author_id = $row["article_author_id"];
              $article_text = $row["article_text"];
              $article_text = nl2br($article_text);
              $article_text = activate_links($article_text);
              $article_picture = $row["article_picture"];
              $article_share_id = $row["article_share_id"];
              $article_datetime = $row["article_datetime"];
              $article_age = time_ago($article_datetime);

                  if (!empty($article_share_id))
                  {
                      include "incarticleshare.php";

                  }
                  else
                  {
                      include "incarticle.php";

                  }
            }
        include "pagination.php";
    print "</div>";//Fin Colonne millieu
//Début Colonne droite
    print "<div class=\"w3-col m3\">";
//bloc Actualités (option)
//bloc Pub (option)
//bloc Messagerie (option)
//bloc Footer
    print "<div id=\"Starting_post\" class=\"w3-container\">";
      print "<p>";
          print "<a href=\"/page/cgu\">";
          print "Conditions d’utilisation";
          print "</a>";
          print " / ";
          print "<a href=\"/page/privacy\">";
            print "Politique de Confidentialité";
          print "</a>";
          print " / ";
          print "<a href=\"/page/cookies\">";
             print "Politique relative aux cookies";
          print "</a>";
      print "</p>\n";
      print "<div class=\"w3-container w3-center\"> ";
          print "<p> SomeDevs © 2022 </p>";
      print "</div>";
    print "</div>";
//Fin Colonne droite
    print "</div>";
//Fin body
  print "</div>";
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