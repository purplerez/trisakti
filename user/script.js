document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('toggleButton');

    // Fungsi untuk mengatur tampilan sidebar
    function toggleSidebar() {
        sidebar.classList.toggle('expand');

        // Mengatur tampilan logo dan teks berdasarkan kondisi sidebar
        const logoText = document.querySelector('.sidebar .sidebar-logo a img');
        if (sidebar.classList.contains('expand')) {
            logoText.style.display = 'inline-block';
        } else {
            logoText.style.display = 'none';
        }
    }

    // Tambahkan event listener pada toggle button
    toggleButton.addEventListener('click', toggleSidebar);

    // Panggil fungsi toggleSidebar untuk mengatur tampilan awal
    toggleSidebar();
});