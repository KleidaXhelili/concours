<?php

require_once('inc/init.php');

$title = '| Accueil';
$accueil = 'active';
require_once('inc/header.php');


/***************************************POST***********************************/


if( !empty($_POST)){

    $erreurs = array();

    $champsvides = 0;
    foreach($_POST as $valeur){
        if(trim($valeur) == '' ) {
            $champsvides++;
        }
    }

    if(empty($_FILES['photo']['name']) && empty($_POST['photo_actuelle']) ){
    $champsvides++;
    }

    if($champsvides > 0){
        $erreurs[] = "Il manque $champsvides information(s)";
    }

    if(empty($erreurs)) {

        if( !empty($_FILES['photo']['name'])){
            if( $_FILES['photo']['size'] > 0 && in_array ($_FILES['photo']['type'], array('image/jpeg', 'image/png', 'image/gif')) 
            ){
                $dossier = __DIR__ . '/img/';
                $fichier = uniqid(). '_' . $_FILES['photo']['name'];
                move_uploaded_file($_FILES['photo']['tmp_name'], $dossier. $fichier);          
            
            }
            else{
                $erreurs[] = 'Fichier vide ou de format incorrect. (Format autorisés : jpeg, png, gif)';
            }
        }    

    
        if(empty($erreurs)){

            $_POST['photo'] = $fichier ?? $_POST['photo_actuelle'];            

            if(!empty($_GET['id'])){

                unset($_POST['photo_actuelle']);
                $_POST['id'] = $_GET['id'];
                $requete = "UPDATE candidats SET nom = :nom,prenom = :prenom,age = :age,photo = :photo,pays = :pays,bio = :bio WHERE id_candidat = :id";

            }else{

                $requete = "INSERT INTO candidats VALUES(NULL, :nom, :prenom, :age, :photo, :pays, :bio)";
            }           
            
            executeRequete($requete, $_POST);
            header('location:' . $_SERVER['PHP_SELF']);
            exit();
        }    
    }
}


/***************************************SUPPRESSION*********************************/ 

if(isset($_GET['action']) && $_GET['action'] == 'del' && !empty($_GET['id']) && is_numeric($_GET['id'])){

    $resultats = executeRequete("SELECT photo FROM candidats WHERE id_candidat=:id", array('id'=>$_GET['id']));
    $candidat = $resultats->fetch();

    $fichier = __DIR__ . '/../img/' . $candidat['photo'];

    if(file_exists($fichier)){
        unlink($fichier); 
    }

    executeRequete("DELETE FROM candidats WHERE id_candidat=:id", array('id'=>$_GET['id']));
    header('location:' .$_SERVER['PHP_SELF']);
    exit();
}


/*****************************************UPDATE*************************************/

if(isset($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id']) && is_numeric($_GET['id'])){

    $edit = executeRequete("SELECT * FROM candidats WHERE id_candidat = :id", array('id' => $_GET['id']));

    if($edit->rowCount() == 1){

        $candidat = $edit->fetch();
    }
}




?>

<div class="container pt-5 pb-5">
    <div class="row">
        <h2 class="text-center">INSCRIPTION</h2>        
            <?php if (!empty($erreurs)) : ?>
                <div class="alert alert-danger"><?= implode('<br>', $erreurs) ?></div>
            <?php endif ?>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" value="<?= $_POST['nom'] ?? $candidat['nom'] ?? ''?>" placeholder="Saisissez ici">
            </div>
            <div class="form-group col-md-3">
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom" value="<?= $_POST['prenom'] ?? $candidat['prenom'] ?? ''?>" placeholder="Saisissez ici">
            </div>
            <div class="form-group col-md-3">
                <label for="age">Age</label>
                <input type="number" name="age" id="age" value="<?= $_POST['age'] ?? $candidat['age'] ?? ''?>" placeholder="Saisissez ici">
            </div>
            <div class="form-group col-md-3">
                <label for="pays">Pays</label>
                <input type="text" name="pays" id="pays" value="<?= $_POST['pays'] ?? $candidat['pays'] ?? ''?>" placeholder="Saisissez ici">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="bio">Bio</label><br>
                <textarea name="bio" id="bio" cols="30" rows="8"><?= $_POST['bio'] ?? $candidat['bio'] ?? ''?></textarea>
            </div>
            <div class="form-group col-md-6">
                <label for="profession">Envoyez-nous votre photo</label><br>
                <input type="file" class="form-control" name="photo" id="photo" placeholder="Saisissez ici">
                <?php
                    if(isset($candidat['photo'])) : 
                    ?>
                    <input type="hidden" name="photo_actuelle" value="<?= $candidat['photo'] ?>">
                    <img src="<?= URL .'img/' . $candidat['photo'] ?>" alt="" class="img-fluid my-3">
                    <?php
                    endif;
                ?>
            </div>
        </div>

        <input type="submit" value="S'inscrire" class="btn btn-danger float-right">

    </form>    
</div>

<hr>
<div class="table table-bordered table-striped table-responsive-md pt-5">
            <h2>CANDIDATS</h2>
            <table>
                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Bio</th>
                    <th colspan="3">Actions</th>
                </tr>

                <?php
                $resultats = executeRequete("SELECT * FROM candidats");
                while($candidat = $resultats->fetch()) :
                ?>
                <tr>
                    <td><?= $candidat['id_candidat'] ?></td>                    
                    <td><?= $candidat['nom'] ?></td>                    
                    <td><?= $candidat['prenom'] ?></td>                    
                    <td><?= substr($candidat['bio'], 0, 50) . "..."?></td>
                    <td><a href="?action=del&id=<?= $candidat['id_candidat'] ?>" class="confirm"> &#x1F5D1; </a></td>                    
                    <td><a href="?action=edit&id=<?= $candidat['id_candidat'] ?>">&#x270F;</a></td>                    
                    <td><a href="fiche_candidat.php?&id=<?= $candidat['id_candidat'] ?>">">&#x1F50D;</a></td>                    
                </tr>
                <?php
                endwhile;
                ?>
                
            </table>

        </div>

<?php

require_once('inc/footer.php');

?>