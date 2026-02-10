<?php
class RoomsRepositories {
    private $pdo;
#connexion
    public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
    }
#Création
    public function add(Rooms $rooms): bool {
        try {
        $stmt = $this->pdo->prepare("INSERT INTO rooms (name, capacity, type, active) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
        $rooms->name,
        $rooms->capacity,
        $rooms->type,
        $rooms->active ? 1 : 0
    ]);
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            return false;
        }
    }
#Lecture
    public function find (int $id): ?Rooms {
        $stmt = $this->pdo->prepare("SELECT * from Rooms WHERE id =?");
        $stmt->execute([$id]);
        $rooms = $stmt->fetchObject("Rooms");
        return $rooms ?: null;
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM rooms");
        return $stmt->fetchAll(PDO::FETCH_CLASS, "Rooms");
    }
#Mise à jour
    public function update(Rooms $rooms): bool {
        $stmt = $this->pdo->prepare("UPDATE rooms SET name = ?, capacity = ?, type = ?, active = ? WHERE id = ?");
        return $stmt->execute([
        $rooms->name,
        $rooms->capacity,
        $rooms->type,
        $rooms->active ? 1 : 0
    ]);
    }
#Supprimer
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM rooms WHERE id = ?");
        return $stmt->execute([$id]);
    }
}