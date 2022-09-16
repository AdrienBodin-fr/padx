<?php
  /*
    Site PADX.FR
    Stage Adrien BODIN 14/03/2022
  */

  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  date_default_timezone_set("Europe/Paris");
  setlocale(LC_ALL, "fr_FR");

  require_once("functions.php");
  require_once("functions_date.php");
  require_once("functions_mysqli.php");
  require_once("security.php");

  /* Structure URL : http://padx.fr/page/$page/$id */
  @$page = trim(stripslashes(str_replace("/", "", strtolower($_REQUEST["page"]))));
  if ((!$page)||($page == "index")) { $page = "home"; }
  @$id = htmlspecialchars($_REQUEST["id"]);
  @$key = htmlspecialchars($_REQUEST["key"]);
  @$mode = htmlspecialchars($_REQUEST["mode"]);

  print "<!DOCTYPE html>\n";
  print "<html lang=\"fr\">\n";
  print "<head>\n";
  print "<meta charset=\"utf-8\">\n";
  print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
  print "<meta name=\"description\" content=\"Retrouvez les devs près de chez vous.\">\n";
  print "<meta name=\"keywords\" content=\"développeur, web, social, network, Somedevs, rencontre\">\n";
  print "<meta name=\"author\" content=\"Adrien Bodin\">\n";
  // Balises OG
    /*
    if (!$uid)
    {
        print "<meta property=\"og:url\" content=\"https://padx.fr\">\n";
        print "<meta property=\"og:image\" content=\"https://padx.fr/images/og.jpg\">\n";
        print "<meta property=\"og:type\" content=\"website\">\n";
        print "<meta property=\"og:description\" content=\"Retrouvez les devs près de chez vous.\">\n";
        print "<meta property=\"og:locale\" content=\"fr_FR\">\n";
        print "<meta property=\"og:title\" content=\"$page | SomeDevs\">\n";
        //Twitter og
        print "<meta name=\"twitter:card\" content=\"summary_large_image\">\n";
        print "<meta name=\"twitter:creator\" content=\"@AdrienBodin / SWAT\">\n";
        print "<meta name=\"twitter:title\" content=\"$page | SomeDevs\">\n";
        print "<meta name=\"twitter:description\" content=\"Retrouvez les devs près de chez vous.\">\n";
        print "<meta name=\"twitter:image\" content=\"https://padx.fr/images/logo4.png\">\n";
    }
    */

  switch ($page) // Balises OG
  {
    case "profil":
    {
      @$id = clean_field($_REQUEST["id"]);
      if ($id)
      {
        sqlconnect();
        $sql = "select user_firstname"
          .", user_lastname"
          .", user_pseudo"
          .", user_picture"
          ." from app_user"
          ." where user_id = \"$id\"";
        $rs = sqlexec($sql);
        if ($row = sqlrow($rs))
        {
          $user_firstname = $row["user_firstname"];
          $user_lastname = $row["user_lastname"];
          $user_pseudo = $row["user_pseudo"];
          $user_picture = $row["user_picture"];
        }
        else
        {
          $id = 0;
        }
        sqldisconnect();
        // Avatar du user : $id
        print "<meta property=\"og:url\" content=\"https://padx.fr/page/profil/$id\">\n";
        print "<meta property=\"og:image\" content=\"https://padx.fr/public/avatars/$user_picture\">\n";
        print "<meta property=\"og:type\" content=\"profile\">\n";
        print "<meta property=\"profile:first_name\" content=\"$user_firstname\">\n";
        print "<meta property=\"profile:last_name\" content=\"$user_lastname\">\n";
        print "<meta property=\"profile:username\" content=\"$user_pseudo\">\n";
        print "<meta property=\"og:description\" content=\"Retrouvez moi sur SomeDevs.\">\n";
        print "<meta property=\"og:locale\" content=\"fr_FR\">\n";
        print "<meta property=\"og:title\" content=\"$user_firstname $user_lastname | SomeDevs\">\n";
        //Twitter og
        print "<meta name=\"twitter:card\" content=\"summary_large_image\">\n";
        print "<meta name=\"twitter:site\" content=\"@SomeDevs\">\n";
        print "<meta name=\"twitter:creator\" content=\"@AdrienBodin\">\n";
        print "<meta name=\"twitter:title\" content=\"$user_firstname $user_lastname | SomeDevs\">\n";
        print "<meta name=\"twitter:description\" content=\"Retrouvez moi sur SomeDevs.\">\n";
        print "<meta name=\"twitter:image\" content=\"https://padx.fr/public/avatars/$user_picture\">\n";
      }
      break;
    }
  }

  print "<link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"/images/apple-touch-icon.png\">\n";
  print "<link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"/images/favicon-32x32.png\">\n";
  print "<link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"/images/favicon-16x16.png\">\n";
  print "<link rel=\"manifest\" href=\"/images/site.webmanifest\">\n";
  print "<link rel=\"stylesheet\" href=\"https://www.w3schools.com/w3css/4/w3.css\">\n";
  print "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>\n";
  print "<script src=\"https://code.jquery.com/ui/1.12.1/jquery-ui.js\"></script>\n";
  print "<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>\n";
  print "<link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css\">\n";

  print "<link rel=\"stylesheet\" href=\"/style.css\" media=\"screen\" type=\"text/css\" />\n";
  print "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\">\n";
