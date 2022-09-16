<?php
  if ($uid)
  {
    print "<div id=\"account_form\" class=\"w3-container\">\n";
        print "<form action=\"/page/$page\" method=\"post\" enctype=\"multipart/form-data\">\n";

        print " <h1>Modifier mon profil</h1>\n";

        if ($error)
        {
          print "<div class=\"w3-red\">\n";
          print "$error";
          print "</div>\n";
        }

        @$btnaccount = htmlspecialchars($_POST["btnaccount"]);
        if ($btnaccount)
        {
          // Récupération des données saisies précédement
          @$user_picture = htmlspecialchars($_FILES["input_picture"]);
          @$user_banner = htmlspecialchars($_FILES["input_banner"]);
          @$user_firstname = htmlspecialchars($_POST["input_firstname"]);
          @$user_lastname = htmlspecialchars($_POST["input_lastname"]);
          @$user_pseudo = htmlspecialchars($_POST["input_pseudo"]);
          @$user_departement = htmlspecialchars($_POST["input_departement"]);
          @$user_city = htmlspecialchars($_POST["input_city"]);
          @$user_country = htmlspecialchars($_POST["input_country"]);
          @$user_bio = htmlspecialchars($_POST["input_bio"]);
          @$user_passwd = htmlspecialchars($_POST["input_passwd"]);
        }
        else
        {

          // Récupération des données de la base
          sqlconnect();
          $sql = "select * from app_user"
            ." where user_id = \"$uid\""
          ;
          $rs = sqlexec($sql);
          if ($row = sqlrow($rs))
          {
            $user_picture = $row["user_picture"];
            $user_banner = $row["user_banner"];
            $user_firstname = $row["user_firstname"];
            $user_lastname = $row["user_lastname"];
            $user_pseudo = $row["user_pseudo"];
            $user_departement = $row["user_departement"];
            $user_city = $row["user_city"];
            $user_country = $row["user_country"];
            $user_bio = $row["user_bio"];
            $user_passwd = $row["user_passwd"];
          }

        }



        //Affichage et choix photo de profil
        print "<div class=\"w3-container w3-center\">\n";
            //affichage avatar
            if (!empty($user_picture)/* && isset($_SESSION['user_picture'])*/)
            {
                print "<img id=\"Profil_card_picture\" class=\" w3-circle w3-center \" style=\"height:100px;\"  src=\"/public/avatars/$user_picture\" alt=\"Avatar\">";
            }
            else
            {
                print "<img id=\"Profil_card_picture\" class=\" w3-circle w3-center \" style=\"height:100px;\"  src=\"/public/avatars/defaults/Default-avatar.jpg\" alt=\"Avatar\">";  //Si pas d'avatar, ont en affiche un manuellement.
            }
            //Fin affichage avatar
          print "<div id=\"modify_profil_picture_buttons\" class=\"w3-container w3-center\">\n";
              print "<input id=\"input_choice_picture\" type=\"file\" name=\"input_picture\" id=\"input_picture\""
                  ." value=\"$user_picture\" class=\"w3-input w3-small \">\n";
              print "<button type=\"submit\" name=\"btndeletepicture\" value=\"1\""
                  ." class=\"w3-btn w3-small w3-red\">\n";
              print "Supprimer la photo";
          print "</button>\n";
          print "</div>\n";
        print "</div>\n";
          print "<br/>\n";

        // Différents Inputs du form

        print "<input id=\"input_choice_banner\" type=\"file\" name=\"input_banner\" id=\"input_banner\""
              ." value=\"$user_banner\" class=\"w3-input w3-small \">\n";
        print "<button type=\"submit\" name=\"btndeletebanner\" value=\"1\""
              ." class=\"w3-btn w3-small w3-red\">\n";
        print "Supprimer la bannière";
        print "</button>\n";
        print "<br/>\n";

        print "<p>Les champs avec <img id=\"asterisk\" src=\"/images/asterisk2.png\" alt=\"(obligatoire)\"> sont obligatoires.</p>\n";
        print "<br/>\n";


        print "<img id=\"asterisklabel\" src=\"/images/asterisk2.png\" alt=\"(obligatoire)\"><label>Prénom</label>";
        print "<input type=\"text\" name=\"input_firstname\" id=\"input_firstname\""
          ." value=\"$user_firstname\" class=\"w3-input\">\n";
        print "<br/>\n";

        print "<img id=\"asterisklabel\" src=\"/images/asterisk2.png\" alt=\"(obligatoire)\"><label>Nom</label>";
        print "<input type=\"text\" name=\"input_lastname\" id=\"input_lastname\""
          ." value=\"$user_lastname\" class=\"w3-input\">\n";
        print "<br/>\n";

          print "<img id=\"asterisklabel\" src=\"/images/asterisk2.png\" alt=\"(obligatoire)\"><label>Pseudo</label>";
          print "<input type=\"text\" name=\"input_pseudo\" id=\"input_pseudo\""
              ." value=\"$user_pseudo\" class=\"w3-input\">\n";
          print "<br/>\n";

          print "<label>Département</label>";
          print "<select class=\"w3-select\" name=\"input_departement\" id=\"input_departement\">";
          print "<option value=\"\" disabled selected>Choisir un département</option>";
          $deps = [];
          $deps[] = ["01", "01 Ain"];
          $deps[] = ["02", "02 Aisne"];
          $deps[] = ["03", "03 Allier"];
          $deps[] = ["04", "04 Alpes-de-Haute-Provence"];
          $deps[] = ["05", "05 Hautes-Alpes"];
          $deps[] = ["06", "06 Alpes-Maritimes"];
          $deps[] = ["07", "07 Ardèche"];
          $deps[] = ["08", "08 Ardennes"];
          $deps[] = ["09", "09 Ariège"];
          $deps[] = ["10", "10 Aube"];
          $deps[] = ["11", "11 Aude"];
          $deps[] = ["12", "12 Aveyron"];
          $deps[] = ["13", "13 Bouches-du-Rhône"];
          $deps[] = ["14", "14 Calvados"];
          $deps[] = ["15", "15 Cantal"];
          $deps[] = ["16", "16 Charente"];
          $deps[] = ["17", "17 Charente-Maritime"];
          $deps[] = ["18", "18 Cher"];
          $deps[] = ["19", "19 Corrèze"];
          $deps[] = ["21", "21 Côte-d'Or"];
          $deps[] = ["22", "22 Côtes-d'Armor"];
          $deps[] = ["23", "23 Creuse"];
          $deps[] = ["24", "24 Dordogne"];
          $deps[] = ["25", "25 Doubs"];
          $deps[] = ["26", "26 Drôme"];
          $deps[] = ["27", "27 Eure"];
          $deps[] = ["28", "28 Eure-et-Loir"];
          $deps[] = ["29", "29 Finistère"];
          $deps[] = ["2A", "2A Corse-du-Sud"];
          $deps[] = ["2B", "2B Haute-Corse"];
          $deps[] = ["30", "30 Gard"];
          $deps[] = ["31", "31 Haute-Garonne"];
          $deps[] = ["32", "32 Gers"];
          $deps[] = ["33", "33 Gironde"];
          $deps[] = ["34", "34 Hérault"];
          $deps[] = ["35", "35 Ille-et-Vilaine"];
          $deps[] = ["36", "36 Indre"];
          $deps[] = ["37", "37 Indre-et-Loire"];
          $deps[] = ["38", "38 Isère"];
          $deps[] = ["39", "39 Jura"];
          $deps[] = ["40", "40 Landes"];
          $deps[] = ["41", "41 Loir-et-Cher"];
          $deps[] = ["42", "42 Loire"];
          $deps[] = ["43", "43 Haute-Loire"];
          $deps[] = ["44", "44 Loire-Atlantique"];
          $deps[] = ["45", "45 Loiret"];
          $deps[] = ["46", "46 Lot"];
          $deps[] = ["47", "47 Lot-et-Garonne"];
          $deps[] = ["48", "48 Lozère"];
          $deps[] = ["49", "49 Maine-et-Loire"];
          $deps[] = ["50", "50 Manche"];
          $deps[] = ["51", "51 Marne"];
          $deps[] = ["52", "52 Haute-Marne"];
          $deps[] = ["53", "53 Mayenne"];
          $deps[] = ["54", "54 Meurthe-et-Moselle"];
          $deps[] = ["55", "55 Meuse"];
          $deps[] = ["56", "56 Morbihan"];
          $deps[] = ["57", "57 Moselle"];
          $deps[] = ["58", "58 Nièvre"];
          $deps[] = ["59", "59 Nord"];
          $deps[] = ["60", "60 Oise"];
          $deps[] = ["61", "61 Orne"];
          $deps[] = ["62", "62 Pas-de-Calais"];
          $deps[] = ["63", "63 Puy-de-Dôme"];
          $deps[] = ["64", "64 Pyrénées-Atlantiques"];
          $deps[] = ["65", "65 Hautes-Pyrénées"];
          $deps[] = ["66", "66 Pyrénées-Orientales"];
          $deps[] = ["67", "67 Bas-Rhin"];
          $deps[] = ["68", "68 Haut-Rhin"];
          $deps[] = ["69", "69 RhôneNote"];
          $deps[] = ["70", "70 Haute-Saône"];
          $deps[] = ["71", "71 Saône-et-Loire"];
          $deps[] = ["72", "72 Sarthe"];
          $deps[] = ["73", "73 Savoie"];
          $deps[] = ["74", "74 Haute-Savoie"];
          $deps[] = ["75", "75 Paris"];
          $deps[] = ["76", "76 Seine-Maritime"];
          $deps[] = ["77", "77 Seine-et-Marne"];
          $deps[] = ["78", "78 Yvelines"];
          $deps[] = ["79", "79 Deux-Sèvres"];
          $deps[] = ["80", "80 Somme"];
          $deps[] = ["81", "81 Tarn"];
          $deps[] = ["82", "82 Tarn-et-Garonne"];
          $deps[] = ["83", "83 Var"];
          $deps[] = ["84", "84 Vaucluse"];
          $deps[] = ["85", "85 Vendée"];
          $deps[] = ["86", "86 Vienne"];
          $deps[] = ["87", "87 Haute-Vienne"];
          $deps[] = ["88", "88 Vosges"];
          $deps[] = ["89", "89 Yonne"];
          $deps[] = ["90", "90 Territoire de Belfort"];
          $deps[] = ["91", "91 Essonne"];
          $deps[] = ["92", "92 Hauts-de-Seine"];
          $deps[] = ["93", "93 Seine-Saint-Denis"];
          $deps[] = ["94", "94 Val-de-Marne"];
          $deps[] = ["95", "95 Val-d'Oise"];
          $deps[] = ["971", "971 Guadeloupe"];
          $deps[] = ["972", "972 Martinique"];
          $deps[] = ["973", "973 Guyane"];
          $deps[] = ["974", "974 La Réunion"];
          $deps[] = ["976", "976 Mayotte"];
          print combo_array($deps, $user_departement);
          print "</select>";
          print "<br/>\n";

          print "<label>Ville</label>";
          print "<input type=\"text\" name=\"input_city\" id=\"input_city\""
              ." value=\"$user_city\" class=\"w3-input\">\n";
          print "<br/>\n";

          print "<label>Pays</label>";
          print "<input type=\"text\" name=\"input_country\" id=\"input_country\""
              ." value=\"$user_country\" class=\"w3-input\">\n";
          print "<br/>\n";

          print "<label>A propos de vous</label>";
          print "<textarea cols=\"30\" rows=\"10\" name=\"input_bio\" id=\"input_bio\""
              ." class=\"w3-input\">"
              ."$user_bio";
          print "</textarea>\n";
          print "<br/>\n";

          print "<button type=\"submit\" name=\"btnaccount\" value=\"1\""
              ." class=\"w3-btn w3-pink\">\n";
          print "Enregister";
          print "</button>\n";

        print "</form>\n";

      print "<div id=\"accountmdp_form\" class=\"\">\n";
          print "<form id=\"\" action=\"/page/$page\" method=\"post\" enctype=\"multipart/form-data\">\n";
          if ($error)
          {
              print "<div class=\"w3-red\">\n";
              print "$error";
              print "</div>\n";
          }
          /*if ($mdpvalidate)
          {
              print "<div class=\"w3-green\">\n";
              print "$mdpvalidate";
              print "</div>\n";
          }*/
              print "<input type=\"password\" name=\"input_passwd\" id=\"input_passwd\""
                  ." placeholder=\"&#xf023; Mot de passe\" class=\"w3-input\">\n";
              print "<br/>\n";

              print "<button type=\"submit\" name=\"btnmdpaccount\" value=\"1\""
                  ." class=\"w3-btn w3-pink btnpos\" onclick=\"return confirm('Confirmer ?')\">\n";
              print "Modifier le Mot de passe";
              print "</button>\n";
          print "</form>\n";
      print "</div>\n";
    print "</div>\n"; // container
      sqldisconnect();
  }
  else // Pas d'uid
  {
  }
?>

<script>
$(function(){
  $("#input_firstname").focus();
});
</script>

<script>
    $(document).ready(function(){
        const apiUrl = 'https://geo.api.gouv.fr/departements?nom=' ;
        const format = '&format=json' ;

        let departement = $('#input_departement'); let errorMessage = $('#error-message');

        $(departement).on('blur', function (){
            let code = $(this).val();
            console.log(code);
            let url = apiUrl+code+format;
            //console.log(code);

            fetch(url, {method: 'get'}).then(response => response.json()).then(results => {
                //console.log(results);
                if (results.length){
                    $.each(results, function (key, value){
                        console.log(value);
                        console.warn(value.nom);
                    });
                }
            }).catch(err => {
                console.log(err);
            });
        });
    });
</script>

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