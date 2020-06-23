<?php

function executeRequete($requete, $params = array())
{
    if (!empty($params)) {
        foreach ($params as $index => $valeur) {
            $params[$index] = htmlspecialchars($valeur);
        }
    }

    global $pdo;

    $resultats = $pdo->prepare($requete);
    $resultats->execute($params);
    return $resultats;
}