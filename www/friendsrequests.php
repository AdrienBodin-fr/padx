<?php
    sqlconnect();

    @$btnacceptfriend = clean_field($_POST["btnacceptfriend"]);
    @$btndeletefriend = clean_field($_POST["btndeletefriend"]);

    // Traitements
    $friend_id = sqlone("select friend_id from app_friend"
        ." where friend_user_id = \"$btnacceptfriend\""
        ." and friend_fuser_id = \"$uid\""
    );
    if ($btnacceptfriend)
    {
        //print "Ajout du user $btnaddfriend en ami";
        // update dans la table app_friend
        $error = "";
        if (!$error)
        {
            $is_user = sqlone("select friend_id"
                ." from app_friend"
                ." where friend_user_id = \"$uid\""
                ." and friend_fuser_id != \"$btnacceptfriend\""
            );
            if ($is_user)
            {
                $error = "Vous êtes déjà amis";
            }
        }
        if (!$error)
        {
            $sqlu = "update app_friend set"
                ." friend_fuser_id = \"$btnacceptfriend\""
                .", friend_user_id = \"$uid\""
                .", friend_pending = \"0\""
                ." where friend_id = \"$friend_id\"";

            sqlexec($sqlu);
            redirect("/page/friends");
        }
    }

    if ($btndeletefriend)
    {
        //print "Suppression du user $btndeletefriend en ami";
        // Delete dans la table app_friend
        $sqld = "DELETE
                     FROM app_friend
                     WHERE friend_fuser_id = \"$btndeletefriend\"";
        sqlexec($sqld);
        redirect("/page/friends");
    }


//Début du bloc friends
print "<div id=\"Profil_card\" class=\"w3-card-4 w3-margin\"> ";
    // Menu
    print "<div id=\"friends_container_menu\" class=\"w3-container\"> ";
        print "<div class=\"w3-container w3-left-align\"> ";
            print " <h2 id=\"\">Amis</h2> ";
        print " </div> ";
        print "<div class=\"w3-container w3-left-align\"> ";
            print "<a href=\"/page/friends\"><button id=\"card_friends_buttons_invit\" class=\"w3-btn w3-round-xxlarge w3-hover-light-gray w3-blue-gray\">Retour</button></a>";
        print " </div> ";
    print " </div> ";
    print "<form id=\"form_friends_list\" action=\"/page/$page\" method=\"post\">\n";
    @$q = clean_field($_POST["q"]);
    print "<div id=\"friends_container_list\" class=\"w3-container\">";
        // Liste Vos amis
        print "<div class=\"w3-container w3-left-align\"> ";
            print "<h3 id=\"\">Invitations :</h3>";
        print "</div>";
        print "<div class=\"container_cards\">";
            $sql = "select * from app_user"
                ." where user_id != \"$uid\"";
            $rs = sqlexec($sql);

            foreach ($rs as $row)
            {
                $user_id = $row["user_id"];
                $user_picture = $row["user_picture"];
                $user_firstname = $row["user_firstname"];
                $user_lastname = $row["user_lastname"];
                $user_pseudo = $row["user_pseudo"];
                $user_city = $row["user_city"];

                // Rechercher si invitation ou pas
                $friend_pending = sqlone("select friend_pending from app_friend"
                    ." where friend_user_id = \"$user_id\""
                    ." and friend_fuser_id = \"$uid\""
                );

                if ($friend_pending)
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
                                print "<div class=\"card_friends_buttons\">";
                                    print "<button id=\"card_friends_buttons_add\" type=\"submit\" name=\"btnacceptfriend\" value=\"$user_id\""
                                                ." class=\"w3-button w3-round w3-hover-pale-red w3-pink\">Accepter</button>";
                                    print "<button class=\"w3-button w3-round w3-hover-light-gray w3-blue-gray\">Refuser</button>";
                                print "</div>";
                            print "</div>";
                        print "</div>";
                        //Fin Cards friends
                    }// Fin du if Friends_pending
            }//Fin du Foreach
                /*if (!$rs_friend_pending)
                {
                    print "<div class=\"w3-container w3-left-align\"> ";
                        print "<p>Vous n'avez pas d'invitations</p>";
                    print "</div> ";
                }*/
        print "</div>";
//Fin du If friends
print "</form>\n";
print "</div>";// Fin du bloc

sqldisconnect();

/*$friend_pending = sqlone("select friend_pending from app_friend"
    ." where friend_user_id = \"$uid\""
    ." and friend_fuser_id = \"$user_id\""
);*/
?>
