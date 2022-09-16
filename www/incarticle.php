<?php
print "<div id=\"feed_container\" class=\"w3-container\">";
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
            if ($uid == $article_author_id /*or $user_super == 1*/){ //Bouton suppression de l'article
                print "<div  class=\"w3-dropdown-hover feed_container_infos_icon\">\n";
                    print "<img class=\"feed_comments_icon\" src=\"/images/menupost.png\"  alt=\"icon menu\">";
                    print "<div id=\"sdemo\" class=\"w3-dropdown-content w3-bar-block w3-black\" style=\"top: 20px; right:0px;\">";
                        print "<a href=\"/page/postmodify/$article_id\" class=\"w3-btn w3-hover-none\">Modifier la publication</a>\n";
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
            print "<p>$article_text</p>";
            if (!empty($article_picture))
            {
                print "<img src=\"/public/articles/$article_picture\" id=\"feed_container_article_image\" class=\" w3-padding\" alt=\"Image Article\">";
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
        print "</div>";
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
                    print "<a href=\"/page/comments/$article_id?p=$p\"  value=\"$article_id\""
                        ." id=\"feed_comments_icon_container\" class=\"w3-btn w3-hover-none\"><img class=\"feed_comments_icon\""
                        ." src=\"/images/comment.png\"  alt=\"icon statistiques\">Commenter</a>";
                    print "<button type=\"submit\" name=\"btnshare\" value=\"$article_id\""
                        ." id=\"feed_comments_icon_container\" class=\"w3-btn w3-hover-none\" onclick=\"return confirm('Voulez vous partager cette publication ?');\"><img class=\"feed_comments_icon\""
                        ." src=\"/images/share.png\"  alt=\"icon statistiques\">Partager</button>";
                print "</form>\n";
            print "</div>";
    }
        // Affichage des deux premiers commentaires
        $sql2 = "select *"
            ." from app_comment"
            ." where comment_article_id = \"$article_id\"";
        $rs2 = sqlexec($sql2);
        if ($row2 = sqlrow($rs2))
        {
            $comment_id = $row2["comment_id"];
        }
        if (!empty($comment_id))
        {
                // Créer un commentaire
                @$btncomment = htmlspecialchars($_POST["btncomment"]);
                if ($btncomment)
                {
                    $error = "";

                    @$input_commenttext = htmlspecialchars($_POST["input_commenttext"]);

                    // Test validité des champs de Modifier Profil

                    // Si pas d'erreurs Update
                    if (!$error)
                    {
                        $sqli = "insert into app_comment set"
                            ."  comment_user_id = \"$uid\""
                            .", comment_article_id = \"$article_id\""
                            .", comment_author_id = \"$uid\""
                            .", comment_text = \"$input_commenttext\""
                            .", comment_datetime = now()"
                        ;
                        sqlexec($sqli);
                        file_put_contents("_sql.log", $sqli);
                        //redirect("/");
                    }
                }
                //bloc Commentaires
                // J'aime un commentaire
                @$btnlike = clean_field($_POST["btnlike"]);
                if ($btnlike)
                {
                    $error = "";
                    if (!$error)
                    {
                        $is_like = sqlone("select like_id"
                            ." from app_like"
                            ." where like_user_id = \"$uid\""
                            ." and like_comment_id = \"$btnlike\""
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
                            .", like_comment_id = \"$btnlike\""
                            .", like_datetime = now()"
                        ;
                        sqlexec($sqli);
                        //redirect("/page/$page");
                    }
                }
                // Je n'aime plus un commentaire
                @$btndislike = clean_field($_POST["btndislike"]);
                if ($btndislike)
                {
                    $sqld = "DELETE
                     FROM app_like
                     WHERE like_user_id = \"$uid\"
                     AND like_comment_id = \"$btndislike\"";
                    sqlexec($sqld);
                    //redirect("/page/$page");
                }

                // Delete un commentaire
                @$btndeletecomment = clean_field($_POST["btndeletecomment"]);
                if ($btndeletecomment)
                {
                    // Supprimer la photo de l'article si elle existe
                    // ***

                    $sqld = "DELETE
                FROM app_comment
                WHERE comment_author_id = \"$uid\"
                AND comment_id = \"$btndeletecomment\"";
                    sqlexec($sqld);

                    // Supprimer aussi tous les éléments liés à l'article
                    // ***
                }

                $sql3 = "select * from app_user U" // Requete affichage des Commentaires
                    ." left join app_comment C on C.comment_user_id = U.user_id"
                    ." where comment_article_id = \"$article_id\""
                    ." order by comment_datetime desc limit 2"
                ;
                $rs3 = sqlexec($sql3);
                while ($row3 = sqlrow($rs3))
                {
                    $user_id = $row3["user_id"];
                    $user_picture = $row3["user_picture"];
                    $user_firstname = $row3["user_firstname"];
                    $user_lastname = $row3["user_lastname"];
                    $user_pseudo = $row3["user_pseudo"];
                    $user_super = $row3["user_super"];
                    $comment_id = $row3["comment_id"];
                    $comment_author_id = $row3["comment_author_id"];
                    $comment_text = $row3["comment_text"];
                    $comment_text = nl2br($comment_text);
                    $comment_datetime = $row3["comment_datetime"];
                    $comment_age = time_ago($comment_datetime);
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
                                        print "</div>";
                                        if ($uid == $comment_author_id or $user_super == 1){ //Bouton suppression du commentaire
                                            print "<div  class=\"w3-dropdown-hover feed_container_infos_icon\">\n";
                                                print "<img class=\"feed_comments_icon\" src=\"/images/menupost.png\"  alt=\"icon menu\">";
                                                print "<div id=\"sdemo\" class=\"w3-dropdown-content w3-bar-block w3-black\" style=\"top: 20px; right:0px;\">";
                                                    //print "<a href=\"/page/postmodify/$article_id\" class=\"w3-btn w3-hover-none\">Modifier la publication</a>\n";
                                                    print "<form method=\"post\">\n";
                                                        print "<button type=\"submit\" name=\"btndeletecomment\" value=\"$comment_id\" id=\"feed_comments_icon_container\""
                                                            ." class=\"w3-btn w3-hover-none w3-bottombar w3-hover-border-red w3-hover-text-red btnpos\" onclick=\"return confirm('Confirmer ?');\">"
                                                            ." Supprimer</button>";
                                                    print "</form>\n";
                                                print "</div>";
                                            print "</div>";
                                        }
                                    print "</div>";
                            print "</div>";
                            print "<div id=\"feed_container_article\" class=\"w3-container w3-center \">";
                                print "<p>$comment_text</p>";
                                if (!empty($comment_picture))
                                {
                                    print "<img src=\"/public/articles/$comment_picture\" id=\"feed_container_article_image\" class=\" w3-padding\" alt=\"Image Article\">";
                                }
                            print "</div>";
                            print "<div id=\"feed_container_comments\" class=\"w3-container \">";
                                print " <p id=\"feed_container_infos_date\">. $comment_age</p> ";
                                if ($uid)
                                {
                                    print "<form id=\"\" method=\"post\">\n";
                                        // Rechercher si like ou pas
                                        $like_id = sqlone("select like_id from app_like"
                                            ." where like_user_id = \"$uid\""
                                            ." and like_comment_id = \"$comment_id\""
                                        );
                                        if ($like_id)
                                        {
                                            print "<button type=\"submit\" name=\"btndislike\" value=\"$comment_id\""
                                                ." id=\"feed_comments_icon_container\" class=\"w3-btn w3-hover-none btnpos\"><img class=\"feed_comments_icon\" src=\"/images/dislike.png\"  alt=\"icon statistiques\">Je n'aime plus</button>";
                                        }
                                        if (!$like_id)
                                        {
                                            print "<button type=\"submit\" name=\"btnlike\" value=\"$comment_id\""
                                                ." id=\"feed_comments_icon_container\" class=\"w3-btn w3-hover-none btnpos\"><img class=\"feed_comments_icon\" src=\"/images/like3.png\"  alt=\"icon statistiques\">J'aime</button>";
                                        }
                                        print "<button type=\"submit\" name=\"btncomments\" value=\"$comment_id\""
                                            ." id=\"feed_comments_icon_container\" class=\"w3-btn w3-hover-none w3-disabled\"><img class=\"feed_comments_icon\" src=\"/images/comment.png\"  alt=\"icon statistiques\">Répondre</button>";
                                    print "</form>\n";
                                }
                                print "<div id=\"feed_container_article_comments\" class=\"w3-container \">";
                                    $nb_like = sqlone("select count(*) from app_like" //Compteur de likes
                                        ." where like_comment_id = \"$comment_id\""
                                    );
                                    print"<p class=\" w3-left Profil_stats\"><img class=\"Profil_card_icon\" src=\"/images/like2.png\"  alt=\"icon statistiques\">$nb_like like".($nb_like>1?"s":"")."</p>";
                                print "</div>";
                            print "</div>";
                        print "</div>";
                        }
                if ($uid)
                {
                                // Fin du bloc commentaires
                    print "<form id=\"post_comment_form\"  method=\"post\" enctype=\"multipart/form-data\">\n";
                        if ($error)
                        {
                            print "<div class=\"w3-red\">\n";
                            print "$error";
                            print "</div>\n";
                        }

                        print "<textarea cols=\"30\" rows=\"10\" name=\"input_commenttext\" id=\"Starting_comment_create\""
                            ." class=\"w3-input\" placeholder=\"Votre commentaire...\">";
                        print "</textarea>\n";
                        print "<br/>\n";

                        print "<button type=\"submit\" name=\"btncomment\" value=\"$article_id\""
                            ." class=\"w3-btn w3-round-xxlarge w3-blue-gray w3-hover-light-gray btnpos\">\n";
                        print "Publier";
                        print "</button>\n";
                    print "</form>\n";
                }
            }
print "</div>";

?>