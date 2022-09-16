<?php
    sqlconnect();

    @$id = clean_field($_REQUEST["id"]); // Récupération ID dans l'URL
    if ($id)
    {
        $sql = "select *"
            ." from app_article"
            ." where article_id = \"$id\"";
        $rs = sqlexec($sql);
        if ($row = sqlrow($rs))
        {
            $article_id = $row["article_id"];
            $article_author_id = $row["article_author_id"];
            $article_text = $row["article_text"];
            $article_text = nl2br($article_text);
            $article_text = activate_links($article_text);
            $article_picture = $row["article_picture"];
            $article_datetime = $row["article_datetime"];
            $article_age = time_ago($article_datetime);
        }
        else
        {
            $id = 0;
        }
    }

    // modifier une publication
    @$btnpost = htmlspecialchars($_POST["btnpost"]);
    if ($btnpost)
    {
        $error = "";

        @$input_posttext = htmlspecialchars($_POST["input_posttext"]);
        @$input_postpicture = htmlspecialchars($_FILES["input_postpicture"]);

        // Ajout image
        if(isset($_FILES['input_postpicture']) && !empty($_FILES['input_postpicture']['name']))
        {
            $filename = $_FILES['input_postpicture']['tmp_name'];

            list($width_orig, $height_orig) = getimagesize($filename);

            if ($width_orig >= 100 && $height_orig >= 100 && $width_orig <= 6000 && $height_orig <= 6000)
            {
                $listeExtension = array('jpg' => 'image/jpg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
                $listeExtensionIE = array('jpg' => 'image/pjpg', 'jpeg' => 'image/pjpeg');
                $tailleMax = 2097152; //Taille maximum 2 Mo
                // 3 Mo = 3145728
                // 4 Mo = 4194304
                // 5 Mo = 5242880
                // 7 Mo = 7340032
                // 10 Mo = 10485760
                // 12 Mo = 12582912
                $extensionsValides = array('jpg', 'jpeg'); // Format accepté

                if ($_FILES['input_postpicture']['size'] <= $tailleMax)
                { // Si le fichier est plus petit que $tailleMax
                    $extensionUpload = strtolower(substr(strrchr($_FILES['input_postpicture']['name'], '.'), 1)); // Prend l'extension après le point, soit "jpg, jpeg, png ou gif"
                    if (in_array($extensionUpload, $extensionsValides)) // Vérifie que l'extension soit validé dans $extensionsValides
                    {
                        $nom = md5(uniqid(rand(), true)); // Genere un nom unique à la photo

                        // Supprimer l'ancien user_picture
                        $article_picture = sqlone("select article_picture from app_article"
                            ." where article_id = \"$btnpost\""
                        );
                        if ($article_picture)
                        {
                            unlink("public/articles/$article_picture");
                        }

                        $chemin = "public/articles" /* . $_SESSION['user_id']*/ . "/" . $nom . "." . $extensionUpload; // Chemin pour placer la photo
                        $deplacement = move_uploaded_file($_FILES['input_postpicture']['tmp_name'], $chemin); // On fini par mettre la photo dans le dossier
                        if ($deplacement)
                        { // Si le deplacement est fait
                            $article_picture = "$nom" ."." . $extensionUpload;
                        }
                    }
                    else
                    {
                        $error = "Format d'image incorrect";
                    }
                }
                else
                {
                    $error = "Fichier trop lourd";
                }
            }
            else
            {
                $error = "Dimensions d'image incorrectes";
            }
        }

        // Test validité des champs de Modifier Profil

        // Si pas d'erreurs Update
        if (!$error)
        {
            $sqlu = "update app_article set\n"
                ." article_text = \"$input_posttext\""
                .(@$article_picture?", article_picture = \"$article_picture\"\n":"")
                ." where article_id = \"$btnpost\"\n"
            ;
            sqlexec($sqlu);
            file_put_contents("_sql.log", $sqlu);
           redirect("/");
        }
    }
        $sql = "select * from app_user U"// Requete affichage des articles
            ." left join app_article A on A.article_user_id = U.user_id"
            ." where article_id = \"$id\""
        ;
        $rs = sqlexec($sql);
        while ($row = sqlrow($rs))
        {
            $user_id = $row["user_id"];
            $user_picture = $row["user_picture"];
            $user_firstname = $row["user_firstname"];
            $user_lastname = $row["user_lastname"];
            $user_pseudo = $row["user_pseudo"];

        }
            print "<div id=\"Profil_card\" class=\"w3-card-4\"> ";
                // Titre
                print "<div id=\"profil_container_posttitle\" class=\"w3-container\"> ";
                    print "<div class=\"\"> ";
                        print "<a href=\"/\"><img class=\"Starting_post_icon\" src=\"/images/arrow.png\" style=\"height:30px;\"  alt=\"icon back\"></a>";
                    print " </div> ";
                    print "<div class=\"w3-container\"> ";
                        print "<h2 id=\"\">Modifier une publication</h2>";
                    print " </div> ";
                print "</div> ";
                // Contenus
                print "<div id=\"friends_container_list\" class=\"w3-container\">";
                    print "<div id=\"Starting_post_infos\" class=\"w3-container \"> ";
                        //affichage avatar
                        if (!empty($user_picture)/* && isset($_SESSION['user_picture'])*/)
                        {
                            print "<img src=\"/public/avatars/$user_picture\" id=\"Starting_post_avatar\" class=\" w3-circle w3-hover-none\" style=\"height:60px;\"  alt=\"Avatar\"></a>";
                        }
                        else
                        {
                            print "<img src=\"/public/avatars/defaults/Default-avatar.jpg\" id=\"Starting_post_avatar\" class=\" w3-circle w3-hover-none\" style=\"height:60px;\" alt=\"Avatar\"></a>";  //Si pas d'avatar, ont en affiche un manuellement.
                        }
                        //Fin affichage avatar
                            print "<div  class=\"feed_container_infos_justify_name\">\n";
                                print " <p>$user_firstname $user_lastname</p> ";
                            print "</div>";
                        print "</div>";
                    print "<form id=\"post_form\" action=\"/page/$page\" method=\"post\" enctype=\"multipart/form-data\">\n";
                        if ($error)
                        {
                            print "<div class=\"w3-red\">\n";
                            print "$error";
                            print "</div>\n";
                        }
                        print "<textarea cols=\"30\" rows=\"10\" name=\"input_posttext\" id=\"input_posttext\""
                            ." class=\"w3-input\" placeholder=\"Quoi de neuf ?\">";
                        print "$article_text</textarea>\n";
                        print "<br/>\n";

                        print "<input id=\"input_postpicture\" type=\"file\" name=\"input_postpicture\" id=\"input_postpicture\""
                            ." value=\"$article_picture\" class=\"w3-input w3-small \">\n";
                        print "<br/>\n";

                        print "<button type=\"submit\" name=\"btnpost\" value=\"$article_id\""
                            ." class=\"w3-btn w3-pink\">\n";
                        print "Modifier";
                        print "</button>\n";
                    print "</form>\n";
                print "</div>";
            print "</div>";// Fin du bloc
    sqldisconnect();
?>
<script>
    $(function(){
        $("#input_posttext").focus();
    });
</script>
