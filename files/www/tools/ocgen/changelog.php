<?php
?>
<!DOCTYPE html>
<html>
<head>
<?php
        $title = "Changelog";
        include("inc/header.php");
    ?>
	<style>
		.container2 {
			width: 450px;
			margin: 0 auto;
			padding: 20px;
			border: 1px solid #ccc;
		}
		.scroll {
			height: 200px;
			overflow-y: scroll;
			padding: 10px;
			border: 1px solid #ccc;
		}
	</style>
</head>
<body class="<?php echo getThemeClass(); ?>">
    <?php include('inc/navbar.php'); ?>
  <div class="container">
        <div class="row py-2">
            <div class="col-lg-6 col-md-12 mx-auto mt-3">
                <div class="card bg-info bg-transparent box-shadow">
                    <div class="col-lg-12">
		
	</div>
	<div class="card">
			<div class="card-header">
                <div class="text-center">
    	<h3><i class="fa fa-bug" aria-hidden="true"></i> Changelog</h3>
				</div>
			</div><p>
	<div class="container2">
			        <div class="form-container">
<div class="server">
		<h5>Changelog :</h5>					
		<div class="scroll">
			<strong><p>Version 1.2_beta</strong></p>
			<ul>
				<li>Fixed URL config validation</li>
				<li>Added protocol Shadowsocks WS-2022 support</li>
				<li>Fix minor bug</li>
			</ul>
			<strong><p>Version 1.1_beta</strong></p>
			<ul>
				<li>Added protocol Shadowsocks WS-2022 support</li>
				<li>Fix minor bug</li>
			</ul>
			<strong><p>Version 1.0_beta</strong></p>
			<ul>
				<li>Updated version to 1.0_beta</li>
				<li>Improved user interface</li>
				<li>Added dark mode theme</li>
				<li>Added some icon using Fontawesome theme</li>
				<li>Added login interface</li>
				<li>Added navigation bar</li>
				<li>Added Openclash Status & IP information</li>
				<li>Added Netdata menu (packet not included)</li>
				<li>Added Vless reality protocol support</li>
				<li>Added major feature</li>
				<li>Fix minor bug</li>
				<li>And more</li>
			</ul>
			<strong><p>Version 0.3_beta</strong></p>
			<ul>
				<li>Added Shadowsocks config support</li>
				<li>Improved user interface</li>
				<li>Fix minor bug</li>
			</ul>
			<strong><p>Version 0.2_beta</strong></p>
			<ul>
				<li>Improved user interface</li>
				<li>Added Unicode support on config name</li>
				<li>Fix some error websocket path on config</li>
				<li>Add gRPC mode support for all config</li>
				<li>Add TCP XTLS connection mode support for all config</li>
                <li>Fix error dependencies</<li>
				<li>Fix minor bug</li>
			</ul>
			<strong><p>Version 0.1_beta</strong></p>
			<ul>
				<li>Initial release</li>
				<li>Added basic functionality</li>
			</ul>
		</div>
	</div>
	</div>
	</div>

    <script>
        function toggleNavbar() {
            var navbar = document.getElementById("navbar");
            if (navbar.className === "collapse navbar-collapse justify-content-md-center") {
                navbar.className += " show";
            } else {
                navbar.className = "collapse navbar-collapse justify-content-md-center";
            }
        }
    </script>
	<?php include("inc/javascript.php"); ?>
<script src="js/index.js"></script>
</body>
</html>
<?php include 'inc/footer.php'; ?>