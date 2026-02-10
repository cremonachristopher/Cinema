<?php

class connexion {
private $servername = "127.0.0.1";
private $port = "3306";
private $username = "root";
private $password = "root";
private $dbname = "my_cinema";
public $db;

public function __construct() {
    try {
        $dsn = "mysql:host={$this->servername};port={$this->port};dbname={$this->dbname};charset=utf8";
        $this->db = new PDO ($dsn, $this->username, $this->password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die ("Erreur : Oups, la connexion ne s'est pas effectuée". $e->getMessage());
        }
    }
}

$connexion = new connexion();
$pdo = $connexion->db;