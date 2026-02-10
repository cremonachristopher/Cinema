<?php
class ScreeningRepositories {
    private $pdo;
#connexion
    public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
    }
#Création
    public function add(Screening $screening): bool {
        $stmt = $this->pdo->prepare("INSERT INTO screenings (room_id, movie_id, start_time) VALUES (?, ?, ?)");
        return $stmt->execute([
        $screening->room_id,
        $screening->movie_id,
        $screening->start_time,
    ]);
    }

#Lecture
    public function find (int $id): ?Screening {
        $stmt = $this->pdo->prepare("SELECT * from screenings WHERE id =?");
        $stmt->execute([$id]);
        $screening = $stmt->fetchObject("screening");
        return $screening ?: null;
    }

    public function getAll(): array {
        $sql =  "SELECT
                    s.id,
                    s.start_time,
                    m.title AS movie_title,
                    r.name AS room_name
                FROM screenings s
                JOIN movies m ON s.movie_id = m.id
                JOIN rooms r ON s.room_id = r.id
                ORDER BY s.start_time ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
#Mise à jour
    public function update(Screening $screening): bool {
        $stmt = $this->pdo->prepare("UPDATE screenings SET movie_id = ?, room_id = ?, start_time = ? WHERE id = ?");
        return $stmt->execute([
        $screening->movie_id,
        $screening->room_id,
        $screening->start_time,
        $screening->id
    ]);
    }
#Supprimer
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM screenings WHERE id = ?");
        return $stmt->execute([$id]);
    }

#Disponibilité
public function IsRoomOccupied($room_id, $start_time):bool {
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM screenings WHERE room_id = ? AND start_time = ?");
    $stmt->execute([$room_id, $start_time]);
    return $stmt->fetchColumn()> 0; 
}
}