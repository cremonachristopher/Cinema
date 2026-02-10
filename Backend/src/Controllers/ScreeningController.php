<?php
class ScreeningController {
    private $service; 
    public function __construct(ScreeningService $service) {
    $this->service = $service;
    }
    
    #Lecture
    public function list() {
        header("Content-Type: application/json");
        $screening = $this->service->getAllScreenings();
        echo json_encode($screening);
    }
    #Creation
    public function create() {
        header("Content-Type: application/json");
        $data = [
            "room_id" => $_POST['room_id'] ?? null,
            'movie_id' => $_POST['movie_id'] ?? null,
            'start_time' => $_POST ['start_time'] ?? null
        ];
        if (empty ($data['room_id']) || empty ($data['movie_id']) || empty ($data['start_time'])) {
            http_response_code(400);
            echo json_encode(["error" => "Données manquantes (salles, film et horaires requis."]);
            return;
            }
        try {
            $result = $this->service->createScreening($data);
            echo json_encode([
                "message" => "Séance créée avec succès",
                "id" => $result
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    #Supprimer
    public function delete() {
        header("Content-Type: application/json");
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "ID de séance manquant."]);
            return;
        }
        $success = $this->service->deleteScreening((int)$id);
        if ($success) {
            echo json_encode(["message" => "Séance supprimée."]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Impossible de supprimer la séance."]);
        }
    }
}

