<?php
print "<div id=\"feed_container\" class=\"w3-container\">";
    print "<div id=\"feed_container_infos\" class=\"w3-container \">";
        //affichage avatar
        if (!empty($user_picture))/* && isset($_SESSION['user_picture'])*/
        {
            print "<a href=\"/page/profil/$user_id\"><img src=\"/public/avatars/$user_picture\" id=\"Starting_post_avatar\" class=\" w3-circle w3-hover-none\" style=\"height:60px;\"  alt=\"Avatar\"></a>";
        }
        else
        {
            print "<a href=\"/page/profil/$user_id\"><img src=\"/public/avatars/defaults/Default-avatar.jpg\" id=\"Starting_post_avatar\" class=\" w3-circle w3-hover-none\" style=\"height:60px;\" alt=\"Avatar\"></a>";  //Si pas d'avatar, ont en affiche un manuellement.
        }
        //Fin affichage avatar
        print "<div  class=\"feed_container_infos_justify\">\n";
            print "<div  class=\"feed_container_infos_justify_name\">\n";
                print " <a href=\"/page/profil/$user_id\" id=\"feed_container_infos_name\">$user_firstname $user_lastname</a> ";
                print " <p id=\"feed_container_infos_pseudo\" class=\"w3-hide-small\">@$user_pseudo</p> ";
                print " <p id=\"feed_container_infos_date\">. $article_age</p> ";
            print "</div>";
            if ($uid == $article_author_id or $user_super == 1){ //Bouton suppression de l'article
                print "<div  class=\"w3-dropdown-hover feed_container_infos_icon\">\n";
                    print "<img class=\"feed_comments_icon\" src=\"/images/menupost.png\"  alt=\"icon menu\">";
                    print "<div id=\"sdemo\" class=\"w3-dropdown-content w3-bar-block w3-black\" style=\"top: 20px; right:0px;\">";
                        print "<form method=\"post\">\n";
                            print "<button type=\"submit\" name=\"btndeletearticle\" value=\"$article_id\" id=\"feed_comments_icon_container\""
                                ." class=\"w3-btn w3-hover-none w3-bottombar w3-hover-border-red w3-hover-text-red btnpos\" onclick=\"return confirm('Confirmer ?');\">"
                                ."Supprimer</button>";
                        print "</form>\n";
                    print "</div>";
                print "</div>";
            }
        print "</div>";
    print "</div>";
    print "<div id=\"feed_container_article\" class=\"w3-container w3-center \">";
        //Affichage de la publication partagé
        $sql4 = "select * from app_user U"// Requete affichage des articles
            ." left join app_article A on A.article_author_id = U.user_id"
            ." where article_author_id = U.user_id"
            ." And article_id = \"$article_share_id\""
        ;
        $rs4 = sqlexec($sql4);
        while ($row4 = sqlrow($rs4))
        {
            $user_id = $row4["user_id"];
            $user_picture = $row4["user_picture"];
            $user_firstname = $row4["user_firstname"];
            $user_lastname = $row4["user_lastname"];
            $user_pseudo = $row4["user_pseudo"];
            $user_super = $row4["user_super"];
            $article_id = $row4["article_id"];
            $article_author_id = $row4["article_author_id"];
            $article_text = $row4["article_text"];
            $article_text = nl2br($article_text);
            $article_text = activate_links($article_text);
            $article_picture = $row4["article_picture"];
            $article_share_id = $row4["article_share_id"];
            $article_datetime = $row4["article_datetime"];
            $article_age = time_ago($article_datetime);
            print "<div id=\"feed_container\" class=\"w3-container  w3-margin\">";
                print "<div id=\"feed_container_infos\" class=\"w3-container \">";
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
                    print "<div  class=\"feed_container_infos_justify\">\n";
                        print "<div  class=\"feed_container_infos_justify_name\">\n";
                            print " <a href=\"/page/profil/$user_id\" id=\"feed_container_infos_name\">$user_firstname $user_lastname</a> ";
                            print " <p id=\"feed_container_infos_pseudo\" class=\"w3-hide-small\">@$user_pseudo</p> ";
                            print " <p id=\"feed_container_infos_date\">. $article_age</p> ";
                        print "</div>";
                    print "</div>";
                print "</div>";
                print "<div id=\"feed_container_article\" class=\"w3-container w3-center \">";
                    print "<p>$article_text</p>";
                    if (!empty($article_picture))
                    {
                        print "<img src=\"/public/articles/$article_picture\" id=\"feed_container_article_image\" class=\" w3-padding\" alt=\"Image Article\">";
                    }
                print "</div>";
            print "</div>";//Fin affichage du partage
        }
        print "<div id=\"feed_container_article_comments\" class=\"w3-container \">";
            $nb_like = sqlone("select count(*) from app_like" //Compteur de likes
                ." where like_article_id = \"$article_id\""
            );
            $nb_comment = sqlone("select count(*) from app_comment" //Compteur de commentaires
                ." where comment_article_id = \"$article_id\""
            );
            $nb_share = sqlone("select count(*) from app_article" //Compteur de partages
                ." where article_share_id = \"$article_id\""
            );
            print"<p class=\" w3-left Profil_stats\"><img class=\"Profil_card_icon\" src=\"/images/like2.png\"  alt=\"icon statistiques\">$nb_like like".($nb_like>1?"s":"")."</p>";
            print "<div id=\"feed_container_article_comments2\" class=\"w3-container\">";
                print"<p class=\"Profil_stats\">$nb_comment Commentaire".($nb_comment>1?"s":"")."</p>";
                print"<p class=\"Profil_stats\">. $nb_share Partage".($nb_share>1?"s":"")."</p>";
            print "</div>";
        print "</div>";
    print "</div>"; // Fin du container Article
    if ($uid)
    {
        print "<div id=\"feed_container_comments\" class=\"w3-container \">";
            print "<form id=\"\" method=\"post\">\n";
                // Rechercher si like ou pas
                $like_id = sqlone("select like_id from app_like"
                    ." where like_user_id = \"$uid\""
                    ." and like_article_id = \"$article_id\""
                );
                if ($like_id)
                {
                    print "<button type=\"submit\" name=\"btndislike\" value=\"$article_id\""
                        ." id=\"feed_comments_icon_container\" class=\"w3-btn w3-hover-none btnpos\"><img class=\"feed_comments_icon\" src=\"/images/dislike.png\"  alt=\"icon statistiques\">Je n'aime plus</button>";
                }
                if (!$like_id)
                {
                    print "<button type=\"submit\" name=\"btnlike\" value=\"$article_id\""
                        ." id=\"feed_comments_icon_container\" class=\"w3-btn w3-hover-none btnpos\"><img class=\"feed_comments_icon\" src=\"/images/like3.png\"  alt=\"icon statistiques\">J'aime</button>";
                }
                print "<a href=\"/page/comments/$article_id\"  value=\"$article_id\""
                    ." id=\"feed_comments_icon_container\" class=\"w3-btn w3-hover-none\"><img class=\"feed_comments_icon\""
                    ." src=\"/images/comment.png\"  alt=\"icon statistiques\">Commenter</a>";
                print "<button type=\"submit\" name=\"btnshare\" value=\"$article_id\""
                    ." id=\"feed_comments_icon_container\" class=\"w3-btn w3-hover-none\" onclick=\"return confirm('Voulez vous partager cette publication ?');\"><img class=\"feed_comments_icon\""
                    ." src=\"/images/share.png\"  alt=\"icon statistiques\">Partager</button>";
            print "</form>\n";
        print "</div>";
    }
print "</div>";

?>