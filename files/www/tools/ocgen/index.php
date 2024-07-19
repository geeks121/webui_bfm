<?php
	require_once 'inc/theme.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>OcGen Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="data/style.css">
		<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="lib/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="lib/vendor/bootstrap/css/dark-mode.css">
    
	<link rel="apple-touch-icon" sizes="57x57" href="data/img/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="data/img/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="data/img/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="data/img/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="data/img/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="data/img/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="data/img/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="data/img/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="data/img/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="data/img/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="data/img/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="data/img/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="data/img/favicon-16x16.png">
	<link rel="manifest" href="data/img/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="data/img/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<!-- Font Awesome -->
	<link href="data/fontawesome6/css/fontawesome.css" rel="stylesheet">
	<link href="data/fontawesome6/css/brands.css" rel="stylesheet">
	<link href="data/fontawesome6/css/solid.css" rel="stylesheet">
	<link href="data/fontawesome6/css/regular.css" rel="stylesheet">
	<link href="data/fontawesome6/css/v4-shims.css" rel="stylesheet">
	
    <style>
		h1 {
    font-size: 36px;
    margin-bottom: 20px;
    font-family: "Yefimov Serif Bold", serif;
}

.dashboard-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    text-align: center;
    background-color: whitesmoke;
    font-family: "Yefimov Serif Bold", serif;
}

.dashboard-buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    margin-top: 20px;
}

.dashboard-buttons a {
    width: 150px;
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    font-size: 18px;
    color: #fff;
    border-radius: 4px;
    margin: 10px;
    font-family: "Yefimov Serif Bold", serif;
}

.menu-item:btn {
    transform: translateY(-5px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
}

.menu {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}

.menu-item {
    margin: 20px;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    transition: all 0.3s;
    cursor: pointer;
    flex-basis: 200px;
    flex-grow: 1;
    flex-shrink: 0;
    text-align: center;
    font-family: "Yefimov Serif Bold", serif;
}

.menu-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
}

.menu-item img {
    width: 100px;
    height: 100px;
    margin-bottom: 10px;
}

.menu-item h2 {
    font-size: 20px;
    margin-bottom: 10px;
}

.menu a {
    text-decoration: none;
    color: #005085;
    font-family: "Yefimov Serif Bold", serif;
}

.container {
    margin: 50px auto;
    max-width: 800px;
    text-align: center;
    font-family: "Yefimov Serif Bold", serif;
}

.bg-info {
    background-color: whitesmoke!important;
    font-family: "Yefimov Serif Bold", serif;
}

    </style>
</head>
<body class="<?php echo getThemeClass(); ?>">
<?php include 'inc/navbar.php'; ?>

        <div class="row">
            <div class="col-lg-8 col-md-12 mx-auto mt-4 mb-2">
                <div class="card bg-info box-shadow">      
					<div class="card-header">
                        <div class="text-center">
                            <h3><i class="fa fa-home"></i>  OcGen Dashboard</h3>
                        </div>
                    </div>	
        <div class="col-lg-12"><p>
        <div class="menu">
        <a href="vmess.php"> 
          <div class="menu-item">
            <img src="data/img/v2ray-icon.png" alt="Vmess">
            <h2>Vmess</h2>
          </div>
        </a>
        <a href="vless.php">
          <div class="menu-item">
            <img src="data/img/v2ray-vless-icon.png" alt="Vless">
            <h2>Vless</h2>
          </div>
        </a>
        <a href="trojan.php">
          <div class="menu-item">
            <img src="data/img/trojan-icon.png" alt="Trojan">
            <h2>Trojan</h2>
          </div>
        </a>
        <a href="ss.php">
          <div class="menu-item">
            <img src="data/img/ss-icon.png" alt="Trojan">
            <h2>Shadowsock</h2>
          </div>
        </a>
        <a href="config.php"> 
          <div class="menu-item">
            <img src="data/img/config-icon.png" alt="Config">
            <h2>Config</h2>
          </div>
        </a>
      </div>
<?php include 'inc/ip.php'; ?>
<?php require_once'inc/footer.php'; ?>
<?php include("inc/javascript.php"); ?>
<script src="js/index.js" async></script>
</body>
</html>