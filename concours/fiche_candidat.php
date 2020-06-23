<?php

require_once('inc/init.php');
require_once('inc/header.php');


if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $requete = "SELECT * FROM candidats WHERE id_candidat=:id";
    $resultat = executeRequete($requete, array('id' => $_GET['id']));
    if($resultat -> rowCount() > 0){
        $candidat = $resultat->fetch();
    }
}

?>


<a href="concours.php" class="btn btn-danger my-3">Retour</a>
<h2>Fiche Candidat</h2>    
    <div class="row">
        <div class="col-md-4">
            <p>              
                <span><?php  ?></span><br>
                <span><?php  ?></span><br>
                <span><?php  ?></span><br>                
            </p>
        </div>
        <div class="col-md-8">
            <img src="" alt="">
        </div>
    </div>


<?php

require_once('inc/footer.php');

?>