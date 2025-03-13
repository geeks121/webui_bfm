<?php
$p = $_SERVER['HTTP_HOST'];
$x = explode(':', $p);
$host = $x[0];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clash For Magisk</title>
    <link rel="stylesheet" href="css&fonts/opsi.css">
</head>
<body>
<header>
    <div class="new-container">
        <p onclick="showTab('CFM')">Clash For Magisk</p>
        <p onclick="showTab('YAML')">Config.yaml</p>
        <p onclick="showTab('AKUN')">Akun</p>
    </div>
    <div class="header-top">
        <h1>o</h1>
    </div>
    <div class="header-bottom">
        <h1>o</h1>
    </div>
</header>

<div class="container">
    <div id="CFM" class="tab-content active">
        <iframe id="cfm" loading="lazy"></iframe>
    </div>
    <div id="YAML" class="tab-content">
        <iframe id="yaml" loading="lazy"></iframe>
    </div>
    <div id="AKUN" class="tab-content">
        <iframe id="akun" loading="lazy"></iframe>
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
    if (tabName === 'CFM' && !document.getElementById('cfm').src) {
        document.getElementById('cfm').src = 'bfr&cfm/cfm.php';
    }
    if (tabName === 'YAML' && !document.getElementById('yaml').src) {
        document.getElementById('yaml').src = 'http://<?php echo $host; ?>/tiny/index.php?p=data%2Fclash&view=config.yaml';
    }
    if (tabName === 'AKUN' && !document.getElementById('akun').src) {
        document.getElementById('akun').src = 'http://<?php echo $host; ?>/tiny/index.php?p=data%2Fclash%2Fproxy_provider&view=akun.yaml';
    }
}

// Set default active tab
document.addEventListener("DOMContentLoaded", function() {
    showTab('CFM');
});
</script>

</body>
</html>