# 🎬 CineFlash - Gestion de Cinéma

**CineFlash** est une application web complète de gestion de cinéma. Elle permet d'administrer les films, les salles et la programmation des séances via une interface unique et réactive.

Le projet est conçu avec une architecture **MVC (Model-View-Controller)** et utilise le **Repository Pattern** pour une gestion propre des données.

---

## 🛠️ Configuration & Installation

### 1. Prérequis
* **Serveur Local :** PHP 8.0+ et MySQL (XAMPP, WAMP, MAMP ou Laragon).
* **Base de données :** Créez une base nommée `my_cinema`.

### 2. Initialisation de la Base de Données
Exécutez le script SQL suivant pour créer les tables avec le support du **Soft Delete** :

```sql
CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    duration INT,
    release_year INT,
    description TEXT,
    deleted_at DATETIME NULL
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    capacity INT NOT NULL,
    type VARCHAR(50),
    deleted_at DATETIME NULL
);

CREATE TABLE screenings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT,
    room_id INT,
    start_time DATETIME,
    deleted_at DATETIME NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

3. Connexion PHP
Dans votre fichier de configuration (src/Config/Database.php), configurez vos accès :
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'my_cinema');
    define('DB_USER', 'root');
    define('DB_PASS', '');
Serveur Backend : Ouvrez un terminal dans le dossier racine du projet et lancez :
    php -S localhost:8000 -t .
Accès Frontend : Ouvrez le fichier index.html dans votre navigateur ou utilisez l'extension Live Server de VS Code.
    Vérification : L'application doit pointer vers http://localhost:8000/index.php pour les appels API.
Structure MVC (Model, View, Controller)
    CineFlash/
    ├── index.html              # Vue principale (Frontend)
    ├── js/
    │   └── api.js              # Logique Fetch et manipulation du DOM
    └── src/
        ├── Config/             # Connexion à la base de données
        ├── Controllers/        # Logique de traitement des requêtes
        ├── Models/             # Classes d'objets (Movie, Room, etc.)
        ├── Repositories/       # Requêtes SQL (Abstraction de la BDD)
        └── index.php           # Routeur principal de l'API
Le backend expose les routes suivantes via index.php?action={nom_action}. Toutes les données sont échangées au format JSON.
Méthode,Action,Description
GET,list_movie,Récupère tous les films non supprimés.
POST,add_movie,Crée un nouveau film en base.
POST,update_movie,Modifie un film (nécessite un id en POST).
GET,delete_movie,Applique un Soft Delete via l'ID en paramètre.
GET,list_room,Récupère la liste des salles actives.
POST,add_room,Crée une nouvelle salle.
GET,list_screening,Liste les séances avec jointures (Film/Salle).


Développeur : Moi-même
Version : 1.0.0

Licence : MIT