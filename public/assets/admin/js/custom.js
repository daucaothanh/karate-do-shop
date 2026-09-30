/**
 * Gentelella Theme Scripts for Karate-Do Shop
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Sidebar Toggle
    const menuToggle = document.getElementById('menu_toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function (e) {
            e.preventDefault();
            document.body.classList.toggle('nav-sm');
        });
    }

    // 2. Auto Dismiss Alerts after 5s
    const alerts = document.querySelectorAll('.alert-auto-dismiss');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function () {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // 3. User Dropdown Toggle
    const userDropdown = document.getElementById('navbarDropdown');
    const userMenu = document.getElementById('userMenuDropdown');
    if (userDropdown && userMenu) {
        userDropdown.addEventListener('click', function (e) {
            e.preventDefault();
            userMenu.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
            if (!userDropdown.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.remove('show');
            }
        });
    }
});

// Image Preview Helper for file inputs
function previewImages(input, previewContainerId) {
    const container = document.getElementById(previewContainerId);
    if (!container) return;
    container.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail mr-2 mb-2';
                    img.style.width = '80px';
                    img.style.height = '80px';
                    img.style.objectFit = 'cover';
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    }
}
