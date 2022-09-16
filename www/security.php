<?php
  require_once("ReCaptcha/autoload.php");
  $URL = "";

  global $uid;
  global $email;
  global $sup;
  global $page;
  global $error;

  // Récupération des cookies "précédents"
  @$email = $_COOKIE["email"];
  @$passwd = $_COOKIE["passwd"];

  @$btnlogout = htmlspecialchars($_POST["btnlogout"].$_GET["logout"]);
  if ($btnlogout)
  {
    $email = "";
    $passwd = "";
    setcookie("email", "", time()+3600*24*365*10, "/", "");
    setcookie("passwd", "", time()+3600*24*365*10, "/", "");
  }

  // Connexion
  @$btnlogin = htmlspecialchars($_POST["btnlogin"]);
  if ($btnlogin)
  {
    @$input_login = htmlspecialchars($_POST["input_login"]);
    @$input_passwd = htmlspecialchars($_POST["input_passwd"]);
    @$email = $input_login;
    @$passwd = md5($input_passwd);
    setcookie("email", "$email", time()+3600*24*365*10, "/", "");
    setcookie("passwd", "$passwd", time()+3600*24*365*10, "/", "");
  }

  // Inscription
  @$btnregister = htmlspecialchars($_POST["btnregister"]);
  if ($btnregister)
  {
    sqlconnect();
    $error = false;

    @$input_firstname = htmlspecialchars($_POST["input_firstname"]);
    @$input_lastname = htmlspecialchars($_POST["input_lastname"]);
    @$input_pseudo = htmlspecialchars($_POST["input_pseudo"]);
    @$input_login = htmlspecialchars($_POST["input_login"]);
    @$input_passwd = htmlspecialchars($_POST["input_passwd"]);


    if ((!$error)&&(!$input_firstname))
    {
      $error = "Veuillez renseigner votre prénom";
    }

    if((!$error)&&(!preg_match("/^([a-zA-Z' ]+)$/",$input_firstname)))
    {
        $error = "Le prénom n'est pas valide";
    }


      if ((!$error)&&(strlen($input_firstname)>30))
    {
      $error = "Le Prénom est trop long, il doit contenir entre 2 et 30 caractères";
    }

    if ((!$error)&&(strlen($input_firstname)<02))
    {
      $error = "Le Prénnom est trop court, il doit contenir entre 2 et 30 caractères";
    }

    if ((!$error)&&(!$input_lastname))
    {
      $error = "Veuillez renseigner votre nom";
    }

    if((!$error)&&(!preg_match("/^([a-zA-Z' ]+)$/",$input_lastname)))
    {
        $error = "Le nom n'est pas valide";
    }

    if ((!$error)&&(strlen($input_lastname)>30))
    {
      $error = "Le Nom est trop long, il doit contenir entre 2 et 30 caractères";
    }

    if ((!$error)&&(strlen($input_lastname)<02))
    {
      $error = "Le Nom est trop court, il doit contenir entre 2 et 30 caractères";
    }

    if ((!$error)&&(!$input_pseudo))
    {
      $error = "Veuillez renseigner votre pseudo";
    }

    if((!$error)&&(!preg_match("/^([a-zA-Z' ]+)$/",$input_pseudo)))
    {
        $error = "Le pseudo n'est pas valide";
    }

    if ((!$error)&&(strlen($input_pseudo)>20))
    {
      $error = "Le Pseudo est trop long, il doit contenir entre 2 et 20 caractères";
    }

    if ((!$error)&&(strlen($input_pseudo)<02))
    {
      $error = "Le Pseudo est trop court, il doit contenir entre 2 et 20 caractères";
    }

    if (!$error)
    {
      $is_user = sqlone("select user_id"
          ." from app_user"
          ." where user_pseudo = \"$input_pseudo\""
          ." and user_id != \"$uid\""
      );
      if ($is_user)
      {
          $error = "Pseudo déjà utilisé";
      }
    }

    if ((!$error)&&(!$input_login))
    {
      $error = "Le champ E-mail est vide";
    }

    if ((!$error)&&(strlen($input_login)>50))
    {
      $error = "L'EMail est trop long, il dépasse 50 caractères";
    }

    if ((!$error)&&(!$input_passwd))
    {
      $error = "Le champ Mot de passe est vide";
    }

    if ((!$error)&&(strlen($input_passwd)>30))
    {
      $error = "Le mot de passe est trop long, il doit contenir entre 6 et 30 caractères";
    }

    if ((!$error)&&(strlen($input_passwd)>06))
    {
      $error = "Le mot de passe est trop court, il doit contenir entre 6 et 30 caractères";
    }


    if (!$error)
    {
      $is_user = sqlone("select user_id"
        ." from app_user"
        ." where user_email = \"$input_login\""
      );
      if ($is_user)
      {
        $error = "Adresse EMail déjà inscrite";
      }
    }

    if ((!$error)&&(filter_var($input_login, FILTER_VALIDATE_EMAIL)))
    {
      $error = "L'Email n'est pas valide";
    }

    //Vérification Captcha
    $recaptcha = new \ReCaptcha\ReCaptcha('6LeKDK8fAAAAAFZiU5zRBVvEoo_BaEYOCF1GQp7t');
    $resp = $recaptcha->verify($_POST["g-recaptcha-response"]);
    if (!$resp->isSuccess())
    {
      $error = "Le captcha n'est pas valide.";
    }

    if (!$error)
    {
      $sqli = "insert into app_user set"
        ."  user_firstname = \"$input_firstname\"\n"
        .", user_lastname = \"$input_lastname\"\n"
        .", user_pseudo = \"$input_pseudo\"\n"
        .", user_email = \"$input_login\""
        .", user_passwd = \"".md5($input_passwd)."\""
        .", user_firstdate = now()"
      ;
      sqlexec($sqli);
      $uid = sqlid();
      $passwd = md5($input_passwd);
      setcookie("email", "$input_login", time()+3600*24*365*10, "/", "");
      setcookie("passwd", $passwd, time()+3600*24*365*10, "/", "");
      //exit;
      redirect("/page/account");
    }
    sqldisconnect();
  }

  // Test validité
  if (($email)&&($passwd))
  {
    sqlconnect();
    $sql = "select user_id"
      ." from app_user"
      ." where user_email = \"$email\""
      ."   and user_passwd = \"$passwd\""
    ;
    $uid = sqlone($sql);
    if ($uid)
    {
      $sup = sqlone("select user_super from app_user"
        ." where user_id = \"$uid\""
      );
      $sqlu = "update app_user set"
        ." user_lastdate = now()"
        .", user_lastaddr = \"".($_SERVER["REMOTE_ADDR"])."\"";
      sqlexec($sqlu);
      if ($btnlogin)
      {
        redirect("/");
      }
    }
    else
    {
      $uid = "";
      $passwd = "";
      $email = "";
      setcookie("email", "", time()+3600*24*365*10, "/", "");
      setcookie("passwd", "", time()+3600*24*365*10, "/", "");
      $error = "Membre inconnu";
    }
    sqldisconnect();
  }
  else
  {
    $uid = "";
    $email = "Non connecté";
  }

  // Modification profil
  @$btnaccount = htmlspecialchars($_POST["btnaccount"]);
  if ($btnaccount)
  {
    sqlconnect();
    $error = "";

    @$input_picture = htmlspecialchars($_FILES["input_picture"]);
    @$input_banner = htmlspecialchars($_FILES["input_banner"]);
    @$input_firstname = htmlspecialchars($_POST["input_firstname"]);
    @$input_lastname = htmlspecialchars($_POST["input_lastname"]);
    @$input_pseudo = htmlspecialchars($_POST["input_pseudo"]);
    @$input_departement = htmlspecialchars($_POST["input_departement"]);
    @$input_city = htmlspecialchars($_POST["input_city"]);
    @$input_country = htmlspecialchars($_POST["input_country"]);
    @$input_bio = htmlspecialchars($_POST["input_bio"]);
    @$input_passwd = htmlspecialchars($_POST["input_passwd"]);

    // Ajout photo de profil
    if(isset($_FILES['input_picture']) && !empty($_FILES['input_picture']['name']))
    {
      $filename = $_FILES['input_picture']['tmp_name'];

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

        if ($_FILES['input_picture']['size'] <= $tailleMax)
        { // Si le fichier est plus petit que $tailleMax
          $extensionUpload = strtolower(substr(strrchr($_FILES['input_picture']['name'], '.'), 1)); // Prend l'extension après le point, soit "jpg, jpeg, png ou gif"
          if (in_array($extensionUpload, $extensionsValides)) // Vérifie que l'extension soit validé dans $extensionsValides
          {
            $nom = md5(uniqid(rand(), true)); // Genere un nom unique à la photo

            // Supprimer l'ancien user_picture
            $user_picture = sqlone("select user_picture from app_user"
              ." where user_id = \"$uid\""
            );
            if ($user_picture)
            {
              unlink("public/avatars/$user_picture");
            }

            $chemin = "public/avatars/" /* . $_SESSION['user_id']*/ . "/" . $nom . "." . $extensionUpload; // Chemin pour placer la photo
            $deplacement = move_uploaded_file($_FILES['input_picture']['tmp_name'], $chemin); // On fini par mettre la photo dans le dossier
            if ($deplacement)
            { // Si le deplacement est fait
              $user_picture = "$nom" ."." . $extensionUpload;
              ResizeImageCenterCrop("public/avatars/$user_picture", 600, 600);
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

      // Ajout bannière
      if(isset($_FILES['input_banner']) && !empty($_FILES['input_banner']['name']))
      {
          $filename = $_FILES['input_banner']['tmp_name'];

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

              if ($_FILES['input_banner']['size'] <= $tailleMax)
              { // Si le fichier est plus petit que $tailleMax
                  $extensionUpload = strtolower(substr(strrchr($_FILES['input_banner']['name'], '.'), 1)); // Prend l'extension après le point, soit "jpg, jpeg, png ou gif"
                  if (in_array($extensionUpload, $extensionsValides)) // Vérifie que l'extension soit validé dans $extensionsValides
                  {
                      $nom = md5(uniqid(rand(), true)); // Genere un nom unique à la photo

                      // Supprimer l'ancien user_picture
                      $user_banner = sqlone("select user_banner from app_user"
                          ." where user_id = \"$uid\""
                      );
                      if ($user_banner)
                      {
                          unlink("public/banners/$user_banner");
                      }

                      $chemin = "public/banners/" /* . $_SESSION['user_id']*/ . "/" . $nom . "." . $extensionUpload; // Chemin pour placer la photo
                      $deplacement = move_uploaded_file($_FILES['input_banner']['tmp_name'], $chemin); // On fini par mettre la photo dans le dossier
                      if ($deplacement)
                      { // Si le deplacement est fait
                          $user_banner = "$nom" ."." . $extensionUpload;
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

        if ((!$error)&&(!$input_firstname))
        {
          $error = "Veuillez renseigner votre prénom";
        }

        if((!$error)&&(!preg_match("/^([a-zA-Z' ]+)$/",$input_firstname)))
        {
          $error = "Le prénom n'est pas valide";
        }


        if ((!$error)&&(strlen($input_firstname)>30))
        {
          $error = "Le Prénom est trop long, il doit contenir entre 2 et 30 caractères";
        }

        if ((!$error)&&(strlen($input_firstname)<02))
        {
          $error = "Le Prénnom est trop court, il doit contenir entre 2 et 30 caractères";
        }

        if ((!$error)&&(!$input_lastname))
        {
          $error = "Veuillez renseigner votre nom";
        }

        if((!$error)&&(!preg_match("/^([a-zA-Z' ]+)$/",$input_lastname)))
        {
          $error = "Le nom n'est pas valide";
        }

        if ((!$error)&&(strlen($input_lastname)>30))
        {
          $error = "Le Nom est trop long, il doit contenir entre 2 et 30 caractères";
        }

        if ((!$error)&&(strlen($input_lastname)<02))
        {
          $error = "Le Nom est trop court, il doit contenir entre 2 et 30 caractères";
        }

        if ((!$error)&&(!$input_pseudo))
        {
          $error = "Veuillez renseigner votre pseudo";
        }

        if((!$error)&&(!preg_match("/^([a-zA-Z' ]+)$/",$input_pseudo)))
        {
          $error = "Le pseudo n'est pas valide";
        }

        if ((!$error)&&(strlen($input_pseudo)>20))
        {
          $error = "Le Pseudo est trop long, il doit contenir entre 2 et 20 caractères";
        }

        if ((!$error)&&(strlen($input_pseudo)<02))
        {
          $error = "Le Pseudo est trop court, il doit contenir entre 2 et 20 caractères";
        }

        if (!$error)
        {
          $is_user = sqlone("select user_id"
              ." from app_user"
              ." where user_pseudo = \"$input_pseudo\""
              ." and user_id != \"$uid\""
          );
          if ($is_user)
          {
              $error = "Pseudo déjà utilisé";
          }
        }

    // Si pas d'erreurs Update

    if (!$error)
    {
      $sqlu = "update app_user set\n"
        ." user_firstname = \"$input_firstname\"\n"
        .", user_lastname = \"$input_lastname\"\n"
        .", user_pseudo = \"$input_pseudo\"\n"
        .", user_departement = \"$input_departement\"\n"
        .", user_city = \"$input_city\"\n"
        .", user_country = \"$input_country\"\n"
        .", user_bio = \"$input_bio\"\n"
        //.", user_passwd = \"".md5($input_passwd)."\""
        .(@$user_picture?", user_picture = \"$user_picture\"\n":"") // Seulement si $user_picture, sinon rien
        .(@$user_banner?", user_banner = \"$user_banner\"\n":"") // Seulement si $user_banner, sinon rien
        ." where user_id = \"$uid\"\n"
      ;
      sqlexec($sqlu);
      file_put_contents("_sql.log", $sqlu);
      redirect("/page/account");
    }

    sqldisconnect();
  }

  //Delete de la photo
  /*  @$btndeletepicture = htmlspecialchars($_POST["btndeletepicture"]);

    if ($btndeletepicture)
    {
        sqlconnect();

        $user_picture = sqlone("select user_picture from app_user"
            ." where user_id = \"$uid\"");

        $sqld = "DELETE user_picture
                 FROM app_user
                 WHERE user_picture = \"$user_picture\"";

        sqlexec($sqld);
        redirect("/page/account");
        sqldisconnect();
    }
*/

  // Modification Mot de passe
  @$btnmdpaccount = htmlspecialchars($_POST["btnmdpaccount"]);
  if ($btnmdpaccount)
  {
      sqlconnect();
      $error = "";
      $mdpvalidate = "";

      @$input_passwd = htmlspecialchars($_POST["input_passwd"]);

      // Test validité des champs de Modifier Profil
      if ((!$error)&&(strlen($input_passwd)>30))
      {
          $error = "Le mot de passe est trop long, il doit contenir entre 6 et 30 caractères";
      }

      if ((!$error)&&(strlen($input_passwd)<06))
      {
          $error = "Le mot de passe est trop court, il doit contenir entre 6 et 30 caractères";
      }
      // Si pas d'erreurs Update

      if (!$error)
      {
              $sqlu = "update app_user set\n"
                  //.", user_passwd = \"".md5($input_passwd)."\""
                  .(@$input_passwd?", user_passwd = \"".md5($input_passwd)."\"\n":"") // Seulement si $input_passwd, sinon rien
                  ." where user_id = \"$uid\"\n"
              ;
              sqlexec($sqlu);
              file_put_contents("_sql.log", $sqlu);
              redirect("/page/account");
              $mdpvalidate = "Le mot de passe à bien été changer !";
      }
      sqldisconnect();
  }


  // Modification Details
  @$btndetails = htmlspecialchars($_POST["btndetails"]);
  if ($btndetails)
  {
      sqlconnect();
      $error = "";

      @$input_job = htmlspecialchars($_POST["input_job"]);
      @$input_studies = htmlspecialchars($_POST["input_studies"]);
      @$input_birthday = htmlspecialchars($_POST["input_birthday"]);
      $input_birthday = fd2md($input_birthday);
      @$input_gender = htmlspecialchars($_POST["input_gender"]);
      @$input_phonenumber = htmlspecialchars($_POST["input_phonenumber"]);
      @$input_link = htmlspecialchars($_POST["input_link"]);


      // Test validité des champs de Modifier Profil

      // Si pas d'erreurs Update

      if (!$error)
      {

          $sqlu = "update app_user set\n"
              ." user_job = \"$input_job\"\n"
              .", user_studies = \"$input_studies\"\n"
              .", user_birthday = \"$input_birthday\"\n"
              .", user_gender = \"$input_gender\"\n"
              .", user_phonenumber = \"$input_phonenumber\"\n"
              .", user_link = \"$input_link\"\n"
              ." where user_id = \"$uid\"\n"
          ;
          sqlexec($sqlu);
          file_put_contents("_sql.log", $sqlu);
          //redirect("/page/$page/$id/bio");
      }
      sqldisconnect();
  }
?>