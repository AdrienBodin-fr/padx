<?php
    sqlconnect();

    @$btnaddfriend = clean_field($_POST["btnaddfriend"]);
    @$btndeletefriend = clean_field($_POST["btndeletefriend"]);

    // Traitements
    if ($btnaddfriend)
    {
        // Insert dans la table app_friend
        $error = "";
        if (!$error)
        {
            $is_user = sqlone("select friend_id"
                ." from app_friend"
                ." where friend_user_id = \"$uid\""
                ." and friend_fuser_id = \"$btnaddfriend\""
            );
            if ($is_user)
            {
                $error = "Vous êtes déjà amis";
            }
        }
        if (!$error)
        {
            $sqli = "insert into app_friend set"
                ."  friend_fuser_id = \"$btnaddfriend\""
                .", friend_user_id = \"$uid\""
                .", friend_pending = \"1\""
                ;
            sqlexec($sqli);
            redirect("/page/friends");
        }
    }

    if ($btndeletefriend)
    {
        //print "Suppression du user $btndeletefriend en ami";
        // Delete dans la table app_friend
        $sqld = "DELETE friend_id
                 FROM app_friend
                 WHERE friend_fuser_id = \"$btndeletefriend\"
                 AND friend_user_id = \"$uid\"";
        sqlexec($sqld);
        redirect("/page/friends");
}


    //Début du bloc friends
    print "<div id=\"Profil_card\" class=\"w3-card-4 w3-margin =\"> ";
            // Menu
            print "<div id=\"friends_container_menu\" class=\"w3-container\"> ";
                print "<div class=\"w3-container w3-left-align\"> ";
                    print " <h2 id=\"\">Amis</h2> ";
                print " </div> ";
                print "<div class=\"w3-container w3-left-align\"> ";
                    print "<a href=\"/page/friendsrequests\"><button id=\"card_friends_buttons_invit\" class=\"w3-btn w3-round-xxlarge w3-hover-light-gray w3-blue-gray\">Invitations</button></a>";
                    print "<a href=\"/page/friendslist\"><button id=\"card_friends_buttons_yourfriends\" class=\"w3-btn w3-round-xxlarge w3-hover-light-gray w3-blue-gray\">Vos amis</button></a>";
                print " </div> ";
            print " </div> ";
            print "<form id=\"form_friends_list\" action=\"/page/$page\" method=\"post\">\n";
            @$q = clean_field($_POST["q"]);
            // Vous connaissez peut-être
            print "<div id=\"friends_container_list\" class=\"w3-container\">";
                print "<div id=\"navbar_container_search\" class=\"container_search_friends\">\n";
                        print "<input type=\"text\" name=\"q\" value=\"$q\" id=\"searchbar_friends\" class=\" w3-bar-item w3-input w3-round\" placeholder=\"&#xf002; Rechercher sur SomeDevs\">";
                        print "<button type=\"submit\" id=\"searchicon_friends\" name=\"btnsearch\"><i class=\"fa fa-search\"></i></button>\n";
                    $sql = "select * from app_user"
                        ." where user_id != \"$uid\""
                        .($q?" and ("
                            ."user_firstname like \"%$q%\""
                            ." or user_lastname like \"%$q%\""
                            ." or user_pseudo like \"%$q%\""
                            .")"
                            :"")
                    ;
                    $rs = sqlexec($sql);
                    if ($uid)
                    {
                        if ($q) //Enregistrer la recherche
                        {
                            $sqli = "insert into app_search set"
                                ." search_user_id = \"$uid\""
                                .", search_text = \"$q\"";

                            sqlexec($sqli);
                        }
                    }
                print "</div>\n";
                print "<div class=\"w3-container w3-left-align\"> ";
                    print "<h3 id=\"\">Vous connaissez peut-être</h3>";
                print "</div>";
                print "<div class=\"container_cards\">";

               // Affichages des cards
                foreach ($rs as $row)
                {
                    $user_id = $row["user_id"];
                    $user_picture = $row["user_picture"];
                    $user_firstname = $row["user_firstname"];
                    $user_lastname = $row["user_lastname"];
                    $user_pseudo = $row["user_pseudo"];
                    $user_city = $row["user_city"];
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
                                print "<a href=\"/page/profil/$user_id\"><h5>$user_firstname $user_lastname</h5></a>";
                                print "<a href=\"/page/profil/$user_id\"><h6>@$user_pseudo</h6></a>";
                            print "</div>";
                            print "<div class=\"card_friends_buttons\">";
                                // Rechercher si ami ou pas
                                $friend_id = sqlone("select friend_id from app_friend"
                                    ." where friend_user_id = \"$uid\""
                                    ." and friend_fuser_id = \"$user_id\""
                                );
                                $friend_id_bis = sqlone("select friend_id from app_friend"
                                    ." where friend_user_id = \"$user_id\""
                                    ." and friend_fuser_id = \"$uid\""
                                );
                                if ($friend_id or $friend_id_bis)
                                {
                                    print "<button type=\"submit\" name=\"btnaddfriend\" value=\"$user_id\""
                                        ." id=\"card_friends_buttons_add\" class=\"w3-button w3-disabled w3-round w3-hover-pale-red w3-pink btnpos\">Ajouter en ami</button>";
                                    print "<button class=\"w3-button w3-round  w3-blue-gray btnpos\">Supprimer</button>";
                                }
                                else
                                {
                                    print "<button type=\"submit\" name=\"btnaddfriend\" value=\"$user_id\""
                                        ." id=\"card_friends_buttons_add\" class=\"w3-button w3-round w3-pink btnpos\">Ajouter en ami</button>";
                                    print "<button type=\"submit\" name=\"btndeletefriend\" value=\"$user_id\""
                                        ." class=\"w3-button w3-disabled w3-round w3-hover-light-gray w3-blue-gray btnpos\">Supprimer</button>";
                                }
                            print "</div>";
                        print "</div>";
                    print "</div>";
                    //Fin Cards friends
                }
                print "</div>";
            print "</div>";
        print "</form>\n";
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
