const API_URL = 'http://localhost:8000/index.php';

/**
 * 1. INITIALISATION
 */
document.addEventListener('DOMContentLoaded', () => {
    refreshAll();
    setupEventListeners();
});

function refreshAll() {
    loadMovies();
    loadRooms();
    loadScreenings();
    fillSelects();
}

/**
 * 2. LECTURE ET AFFICHAGE (READ)
 */

async function loadMovies() {
    const grid = document.getElementById('movie-grid');
    const tbody = document.getElementById('movie-list-body');
    try {
        const response = await fetch(`${API_URL}?action=list_movie`);
        const movies = await response.json();

        // Affichage en grille (Public)
        if (grid) {
            grid.innerHTML = movies.map(movie => `
                <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-white/5 p-4">
                    <div class="h-40 bg-gray-700 rounded-lg mb-4 flex items-center justify-center italic text-gray-500 text-xs text-center p-2">
                        Affiche : ${movie.title}
                    </div>
                    <h4 class="font-bold text-lg truncate">${movie.title}</h4>
                    <p class="text-red-500 text-xs font-bold mb-2">${movie.genre || 'Cinéma'}</p>
                    <div class="flex justify-between text-xs text-gray-400">
                        <span>${movie.duration} min</span>
                        <span>${movie.release_year}</span>
                    </div>
                </div>
            `).join('');
        }

        // Affichage en tableau (Admin)
        if (tbody) {
            tbody.innerHTML = movies.map(movie => `
                <tr class="hover:bg-white/5 transition border-b border-white/5">
                    <td class="p-4 font-bold text-white">${movie.title}</td>
                    <td class="p-4 text-red-500 text-xs font-bold">${movie.genre || 'N/A'}</td>
                    <td class="p-4 text-sm text-gray-400">${movie.duration} min</td>
                    <td class="p-4 text-sm text-gray-400">${movie.release_year}</td>
                    <td class="p-4 text-right space-x-2">
                        <button onclick="editMovie(${JSON.stringify(movie).replace(/"/g, '&quot;')})" class="text-blue-400 hover:text-blue-600"><i class="fas fa-edit"></i></button>
                        <button onclick="deleteMovie(${movie.id})" class="text-gray-500 hover:text-red-500"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `).join('');
        }
    } catch (e) { console.error("Erreur films:", e); }
}

async function loadRooms() {
    const tbody = document.getElementById('room-list-body');
    if (!tbody) return;
    try {
        const response = await fetch(`${API_URL}?action=list_room`);
        const rooms = await response.json();
        tbody.innerHTML = rooms.map(room => `
            <tr class="hover:bg-white/5 transition border-b border-white/5">
                <td class="p-6 font-bold text-white">${room.name}</td>
                <td class="p-6 text-gray-400">${room.capacity} places</td>
                <td class="p-6"><span class="bg-blue-500/10 text-blue-400 px-3 py-1 rounded-full text-xs font-bold">${room.type}</span></td>
                <td class="p-6 text-right space-x-2">
                    <button onclick="editRoom(${JSON.stringify(room).replace(/"/g, '&quot;')})" class="text-blue-400 hover:text-blue-600"><i class="fas fa-edit"></i></button>
                    <button onclick="deleteRoom(${room.id})" class="text-gray-500 hover:text-red-500"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `).join('');
    } catch (e) { console.error("Erreur salles:", e); }
}

async function loadScreenings() {
    const tbody = document.getElementById('screening-list-body');
    if (!tbody) return;
    try {
        const response = await fetch(`${API_URL}?action=list_screening`);
        const screenings = await response.json();
        tbody.innerHTML = screenings.map(s => `
            <tr class="hover:bg-red-500/5 transition border-b border-white/5">
                <td class="p-6 font-black text-white italic underline decoration-red-600">${s.movie_title}</td>
                <td class="p-6 text-gray-300">${s.room_name}</td>
                <td class="p-6 font-mono text-sm text-gray-400">${new Date(s.start_time).toLocaleString('fr-FR')}</td>
                <td class="p-6 text-right">
                    <button onclick="deleteScreening(${s.id})" class="text-gray-500 hover:text-red-500"><i class="fas fa-times"></i></button>
                </td>
            </tr>
        `).join('');
    } catch (e) { console.error("Erreur séances:", e); }
}

