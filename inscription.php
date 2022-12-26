<?php
    include 'config.php';

     if(isset($_POST['Envoyer'])){

      $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
      $nom = mysqli_real_escape_string($conn, $_POST['nom']);
      $login = mysqli_real_escape_string($conn, $_POST['login']);
      $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
      $password = mysqli_real_escape_string($conn, md5($_POST['password']));
      $cpassword = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
      @$image = $_FILES['image']['name'];
      @$image_size = $_FILES['image']['size'];
      @$image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = 'uploaded_img/'.$image;

      $select = mysqli_query($conn, "SELECT * FROM `utilisateur` WHERE login = '$login' AND password = '$password'") or die('requette échouée');
        if(mysqli_num_rows($select) > 0){
           $message[] = 'Utilisateur déja existant';
        }else{
          if($password != $cpassword){
            $message[] = 'mots de passe non identiques';
          }
          elseif($image_size > 2000000){
            $message[] = 'La taille de l\'image est trop large!';
          }else{
            $insert = mysqli_query($conn, "INSERT INTO `utilisateur`(prenom, nom, login, password, image, user_type) VALUES('$nom', '$prenom', '$login', '$password', 
            '$image','$user_type')") or die('requette échouée');
            if($insert){
              move_uploaded_file($image_tmp_name, $image_folder);
              $message[] = 'Inscription validée!';
              header('location:connexion.php');
            }else{
              $message[] = 'Inscription échouée';
            }
          }
        }




     }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylle.css">
    <title>Inscription</title>
</head>
<body>
    
       <div class="form-container">
         <form action="" method="POST" enctype="multipart/form-data">
            <h3>S'inscrir maintenant</h3>
            <?php
            if(isset($message)){
              foreach($message as $message){
                   echo '<div class="message">'.$message.'</div>';}
                  
            }
                ?>

            <input type="text" name="prenom" placeholder="Entrer votre Prénom" class="box" required>
            <input type="text" name="nom" placeholder="Entrer votre Nom" class="box" required>
            <input type="text" name="login" placeholder="Entrer votre identifiant" class="box" required>
            <input type="password" name="password" placeholder="Entrer votre mot de passe" class="box" required>
            <input type="password" name="cpassword" placeholder="confirmer votre mot de passe" class="box" required>
            <label name="image">Votre photo de profile</label>
            <input type="file" accept="image/jpg, image/jpeg, image/png"  class="box" name="image">
            <select name="user_type" class="box">
                   <option value="user">Utilisateur</option>
                   <option value="admin">Administrateur</option>
            </select>
            <input type="submit" name="Envoyer" value="Envoyer" class="button">
            <p>Déja inscrit ? <a href="connexion.php">Se connecter maintenant</a></p>
          </form>
        </div>






</body>
</html>