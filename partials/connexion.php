<?php

$conn = 'mysql:host=localhost;dbname=bitly;charset=utf8';
$user = 'issam';
$mdp = '26121985';


try {

    $bdd = new PDO($conn, $user, $mdp);
} catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
}