function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const content = document.querySelector('.content');
    const navbar = document.querySelector('.navbar');
    sidebar.classList.toggle('minimized');
    content.classList.toggle('minimized');
    navbar.classList.toggle('minimized');
    const icon = sidebar.querySelector('.toggle-btn i');
    if (sidebar.classList.contains('minimized')) {
        icon.classList.remove('fa-arrow-left');
        icon.classList.add('fa-arrow-right');
    } else {
        icon.classList.remove('fa-arrow-right');
        icon.classList.add('fa-arrow-left');
    }
}

function handleResize() {
    const sidebar = document.querySelector('.sidebar');
    const content = document.querySelector('.content');
    const navbar = document.querySelector('.navbar');
    const icon = sidebar.querySelector('.toggle-btn i');
    if (window.innerWidth <= 768) {
        sidebar.classList.add('minimized');
        content.classList.add('minimized');
        navbar.classList.add('minimized');
        icon.classList.remove('fa-arrow-left');
        icon.classList.add('fa-arrow-right');
    } else {
        sidebar.classList.remove('minimized');
        content.classList.remove('minimized');
        navbar.classList.remove('minimized');
        icon.classList.remove('fa-arrow-right');
        icon.classList.add('fa-arrow-left');
    }
}

function getInitials(name) {
    const names = name.split(' ');
    const initials = names.map(n => n[0]).join('');
    return initials.toUpperCase();
}

function loadUserAvatar() {
    const userAvatar = document.getElementById('userAvatar');
    const userName = JSON.parse('@json($user->name)'); // Nombre del usuario
    const userImage = JSON.parse('@json($user->profile_image)'); // URL de la imagen de perfil

    if (userImage) {
        userAvatar.innerHTML = `<img src="${userImage}" alt="${userName}" style="width: 40px; height: 40px; border-radius: 50%;">`;
    } else {
        const initials = getInitials(userName);
        userAvatar.innerHTML = `<div class="initials">${initials}</div>`;
    }
}

function toggleDropdown() {
    const dropdown = document.querySelector('.dropdown');
    dropdown.classList.toggle('show');
}

function openProfileModal() {
    document.getElementById('profileModal').style.display = 'block';
}

function closeProfileModal() {
    document.getElementById('profileModal').style.display = 'none';
}

function confirmDelete() {
    return confirm('¿Estás seguro de que deseas eliminar la foto de perfil?');
}

window.addEventListener('resize', handleResize);
document.addEventListener('DOMContentLoaded', loadUserAvatar);
