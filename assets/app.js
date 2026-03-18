import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';

// 1. On importe "Modal" spécifiquement (plus léger et propre)
import { Modal } from 'bootstrap'; 

console.log("JS chargé avec succès à : " + new Date().toLocaleTimeString());
console.log("plus besoin de watch");

// Ta fonction Toast (elle est parfaite)
window.showToast = function (message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `toast show align-items-center text-bg-${type} border-0 mb-2`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"></button>
        </div>
    `;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
};

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('section-form');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        
        const button = document.getElementById('add-section-button');
        
        // --- BLOC VISUEL DÉBUT ---
        button.disabled = true;
        button.innerHTML = "CHARGEMENT...";
        // On mémorise l'ancienne classe pour pouvoir la remettre après
        button.classList.replace('btn-primary', 'btn-danger'); 
        button.style.cursor = 'none';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action || window.location.href, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Erreur serveur');

            const html = await response.text();
            const tableBody = document.getElementById('sections-table-body');
            if (tableBody) tableBody.insertAdjacentHTML('beforeend', html);

            // --- CORRECTION MODALE ICI ---
            const modalElement = document.getElementById('addSectionModal');
            // On utilise "Modal" directement (notre import)
            const modalInstance = Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }

            form.reset();
            showToast('Section ajoutée avec succès ✅', 'success');

        } catch (error) {
            showToast('Erreur lors de l’ajout ❌', 'danger');
            console.error("Erreur détaillée :", error);
        } finally {
            // --- BLOC VISUEL FIN ---
            // Ce bloc s'exécute QUOI QU'IL ARRIVE (Succès ou Erreur)
            button.disabled = false;
            button.innerHTML = 'Ajouter';
            button.classList.replace('btn-danger', 'btn-primary');
            button.style.cursor = 'pointer';
        }
    });
});

// Le reste de ton code (suppression) est bon, assure-toi juste qu'il n'appelle pas "bootstrap." non plus.

document.addEventListener('click', async function (e) {

    const button = e.target.closest('.delete-section');
    if (!button) return;

    if (!confirm('Supprimer cette section ?')) return;

    const url = button.dataset.url;
    const token = button.dataset.token;

    // 🔒 désactiver le bouton pendant la requête
    button.disabled = true;

    try {

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _token: token
            })
        });

        if (!response.ok) {
            showToast('Erreur lors de la suppression ❌', 'danger');
            button.disabled = false; // 🔓 réactiver
            return;
        }

        const row = button.closest('tr');
        row.remove();

        showToast('Section supprimée 🗑️', 'success');

    } catch (error) {

        showToast('Erreur serveur ⚠️', 'warning');
        button.disabled = false; // 🔓 réactiver en cas d'erreur

    }
});


