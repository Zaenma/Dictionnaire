<?php

function debug(...$vars)
{
    foreach ($vars as $var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

/**
 * co_db : connexion à la base de donnée 
 *
 * @return PDO
 */
function get_pdo(): PDO
{
    $pdo = new PDO('mysql:host=localhost;dbname=traduction', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    return $pdo;
}
