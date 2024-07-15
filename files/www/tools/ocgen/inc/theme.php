<?php
// Fungsi untuk mendapatkan tema berdasarkan konfigurasi yang disimpan
function getThemeFromConfig() {
    $config = getConfig();
    if (isset($config['system']['theme'])) {
        return $config['system']['theme'];
    }
    return 'light';
}

// Fungsi untuk mendapatkan tema yang disimpan dalam cookie (jika ada)
function getThemeFromCookie() {
    if (isset($_COOKIE['theme'])) {
        return $_COOKIE['theme'];
    }
    return getThemeFromConfig(); // Menggunakan tema dari konfigurasi jika cookie tidak ada
}

// Fungsi untuk mendapatkan class tema berdasarkan tema yang dipilih
function getThemeClass() {
    $theme = getThemeFromCookie();
    if ($theme === 'dark') {
        return 'dark';
    }
    return 'light';
}

// Fungsi untuk menyimpan konfigurasi tema ke dalam file config.json
function saveConfig($config) {
    $filePath = '/root/.ocgen/system/config.json';

    // Memperoleh konfigurasi yang ada sebelumnya
    $existingConfig = getConfig();

    // Memperbarui konfigurasi tema
    $existingConfig['system']['theme'] = $config;

    // Menyimpan konfigurasi yang diperbarui ke dalam file config.json
    $updatedConfig = json_encode($existingConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    file_put_contents($filePath, $updatedConfig);
}

// Fungsi untuk memperoleh konfigurasi dari file config.json
function getConfig() {
    $filePath = '/root/.ocgen/system/config.json';
    if (file_exists($filePath)) {
        $configContent = file_get_contents($filePath);
        return json_decode($configContent, true);
    }
    return [];
}

// Event listener ketika toggle switch berubah
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme'])) {
    $selectedTheme = $_POST['theme'];
    setcookie('theme', $selectedTheme, time() + (86400 * 30), '/'); // Simpan tema dalam cookie selama 30 hari

    saveConfig($selectedTheme);

    header('Location: ' . $_SERVER['REQUEST_URI']); // Refresh halaman setelah mengubah tema
    exit;
}
?>
