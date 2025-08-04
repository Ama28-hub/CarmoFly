<?php
namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    public static function getConnection(): PDO
    {
        try {
            return new PDO(
                'mysql:host=localhost;dbname=agence_voyage;charset=utf8',
                'root',
                'test',
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            // Vous pouvez logguer l’erreur ici si besoin
            throw new RuntimeException('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }
}
