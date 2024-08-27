document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('toggleButton');

    // Fungsi untuk mengatur tampilan sidebar
    function toggleSidebar() {
        sidebar.classList.toggle('expand');
    }

    // Tambahkan event listener pada toggle button
    toggleButton.addEventListener('click', toggleSidebar);

    toggleSidebar();
});
