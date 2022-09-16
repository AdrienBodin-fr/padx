<?php
    //Début du bloc friends
    print "<div id=\"Profil_card\" class=\"w3-card-4\"> ";
        // Titre
        print "<div id=\"profil_container_statstitle\" class=\"w3-container\"> ";
            print "<div   class=\"w3-container w3-left-align\"> ";
                print " <h2 id=\"\">Amis</h2> ";
            print " </div> ";
        print " </div> ";
            print "<form id=\"form_friends_list\" action=\"/page/$page/$id/friends\" method=\"post\">\n";
            @$q = clean_field($_POST["q"]);
                // Liste Vos amis
                print "<div id=\"friends_container_list\" class=\"w3-container\">";
                    print "<div id=\"navbar_container_search\" class=\"container_search_friends\">\n";
                        print "<input type=\"text\" name=\"q\" value=\"$q\" id=\"searchbar_friends\" class=\" w3-bar-item w3-input w3-round\" placeholder=\"&#xf002; Rechercher des amis\">";
                        print "<button type=\"submit\" id=\"searchicon_friends\" name=\"btnsearch\"><i class=\"fa fa-search\"></i></button>\n";
                        $sql = "select * from app_user"
                            ." where user_id != \"$id\""
                            .($q?" and ("
                                ."user_firstname like \"%$q%\""
                                ." or user_lastname like \"%$q%\""
                                ." or user_pseudo like \"%$q%\""
                                .")"
                                :"")
                        ;
                        $rs = sqlexec($sql);
                    print "</div>\n";
                    print "<div class=\"w3-container w3-left-align\"> ";
                        print "<h3 id=\"\">Amis de $user_firstname</h3>";
                    print "</div>";
                    print "<div class=\"container_cards\">";
                        foreach ($rs as $row)
                        {
                            $user_id = $row["user_id"];
                            $user_picture = $row["user_picture"];
                            $user_firstname = $row["user_firstname"];
                            $user_lastname = $row["user_lastname"];
                            $user_pseudo = $row["user_pseudo"];
                            $user_city = $row["user_city"];
                            // Rechercher si ami ou pas
                            $friend_id = sqlone("select friend_id from app_friend"
                                ." where friend_user_id = \"$id\""
                                ." and friend_fuser_id = \"$user_id\""
                                ." and friend_pending = \"0\""
                            );
                            $friend_id_bis = sqlone("select friend_id from app_friend"
                                ." where friend_user_id = \"$user_id\""
                                ." and friend_fuser_id = \"$id\""
                                ." and friend_pending = \"0\""
                            );
                            if ($friend_id or $friend_id_bis)
                            {
                                // Affichages des cards
                                // Cards friends
                                print "<div class=\"card_friends\">";
                                    print "<div class=\"container_image_cards_friends\">";
                                        //affichage avatar
                                        if (!empty($user_picture)/* && isset($_SESSION['user_picture'])*/)
                                        {
                                            print "<a href=\"/page/profil/$user_id\"><img src=\"/public/avatars/$user_picture\" id=\"\" class=\"image_cards_friends\" alt=\"Avatar\"></a>";
                                        }
                                        else
                                        {
                                            print "<a href=\"/page/profil/$user_id\"><img src=\"/public/avatars/defaults/Default-avatar.jpg\" id=\"\" class=\"image_cards_friends\" alt=\"Avatar\"></a>";  //Si pas d'avatar, ont en affiche un manuellement.
                                        }
                                        //Fin affichage avatar
                                    print "</div>";
                                    print "<div class=\"container_card_friends_details\">";
                                        print "<div class=\"card_friends_details\">";
                                            print "<a href=\"/page/view_profile?id=$user_id\"><h5>$user_firstname $user_lastname</h5></a>";
                                            print "<a href=\"/page/view_profile?id=$user_id\"><h6>@$user_pseudo</h6></a>";
                                        print "</div>";
                                    print "</div>";
                                print "</div>";//Fin Cards friends
                            }//Fin du If friends
                        }
            print "</form>\n";
    print "</div>";// Fin du bloc
?>