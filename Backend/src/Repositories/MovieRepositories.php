<?php
class MovieRepositories {
    private $pdo;
#connexion
    public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
    }
#Création
    public function add(Movie $movie): bool {
        $stmt = $this->pdo->prepare("INSERT INTO movies (title, description, duration, release_year, genre, director) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
        $movie->title,
        $movie->description,
        $movie->duration,
        $movie->release_year,
        $movie->genre,
        $movie->director
    ]);
    }
#Lecture
    public function find (int $id): ?Movie {
        $stmt = $this->pdo->prepare("SELECT * from movies WHERE id =?");
        $stmt->execute([$id]);
        $movie = $stmt->fetchObject("Movie");
        return $movie ?: null;
    }

    public function findByGenre(string $genre): array {
        $stmt = $this->pdo->prepare("SELECT * FROM movies where genre =?");
        $stmt->execute([$genre]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, "Movie");
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM movies");
        return $stmt->fetchAll(PDO::FETCH_CLASS, "Movie");
    }
#Mise à jour
    public function update(Movie $movie): bool {
        $stmt = $this->pdo->prepare("UPDATE movies SET title = ?, description = ?, duration = ?, release_year = ?, genre = ?, director = ? WHERE id = ?");
        return $stmt->execute([
        $movie->title,
        $movie->description,
        $movie->duration,
        $movie->release_year,
        $movie->genre,
        $movie->director,
        $movie->id
    ]);
    }
#Supprimer
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM movies WHERE id = ?");
        return $stmt->execute([$id]);
    }
}