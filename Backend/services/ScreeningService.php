<?php
class ScreeningService {
    private $repository; 
    public function __construct(ScreeningRepositories $repository) {
        $this->repository = $repository;
    }
    #Recherche
    public function getAllScreenings() {
        return $this->repository->getAll();
    }
    #Création
    public function createScreening($data) {
        $isAvailable = $this->CheckRoomAvailability($data['room_id'], $data ['start_time']);
        if (!$isAvailable) {
            throw new Exception ("La salle est déjà occupée par une autre séance à ce créneau.");
        }
        $screening = new Screening();
        $screening->room_id = $data['room_id'];
        $screening->movie_id = $data['movie_id'];
        $screening->start_time = $data['start_time'];
        return $this->repository->add($screening);
    }
    #Supprimer
    public function deleteScreening(int $id) {
        return $this->repository->delete($id);
    }

    private function CheckRoomAvailability($room_id, $start_time) {
        $isOccupied = $this->repository->IsRoomOccupied($room_id, $start_time); 
            return !$isOccupied;
    }
}