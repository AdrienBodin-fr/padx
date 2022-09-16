<?php

    if ($id == $uid ) // Privé
    {
        $nb_visit = sqlone("select count(distinct visit_visitor_id) from app_visit" //Compteur de visites
          ." where visit_user_id = \"$id\""
           ."  and visit_visitor_id != \"$id\""
        );

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
                    .", article_datetime = now()"
                ;
                sqlexec($sqli);
            }
        }

            //Début du bloc Statistiques
        print "<div id=\"Profil_card\" class=\"w3-card-4\"> ";
            // Titre
            print "<div id=\"profil_container_statstitle\" class=\"w3-container\"> ";
                print "<div id=\"\" class=\"w3-container w3-left-align\"> ";
                    print " <h2 id=\"\">Statistiques</h2> ";
                    print "<p id=\"Profil_stats\"><img class=\"Profil_card_icon\" src=\"/images/eye.png\"  alt=\"icon view\">Privé pour vous</p>";
                print " </div> ";
            print "</div> ";
            // Liste Vos amis
            print "<div id=\"friends_container_list\" class=\"w3-container\">";
                print "<div class=\"w3-container w3-left-align w3-padding\"> ";
                    print "<div class=\"w3-left-align\"> ";
                        print " <h4 id=\"Profil_stats\"><img class=\"Profil_card_icon\" src=\"/images/multiple-users-silhouette.png\"  alt=\"icon silhouette\">$nb_visit vue".($nb_visit>1?"s":"")." du profil</h4> ";
                    print "</div> ";
                  $sql = "select distinct user_id, user_picture from app_user U"// Requete affichage des visiteurs
                     ." left join app_visit V on V.visit_visitor_id = U.user_id"
                     ." where visit_user_id = \"$id\""
                     ."  and visit_visitor_id != \"$id\""
                     ." order by visit_date desc limit 10"
                  ;
                  $rs = sqlexec($sql);
                    while ($row = sqlrow($rs))
                    {
                      $user_id = $row["user_id"];
                      $user_picture = $row["user_picture"];
                      if (!empty($user_picture)/* && isset($_SESSION['user_picture'])*/)
                      {
                          print "<a href=\"/page/profil/$user_id\"><img src=\"/public/avatars/$user_picture\" id=\"\" class=\"w3-circle\" style=\"height:40px;\" alt=\"Avatar\"></a>";
                      }
                      else
                      {
                          print "<a href=\"/page/profil/$user_id\"><img id=\"\" class=\"w3-circle\" style=\"height:40px;\"  src=\"/public/avatars/defaults/Default-avatar.jpg\" alt=\"Avatar\"></a>";
                      }
                    }
                print "</div>";
                    /*
                print "<div class=\"w3-container w3-left-align w3-padding\"> ";
                    print "<p id=\"Profil_stats\"><img class=\"Profil_card_icon\" src=\"/images/statistiques.png\"  alt=\"icon statistiques\">nb_vues du post</p>";
                    print "<p id=\"Profil_stats\"><img class=\"Profil_card_icon\" src=\"/images/loupe.png\"  alt=\"icon loupe\">nb_vues apparitions dans les résultats de recherche</p>";
                print "</div>";
                    */
            print "</div>";
        print "</div>";// Fin du bloc
    } // Fin du Privé

    include "pagination.php";

    //Début d'affichages des publications'
        @$id = clean_field($_REQUEST["id"]); // Récupération ID dans l'URL
        if ($id)
        {
            $sql = "select * from app_article A"// Requete affichage des articles
                ." left join app_user U on A.article_user_id = U.user_id"
                ." Where article_user_id = \"$id\""
                ." order by article_datetime desc limit 10"
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
                $user_picture = $row["user_picture"];
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
        }
    print "</div>";// Fin du bloc

