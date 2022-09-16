<?php
  require_once("functions_date.php");

    if ($id)
    {

        $sql = "select * from app_user"
            ." where user_id = \"$id\"";
        $rs = sqlexec($sql);
        if ($row = sqlrow($rs))
        {
            $user_id = $row["user_id"];
            $user_firstname = $row["user_firstname"];
            $user_lastname = $row["user_lastname"];
            $user_pseudo = $row["user_pseudo"];
            $user_gender = $row["user_gender"];
            $user_phonenumber = $row["user_phonenumber"];
            $user_departement = $row["user_departement"];
            $user_city = $row["user_city"];
            $user_country = $row["user_country"];
            $user_bio = $row["user_bio"];
            $user_picture = $row["user_picture"];
            $user_job = $row["user_job"];
            $user_studies = $row["user_studies"];
            $user_link = $row["user_link"];
            $user_birthday = md2fd($row["user_birthday"]);
            $user_firstdate = $row["user_firstdate"];
        }
    }
    else
    {
        $user_firstname = "";
        $user_lastname = "";
        $user_pseudo = "";
        $user_gender = "";
        $user_phonenumber = "";
        $user_departement = "";
        $user_city = "";
        $user_country = "";
        $user_bio = "";
        $user_picture = "";
        $user_job = "";
        $user_studies = "";
        $user_link = "";
        $user_birthday = "";
        $user_firstdate = "";
    }

//Début du bloc friends
    if (($mode=="modifier")&&($uid==$id))
    {
      include "profilbiomodify.php";
    }
    else
    {
      print "<div id=\"Profil_card\" class=\"w3-card-4\"> ";
          // Titre
          print "<div id=\"profil_container_biotitle\" class=\" w3-bar w3-container\"> ";
              print "<div class=\"w3-container w3-left\"> ";
                  print " <h2 id=\"\">À propos</h2> ";
              print " </div> ";
              if ($id == $uid)
              {
                  print "<div class=\"w3-container w3-right\"> ";
                      print "<a  class=\"".($mode=="modifier"?"active":"")."\" href=\"/page/$page/$id/bio/modifier\"><img class=\"Profil_card_icon\" src=\"/images/edit-button.png\" style=\"height:40px;\"  alt=\"icon edit\"></a>";
                  print " </div> ";
              }
          print "</div> ";
          // Liste Vos amis
          print "<div id=\"friends_container_list\" class=\"w3-container\">";
              print "<div class=\"w3-container w3-left-align w3-padding\"> ";
                  print "<div class=\"w3-left-align\"> ";
                      print " <h4 id=\"\">Bio</h4> ";
                  print "</div> ";
                  print "<p id=\"Profil_bio\">$user_bio</p>";
              print "</div>";
              print "<div class=\"w3-container w3-left-align w3-padding\"> ";
                  print "<div class=\"w3-left-align\"> ";
                      print " <h4 id=\"\">Détails</h4> ";
                  print "</div> ";

                  if (!empty($user_job))
                  {
                      print "<p id=\"Profil_bio_detail\"><img class=\"Profil_card_icon\" src=\"/images/suitcase.png\"  alt=\"icon location\">Travaille chez $user_job</p>";
                  }
                  if (!empty($user_studies))
                  {
                      print "<p id=\"Profil_bio_detail\"><img class=\"Profil_card_icon\" src=\"/images/graduation-hat.png\"  alt=\"icon location\">A étudié à $user_studies</p>";
                  }

                  if (!empty($user_city))
                  {
                      print "<p id=\"Profil_bio_detail\"><img class=\"Profil_card_icon\" src=\"/images/maison-silhouette-noire-sans-porte.png\"  alt=\"icon location\">";
                      print "Habite à $user_city, $user_departement, $user_country";
                      print "</p> ";
                  }


                  if (!empty($user_birthday))
                  {
                      print "<p id=\"Profil_bio_detail\"><img class=\"Profil_card_icon\" src=\"/images/birthday-cake.png\"  alt=\"icon location\">$user_birthday</p> ";
                  }
                  if (!empty($user_gender))
                  {
                      print "<p id=\"Profil_bio_detail\"><img class=\"Profil_card_icon\" src=\"/images/transgender.png\"  alt=\"icon location\">";
                      switch ($user_gender)
                      {
                        case "F":
                        {
                          print "Féminin";
                          break;
                        }
                        case "M":
                        {
                          print "Masculin";
                          break;
                        }
                      }
                      print "</p> ";
                  }
                  if (!empty($user_phonenumber))
                  {
                      print "<p id=\"Profil_bio_detail\"><img class=\"Profil_card_icon\" src=\"/images/phone-call.png\"  alt=\"icon phone\">$user_phonenumber</p> ";
                  }
                  if (!empty($user_link))
                  {
                      print "<p id=\"Profil_bio_detail\"><img class=\"Profil_card_icon\" src=\"/images/link.png\"  alt=\"icon link\"><a href=\"$user_link\">$user_link</a></p>";
                  }
              print "</div>";
          print "</div>";
      print "</div>";// Fin du bloc
    }
?>



