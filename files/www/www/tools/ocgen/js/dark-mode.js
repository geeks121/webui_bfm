// Fungsi untuk mengatur tema dan menyimpannya ke cookie
function toggleTheme() {
    var body = document.querySelector('body');
    var sunAstro = document.querySelector('.sun.astro-KXYEDVG6');
    var moonAstro = document.querySelector('.moon.astro-KXYEDVG6');
    var theme = body.classList.contains('dark') ? 'light' : 'dark';

    body.classList.remove('dark', 'light');
    body.classList.add(theme);

    if (theme === 'dark') {
        sunAstro.style.display = 'none';
        moonAstro.style.display = 'block';
    } else {
        sunAstro.style.display = 'block';
        moonAstro.style.display = 'none';
    }

    saveThemeToCookie(theme);
    
}

// Fungsi untuk menyimpan tema ke cookie
function saveThemeToCookie(theme) {
    document.cookie = 'theme=' + theme + '; path=/';
}

// Fungsi untuk memuat tema dari cookie saat halaman dimuat
function loadThemeFromCookie() {
    var theme = getCookie('theme');
    if (theme) {
        var body = document.querySelector('body');
        body.classList.add(theme);

        var sunAstro = document.querySelector('.sun.astro-KXYEDVG6');
        var moonAstro = document.querySelector('.moon.astro-KXYEDVG6');

        if (theme === 'dark') {
            sunAstro.style.display = 'none';
            moonAstro.style.display = 'block';
        } else {
            sunAstro.style.display = 'block';
            moonAstro.style.display = 'none';
        }
    } else {
        detectDarkMode();
    }
}

// Fungsi untuk mendapatkan nilai cookie
function getCookie(name) {
    var value = '; ' + document.cookie;
    var parts = value.split('; ' + name + '=');
    if (parts.length === 2) {
        return parts.pop().split(';').shift();
    }
}

// Fungsi untuk mendeteksi mode gelap
function detectDarkMode() {
    var darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    if (darkModeMediaQuery.matches) {
        var body = document.querySelector('body');
        var sunAstro = document.querySelector('.sun.astro-KXYEDVG6');
        var moonAstro = document.querySelector('.moon.astro-KXYEDVG6');

        body.classList.add('dark');
        sunAstro.style.display = body.classList.contains('dark') ? 'none' : 'block';
        moonAstro.style.display = body.classList.contains('dark') ? 'block' : 'none';

        saveThemeToCookie(body.classList.contains('dark') ? 'dark' : 'light');
        saveTheme(body.classList.contains('dark') ? 'dark' : 'light');
    }
}



// Memuat tema dari cookie saat halaman dimuat
window.addEventListener('DOMContentLoaded', function() {
    loadThemeFromCookie();
});

// Event listener ketika halaman di-scroll
window.addEventListener('scroll', function() {
    var floatingButton = document.querySelector('.floating-button');
    floatingButton.style.bottom = '50px'; // Menetapkan jarak dari bawah halaman
});