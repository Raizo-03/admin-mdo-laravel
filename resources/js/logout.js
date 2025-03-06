function showLogoutModal() {
    let modal = document.getElementById('logoutModal');
    modal.classList.remove('hidden'); // Remove hidden to show modal
    modal.classList.add('flex'); // Add flex to center content
}

function closeLogoutModal() {
    let modal = document.getElementById('logoutModal');
    modal.classList.remove('flex'); // Remove flex
    modal.classList.add('hidden'); // Add hidden to hide modal
}

function logout() {
    window.location.href = "{{ route('logout') }}"; // Replace with actual logout route
}