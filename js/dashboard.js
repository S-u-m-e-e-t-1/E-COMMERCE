function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');
    const toggleBtn = document.getElementById('toggleSidebar');

    sidebar.classList.toggle('closed');

    // Adjust content margin when sidebar is closed
    if (sidebar.classList.contains('closed')) {
        toggleBtn.innerHTML = '☰';
        content.style.marginLeft = '0';
    } else {
        toggleBtn.innerHTML = '✖';
        content.style.marginLeft = '250px';
    }
}
