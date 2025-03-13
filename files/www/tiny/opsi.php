<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File Manager</title>
    <link rel="stylesheet" href="../tools_argon/css&fonts/opsi.css">
</head>
<body>
<header>
    <div class="new-container">
        <p onclick="showTab('Root path')">Root path</p>
        <p onclick="showTab('Storage path')">Storage path</p>
        <p onclick="showTab('/data/adb')">/data/adb</p>
        <p onclick="showTab('www')">www</p>
    </div>
    <div class="header-top">
        <h1>o</h1>
    </div>
    <div class="header-bottom">
        <h1>o</h1>
    </div>
</header>

<div class="container">
    <div id="Root path" class="tab-content active">
        <iframe id="rootFrame" loading="lazy"></iframe>
    </div>
    <div id="Storage path" class="tab-content">
        <iframe id="storageFrame" loading="lazy"></iframe>
    </div>
    <div id="/data/adb" class="tab-content">
        <iframe id="adbFrame" loading="lazy"></iframe>
    </div>
    <div id="www" class="tab-content">
        <iframe id="wwwFrame" loading="lazy"></iframe>
    </div>
</div>

<script>
function showTab(tabName) {
    var tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(function(tab) {
        tab.classList.remove('active');
    });
    var tabLinks = document.querySelectorAll('.new-container p');
    tabLinks.forEach(function(link) {
        link.classList.remove('active-tab');
    });
    var activeTab = document.getElementById(tabName);
    if (activeTab) {
        activeTab.classList.add('active');
    }
    var activeLink = document.querySelector('p[onclick="showTab(\'' + tabName + '\')"]');
    if (activeLink) {
        activeLink.classList.add('active-tab');
    }

    // Load iframe src dynamically when tab is active
    if (tabName === 'Root path' && !document.getElementById('rootFrame').src) {
        document.getElementById('rootFrame').src = 'index.php';
    }
    if (tabName === 'Storage path' && !document.getElementById('storageFrame').src) {
        document.getElementById('storageFrame').src = 'index.php?p=sdcard';
    }
    if (tabName === '/data/adb' && !document.getElementById('adbFrame').src) {
        document.getElementById('adbFrame').src = 'index.php?p=data%2Fadb';
    }
    if (tabName === 'www' && !document.getElementById('wwwFrame').src) {
        document.getElementById('wwwFrame').src = 'index.php?p=data%2Fadb%2Fphp7%2Ffiles%2Fwww';
    }
}

// Set default active tab
document.addEventListener("DOMContentLoaded", function() {
    showTab('Root path');
});
</script>

</body>
</html>