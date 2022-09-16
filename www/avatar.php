<?php
/*
if(isset($_FILES['file']) AND !empty($_FILES['file']['name'])) {
    $filename = $_FILES['file']['tmp_name'];

    list($width_orig, $height_orig) = getimagesize($filename);

    if($width_orig >= 200 && $height_orig >= 200 && $width_orig <= 6000 && $height_orig <= 6000) {

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

        if ($_FILES['file']['size'] <= $tailleMax ) { // Si le fichier est plus petit que $tailleMax
            $extensionUpload = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1)); // Prend l'extension après le point, soit "jpg, jpeg, png ou gif"
            if (in_array($extensionUpload, $extensionsValides)) // Vérifie que l'extension soit validé dans $extensionsValides
            {
                $dossier = "/public/avatars/" . $_SESSION['id'] . "/"; // On se place dans le dossier avatars de l'utilisateur connecté.
                if (!is_dir($dossier)) { // On vérifie : Si le dossier existe
                    mkdir($dossier); // S'il n'éxiste pas on le créer.
                }
                else {
                    if (file_exists("/public/avatars/" . $_SESSION['id'] . "/" . $_SESSION['user_picture'] && isset($_SESSION['user_picture']))){
                        unlink("/public/avatars/" . $_SESSION['id'] . "/" . $_SESSION['user_picture']);
                    }
                }
            }

            $nom = md5(uniqid(rand(), true)); // Genere un nom unique à la photo
            $chemin = "/public/avatars/" . $_SESSION['id'] . "/" .$nom ."." . $extensionUpload; // Chemin pour placer la photo
            $deplacement = move_uploaded_file($_FILES['file']['tmp_name'], $chemin); // On fini par mettre la photo dans le dossier

            if ($deplacement){ // Si le deplacement est fait alors on compresse l'image

            }

        }
        }
    }


    if($_FILES['avatar']['size'] <= $tailleMax) {
        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
        if(in_array($extensionUpload, $extensionsValides)) {
            $chemin = "avatar/".$_SESSION['id'].".".$extensionUpload;
            $deplacement = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
            if($deplacement) {
                $updateavatar = $bdd->prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
                $updateavatar->execute(array(
                    'avatar' => $_SESSION['id'].".".$extensionUpload,
                    'id' => $_SESSION['id']
                ));
                header('Location: profil.php?id='.$_SESSION['id']);
            } else {
                $error = "Erreur durant l'importation de votre photo de profil";
            }
        } else {
            $error = "Votre photo de profil doit être au format jpg, jpeg, gif ou png";
        }
    } else {
        $error = "Votre photo de profil ne doit pas dépasser 2Mo";
    }
}
*/

?>

