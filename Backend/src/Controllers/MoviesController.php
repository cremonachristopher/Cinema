<?php
class MovieController {
    private $repository; 
    public function __construct(MovieRepositories $repository) {
    $this->repository = $repository;
    }
    #Lecture
    public function list() {
        header("Content-Type: application/json");
        $movie = $this->repository->getAll();
        echo json_encode($movie);
    }
    #Creation
    public function create() {
        header("Content-Type: application/json");
        if(empty($_POST['title'])) {
            http_response_code(400);
            echo json_encode(["error" => "Le titre est obligatoire."]);
            return;
        }
        $movie = new Movie();
        $movie->title = $_POST['title'];
        $movie->description = $_POST['description'] ?? '';
        $movie->duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : 0;
        $movie->release_year = !empty($_POST['release_year']) ? (int)$_POST['release_year'] : (int)date('Y');
        $movie->genre = $_POST['genre']?? '';
        $movie->director = $_POST['director']?? '';

        if ($this->repository->add($movie)) {
            echo json_encode(["message" => "Film crée avec succès"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Erreur lors de la création"]);
        }
    }
    #Modification
        public function update() {
            header("Content-Type: application/json");

            $id = $_POST['id'] ?? null;
            if (!$id) {
                http_response_code(400);
                echo json_encode(["error" => "ID manquant pour la modification."]);
                return;
            }
            $movie = $this->repository->find((int)$id);
            if (!$movie) {
                http_response_code(404);
                echo json_encode(["error" => "Film introuvable."]);
                return;
            }
                $movie->title = $_POST['title'] ?? $movie->title;
                $movie->description = $_POST['description'] ?? $movie->description;
                $movie->duration = $_POST['duration']?? $movie->duration;
                $movie->release_year = $_POST['release_year']?? $movie->release_year;
                $movie->genre = $_POST['genre']?? $movie->genre;
                $movie->director = $_POST['director']?? $movie->director;

                if ($this->repository->update($movie)) {
                    echo json_encode(["message" => "Film mis à jour", "movie" => $movie]);
                } else {
                    http_response_code(500);
                    echo json_encode (["error" => "Erreur lors de la mise  jour."]);
                }
        }
    #Supprimer
    public function delete() {
        header("Content-Type: application/json");
        $id = $_GET['id'] ?? null;
        if ($id && $this->repository->delete((int)$id)) {
            echo json_encode(["message" => "Film supprimé avec succès."]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Impossible de supprimer. ID invalide"]);
        }
    }
}