switch ($page) // Balises OG
{
    case "profil":
    {
        print "<title>$user_firstname $user_lastname | SomeDevs</title>\n";
    }
}
    print "<title>$page | SomeDevs</title>\n";

  print "</head>\n";

  print "<body>\n";

  // Entête/Menu
  print "<div id=\"navbar\" class=\"w3-top\">\n";
    print "<div class=\"w3-bar w3-card  \">\n";
      print "<a href=\"/\" id=\"navbar_logo\" class=\"w3-bar-item w3-button w3-hover-none \"><img src=\"/images/logo4.png\" alt=\"SomeDevslogo\" style=\"height:40px;\"></a>\n";
      // Barre de recherche
      print "<form id=\"form_friends_list\" action=\"/page/search\" method=\"post\">\n";
            @$q = clean_field($_POST["q"]);
            if ($uid)
            {
                if ($q) //Enregistrer la recherche
                {
                    sqlconnect();
                    $sqli = "insert into app_search set"
                        ." search_user_id = \"$uid\""
                        .", search_text = \"$q\"";

                    sqlexec($sqli);
                    sqldisconnect();
                }
            }
            print "<div id=\"navbar_container_search\" class=\"w3-bar-item w3-hide-small  container_search_friends\">\n";
                print "<input type=\"text\" name=\"q\" value=\"$q\" id=\"searchbar_friends\" class=\" w3-bar-item w3-dropdown-hover  w3-input w3-round\" placeholder=\"&#xf002; Rechercher sur SomeDevs\" onkeyup=\"filtername()\">";
                print "<button type=\"submit\" id=\"searchicon_navbar\" name=\"btnsearch\"><i class=\"fa fa-search\"></i></button>\n";
            print "</div>\n";
      print "</form>\n";

        // Fin Barre de recherche
  if (!$uid)
  {
      print"<div id=\"navbar_connexion\" class=\"w3-bar-item w3-right w3-btn w3-hover-none \">\n";
        print "<a href=\"/page/register\" class=\"w3-bar-item w3-btn w3-hover-none\">Inscription</a>\n";
      print "</div>\n";
      print"<div id=\"navbar_connexion\" class=\"w3-bar-item w3-right w3-btn w3-hover-none \">\n";
        print "<a href=\"/page/login\"  class=\"w3-bar-item w3-btn w3-hover-none\">Connexion</a>\n";
      print "</div>\n";
  }
  if ($uid)
  {
    print"<div class=\"w3-right\">\n";
      print"<div id=\"navbar_icon_picture\" class=\"w3-bar-item w3-btn w3-hide-medium w3-hide-small w3-hover-none \">\n";
        // Affichage avatar
        if ($uid)
        {
          sqlconnect();
          $user_picture = sqlone("select user_picture from app_user"
              ." where user_id = \"$uid\""
          );
          sqldisconnect();
        }
        if (!empty($user_picture))
        {
            print "<a href=\"/page/profil/$uid\"><img id=\"\" class=\" w3-circle\" style=\"height:40px;\" src=\"/public/avatars/$user_picture\" alt=\"Avatar\"></a>";
        }
        else
        {
            print "<a href=\"/page/profil/$uid\"><img id=\"\" class=\"w3-circle\" style=\"height:40px;\"  src=\"/public/avatars/defaults/Default-avatar.jpg\" alt=\"Avatar\"></a>";  //Si pas d'avatar, ont en affiche un manuellement.
        }
        //Fin affichage avatar
      print "</div>\n";
      print"<div id=\"navbar_icon\" class=\"w3-bar-item w3-btn w3-hide-small w3-hover-none \">\n";
        print "<a href=\"/\"><img src=\"/images/home.png\" id=\"navbar_picture\" class=\"w3-bar-item \" style=\"height:40px;\" alt=\"iconHome\"></a>\n";
      print "</div>\n";
      print"<div id=\"navbar_icon\" class=\"w3-bar-item w3-btn w3-hover-none \">\n";
        print "<a href=\"/page/friends\"><img src=\"/images/friends.png\" id=\"navbar_picture\" class=\"w3-bar-item \" style=\"height:40px;\" alt=\"IconFriends\"></a>\n";
      print "</div>\n";
      /*
      print"<div id=\"navbar_icon\" class=\"w3-bar-item w3-btn w3-hover-none w3-disabled\">\n";
        print "<img src=\"/images/conversation.png\" id=\"navbar_picture\" class=\"w3-bar-item\" style=\"height:40px;\" alt=\"IconMessager\">\n";
      print "</div>\n";
      print"<div id=\"navbar_icon\" class=\"w3-bar-item w3-btn  w3-hover-none w3-disabled\">\n";
        print "<img src=\"/images/notification.png\" id=\"navbar_picture\" class=\"w3-bar-item\" style=\"height:40px;\" alt=\"IconNotification\">\n";
      print "</div>\n";
      */
      print "<div  class=\"w3-dropdown-hover\">\n";
        print "<button id=\"navbar_icon\" class=\"w3-bar-item w3-btn w3-hover-none \">\n";
          print "<img src=\"/images/menu2.png\" id=\"navbar_picture\" class=\"w3-bar-item\" style=\"height:40px;\" alt=\"IconMenu\">\n";
        print "</button>";
        print "<div id=\"sdemo\" class=\"w3-dropdown-content w3-bar-block w3-black\" style=\"top: 65px; right:0px;\">";
          print "<a href=\"/page/account\" class=\"w3-bar-item w3-btn w3-padding-large w3-hover-none w3-border-black w3-bottombar w3-hover-border-blue w3-hover-text-blue\">Mon Compte</a>\n";
          print "<a href=\"/?logout=1\" class=\"w3-bar-item w3-btn w3-padding-large w3-hover-none w3-border-black w3-bottombar w3-hover-border-red w3-hover-text-red\">Se déconnecter</a>";
        print "</div>";
      print "</div>";
    print "</div>";
  }

  print "</div>\n";
  print "</div>\n";

  // Page
  print "<div id=\"Container_page\" class=\"w3-container w3-text-white\" >\n";
  if (file_exists("$page.php"))
  {
    include "$page.php";
  }
  else
  {
    print "<p class=\"w3-display-middle w3-xxlarge\">Oups ! La page que vous avez demandé n'existe pas</p>";
  }
  print "</div>\n";

  // Pied
  /*print "<div class=\"w3-bottom w3-right\">\n";
  print "  <div class=\"w3-bar w3-black w3-card\">\n";
  print "    <a href=\"\" class=\"w3-bar-item w3-button w3-padding-small\">$email</a>\n";
  print "  </div>\n";
  print "</div>\n";
*/
  print "</body>\n";
  print "</html>\n";

//Script pour le filtre de recherche lettre par lettre.
?>