/**
 * 3. SUPPRESSIONS (DELETE)
 */

async function deleteMovie(id) {
    if (confirm("Supprimer ce film ?")) {
        await fetch(`${API_URL}?action=delete_movie&id=${id}`);
        refreshAll();
    }
}

async function deleteRoom(id) {
    if (confirm("Supprimer cette salle ?")) {
        await fetch(`${API_URL}?action=delete_room&id=${id}`);
        refreshAll();
    }
}

async function deleteScreening(id) {
    if (confirm("Annuler cette séance ?")) {
        await fetch(`${API_URL}?action=delete_screening&id=${id}`);
        refreshAll();
    }
}

/**
 * 4. MODIFICATIONS (UPDATE)
 */

function editMovie(movie) {
    const form = document.getElementById('add-movie-form');
    form.title.value = movie.title;
    form.genre.value = movie.genre;
    form.duration.value = movie.duration;
    form.release_year.value = movie.release_year;
    form.description.value = movie.description;
    
    // On change l'action du formulaire pour l'update
    form.dataset.editId = movie.id;
    form.querySelector('button').innerText = "Enregistrer les modifications";
    form.querySelector('button').classList.replace('bg-white', 'bg-blue-600');
    window.location.hash = "admin-zone";
}

function editRoom(room) {
    const form = document.getElementById('add-room-form');
    form.name.value = room.name;
    form.capacity.value = room.capacity;
    form.type.value = room.type;
    
    form.dataset.editId = room.id;
    form.querySelector('button').innerText = "Modifier la salle";
    form.querySelector('button').classList.replace('bg-gray-700', 'bg-blue-600');
    window.location.hash = "admin-zone";
}

/**
 * 5. ENVOIS ET FORMULAIRES (CREATE & UPDATE)
 */

function setupEventListeners() {
    handleFormSubmit('add-movie-form', 'add_movie', 'update_movie');
    handleFormSubmit('add-room-form', 'add_room', 'update_room');
    handleFormSubmit('add-screening-form', 'add_screening');
}

function handleFormSubmit(formId, addAction, updateAction) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const editId = form.dataset.editId;
        const action = editId ? updateAction : addAction;
        
        const formData = new FormData(form);
        if (editId) formData.append('id', editId);

        try {
            const response = await fetch(`${API_URL}?action=${action}`, { method: 'POST', body: formData });
            const result = await response.json();

            if (result.message) {
                alert(result.message);
                resetForm(form);
                refreshAll();
            } else {
                alert("Erreur: " + result.error);
            }
        } catch (e) { console.error("Erreur envoi:", e); }
    });
}

function resetForm(form) {
    form.reset();
    delete form.dataset.editId;
    const btn = form.querySelector('button');
    if (form.id === 'add-movie-form') {
        btn.innerText = "Ajouter le film";
        btn.className = "w-full bg-white text-black font-bold py-3 rounded-lg hover:bg-red-600 hover:text-white transition";
    } else if (form.id === 'add-room-form') {
        btn.innerText = "Créer la salle";
        btn.className = "w-full bg-gray-700 text-white font-bold py-3 rounded-lg hover:bg-white hover:text-black transition";
    }
}

/**
 * 6. UTILITAIRES
 */

async function fillSelects() {
    try {
        const [mRes, rRes] = await Promise.all([
            fetch(`${API_URL}?action=list_movie`),
            fetch(`${API_URL}?action=list_room`)
        ]);
        const movies = await mRes.json();
        const rooms = await rRes.json();

        const mSelect = document.getElementById('movie-select');
        const rSelect = document.getElementById('room-select');

        if (mSelect) mSelect.innerHTML = '<option value="">Choisir un film</option>' + 
            movies.map(m => `<option value="${m.id}">${m.title}</option>`).join('');

        if (rSelect) rSelect.innerHTML = '<option value="">Choisir une salle</option>' + 
            rooms.map(r => `<option value="${r.id}">${r.name} (${r.type})</option>`).join('');
    } catch (e) { console.error("Erreur selects:", e); }
}