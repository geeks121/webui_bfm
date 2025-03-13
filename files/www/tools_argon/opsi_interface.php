<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Interface</title>
    <link rel="stylesheet" href="css&fonts/opsi.css">
</head>
<body>
<header>
    <div class="new-container">
        <p onclick="showTab('INTERFACE')">Interface</p>
        <p onclick="showTab('IPSET')">IP Set</p>
    </div>
    <div class="header-top">
        <h1>o</h1>
    </div>
    <div class="header-bottom">
        <h1>o</h1>
    </div>
</header>

<div class="container">
    <div id="INTERFACE" class="tab-content active">
        <iframe id="interface" loading="lazy"></iframe>
    </div>
    <div id="IPSET" class="tab-content">
        <iframe id="ipset" loading="lazy"></iframe>
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
    if (tabName === 'INTERFACE' && !document.getElementById('interface').src) {
        document.getElementById('interface').src = 'interface/interface.php';
    }
    if (tabName === 'IPSET' && !document.getElementById('ipset').src) {
        document.getElementById('ipset').src = 'interface/ipset.php';
    }
}

// Set default active tab
document.addEventListener("DOMContentLoaded", function() {
    showTab('INTERFACE');
});
</script>

</body>
</html>