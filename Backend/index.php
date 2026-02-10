<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");
#MODELS
require_once 'src/Models/Movies.php';
require_once 'src/Models/Rooms.php';
require_once 'src/Models/Screening.php';
#REPOSITORIES
require_once 'src/Repositories/MovieRepositories.php';
require_once 'src/Repositories/ScreeningRepositories.php';
require_once 'src/Repositories/RoomsRepositories.php';
#CONTROLLERS
require_once 'src/Controllers/RoomsController.php';
require_once 'src/Controllers/MoviesController.php';
require_once 'src/Controllers/ScreeningController.php';
#SERVICE
require_once 'services/ScreeningService.php';
#connexion
require_once 'config/Database.php';

$movieRepo = new MovieRepositories($pdo);
$movieController = new MovieController($movieRepo);

$roomsRepo = new RoomsRepositories($pdo);
$roomsController = new RoomsController($roomsRepo);

$screeningRepo = new ScreeningRepositories($pdo);
$screeningService = new ScreeningService($screeningRepo);
$screeningController = new ScreeningController($screeningService);

$request = $_GET['action'] ?? '';
switch($request) {
    #FILM
    case 'list_movie': $movieController -> list();break;
    case 'add_movie': $movieController->create(); break;
    case 'update_movie': $movieController->update(); break;
    case 'delete_movie': $movieController->delete(); break;
    #Salle
    case 'list_room':
    case 'list_rooms' : $roomsController -> list(); break;
    case 'add_room':
    case 'add_rooms': $roomsController->create(); break;
    case 'update_room':
    case 'update_rooms' : $roomsController->update(); break;
    case 'delete_room':
    case 'delete_rooms' : $roomsController->delete(); break;
    #ECRAN
    case 'list_screening' : $screeningController -> list(); break;
    case 'add_screening': $screeningController->create(); break;
    case 'delete_screening' : $screeningController->delete(); break;

    default:
        http_response_code(404);
        echo json_encode (["error" => "Action '$request'non trouvée"]);
    break;
}
exit;