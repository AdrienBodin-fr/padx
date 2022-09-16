<?php
   require_once("script_date.js");

    //Début du Ajouter/Modifier détails
    print "<div id=\"Profil_card\" class=\"w3-card-4\"> ";
        // Titre
        print "<div id=\"profil_container_posttitle\" class=\"w3-container\"> ";
            print "<div class=\"\"> ";
                print "<a href=\"/page/$page/$id/bio\"><img class=\"Starting_post_icon\" src=\"/images/arrow.png\" style=\"height:30px;\"  alt=\"icon back\"></a>";
            print " </div> ";
            print "<div class=\"w3-container\"> ";
                print " <h2 id=\"\">Ajouter / Modifier Détails</h2> ";
            print " </div> ";
        print "</div> ";
        // Contenu
        print "<div id=\"friends_container_list\" class=\"w3-container\">";
            print "<div class=\"w3-container w3-left-align w3-padding\"> ";
                print "<div class=\"w3-left-align\"> ";
                    print " <h4 id=\"\">Détails</h4> ";
                print "</div> ";
                print "<form id=\"form_friends_list\" action=\"/page/$page\" method=\"post\">\n";

                    print "<label>Job</label>";
                    print "<input type=\"text\" name=\"input_job\" id=\"input_job\""
                        ." value=\"$user_job\" class=\"w3-input\">\n";
                    print "<br/>\n";

                    print "<label>Etudes</label>";
                    print "<input type=\"text\" name=\"input_studies\" id=\"input_studies\""
                        ." value=\"$user_studies\" class=\"w3-input\">\n";
                    print "<br/>\n";

                    print "<label>Anniversaire</label>";
                    print "<input type=\"text\" name=\"input_birthday\" id=\"input_birthday\""
                        ." value=\"$user_birthday\" class=\"w3-input datepicker\">\n";
                    print "<br/>\n";

                    print "<label>Sexe</label>";
                    print "<br/>\n";
                    //print "<select class=\"w3-select\" name=\"input_gender\" id=\"input_gender\">";
                    // print "<option value=\"\" disabled selected>Choisir un genre</option>";
                    $genders = [
                      ["F", "Féminin"]
                      ,["M", "Masculin"]
                    ];
                    print radio_array("input_gender", $genders, $user_gender);
                    /*
                    print "<option value=\"Femme\" >Féminin</option>";
                    print "<option value=\"Homme\" >Masculin</option>";
                    */
                    /*
                    print "<option value=\"Non Binaire\" >Non Binaire</option>";
                    print "<option value=\"Gender Queer\" >Gender Queer</option>";
                    print "<option value=\"Gender Fluid\" >Gender Fluid</option>";
                    */
                    //print "$user_gender";
                    //print "</select>";
                    print "<br/>\n";
                    print "<br/>\n";

                    print "<label>Téléphone</label>";
                    print "<input type=\"tel\" name=\"input_phonenumber\" id=\"input_phonenumber\""
                        ." value=\"$user_phonenumber\" class=\"w3-input\""
                        //." pattern=\"[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{2}\""
                        .">\n";
                    print "<br/>\n";

                    print "<label>Liens</label>";
                    print "<input type=\"text\" name=\"input_link\" id=\"input_link\""
                        ." value=\"$user_link\" class=\"w3-input\">\n";
                    print "<br/>\n";

                    print "<button type=\"submit\" name=\"btndetails\" value=\"1\""
                        ." class=\"w3-btn w3-pink\">\n";
                    print "Modifier";
                    print "</button>\n";
                print "</form>\n";
            print "</div>";
        print "</div>";
    print "</div>";// Fin du bloc
