<?php
class RoomsController {
    private $repository; 
    public function __construct(RoomsRepositories $repository) {
    $this->repository = $repository;
    }
    
    #Lecture
    public function list() {
        header("Content-Type: application/json");
        $rooms = $this->repository->getAll();
        echo json_encode($rooms);
    }
    #Creation
    public function create() {
        header("Content-Type: application/json");
        if(empty($_POST['name']) || empty($_POST['capacity'])) {
            http_response_code(400);
            echo json_encode(["error" => "Le nom et la capacité sont obligatoires."]);
            return;
        }
        $rooms = new Rooms();
        $rooms->name  = $_POST['name'];
        $rooms->capacity  = (int)$_POST['capacity'];
        $rooms->type  = $_POST['type'] ?? 'Standard';
        $rooms->active = 1; 

        if ($this->repository->add($rooms)) {
            echo json_encode(["message" => "Salle crée avec succès"]);
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
                echo json_encode(["error" => "ID de la salle manquant."]);
                return;
            }
            $rooms = $this->repository->find((int)$id);
            if (!$rooms) {
                http_response_code(404);
                echo json_encode(["error" => "Salle introuvable."]);
                return;
            }
                
            $rooms->name  = $_POST['name'] ?? $rooms->name;
            $rooms->capacity  = isset($_POST['capacity']) ? (int)$_POST['capacity'] : $rooms->capacity;
            $rooms->type  = $_POST['type'] ?? $rooms->type;
            $rooms->active = isset ($_POST['active']) ? (bool)$_POST['active'] : $rooms->active;

                if ($this->repository->update($rooms)) {
                    echo json_encode(["message" => "Salle mis à jour", "movie" => $rooms]);
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
            echo json_encode(["message" => "Salle supprimé avec succès."]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Impossible de supprimer. ID invalide"]);
        }
    }
}

