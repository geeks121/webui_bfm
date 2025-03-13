<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
        $title = "About";
        include("inc/header.php");
    ?>
    <style>
/* untuk tampilan layar yang lebih kecil dari 768px */
@media (max-width: 767px) {
    .container2 {
        width: 100%;
        margin: 0 auto;
        padding: 10px;
        border: 1px solid #ccc;
    }
}
		p { font-family: Arial; }
    </style>
</head>
<body class="<?php echo getThemeClass(); ?>">
<?PHP
// fungsi untuk menghapus OcGen    
function deleteOcgen() {
	global $ocgen; // Menggunakan variabel global $ocgen
            // Logika untuk menghapus OpenClash Config Generator
            echo "<script>
            var r = confirm('Apakah Anda yakin ingin menghapus paket $ocgen?');
            if (r == true) {
                var r2 = confirm('Anda benar-benar yakin untuk menghapus paket $ocgen?');
                if (r2 == true) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '';
                    form.style.display = 'none';

                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'confirm';
                    input.value = 'yes';

                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            } else {
                window.location.href = window.location.href; //arahkan ke halaman saat ini jika tidak ingin menghapus
            }
          </script>"; //tampilkan dialog konfirmasi penghapusan
}

if(isset($_POST['confirm']) && $_POST['confirm'] == 'yes'){ //jika penghapusan dikonfirmasi
global $ocgen; // Menggunakan variabel global $ocgen
    // menghapus ocgen
    shell_exec("opkg remove luci-app-ocgen > /dev/null 2>&1; rm -rf /www/ocgen > /dev/null 2>&1");
    echo "<script>alert('$ocgen berhasil dihapus.')</script>";
    echo "<script>
                window.location.href = '/';
          </script>"; //redirect ke halaman selanjutnya setelah 1 detik    
        }
// end of fungsi menghapus OcGen		
?>
	<div id="app">
    <?php include('inc/navbar.php'); ?>
        <div class="row py-2">
            <div class="col-lg-6 col-md-12 mx-auto mt-3">
                <div class="card bg-info bg-transparent box-shadow">
				
<div class="card">
    <div class="card-header">	
		<h3 class="text-center my-4"><i class="fa-brands fa-github-alt"></i> About</h3>
	</div>		
</div>		
                    <div class="col-lg-12">
            <div class="row mb-3">
            <div class="col-md-6">
                    
			</div>				
		</div>				
	</div>
		<div class="container2">		
			<div class="form-container">	
			<p>This is the OpenClash Configuration Generator.  It allows users to generate configuration files for OpenClash from Vmess, Vless, Trojan and Shadowsock accounts, the popular Clash client for OpenWrt based routers.</p>
        <p>The OpenClash Config Generator provides a user-friendly interface where users can specify their preferred settings, such as server locations, proxy types, and other Config options. Once the configuration is generated, users can edit the resulting YAML file and import it into their OpenClash configuration.</p>
        <p>This tool aims to simplify the process of creating OpenClash configurations, especially for users who are not familiar with YAML syntax or prefer a graphical interface over manual editing.</p>
        <p>If you have any questions or feedback regarding the OpenClash Config Generator, please feel free to contact me.</p>
					<div class="server">
				 <div class="server">
                        <div class="justify-content-md-center text-center">
                            <strong>
                                <p align="center">
                                  <a href="changelog.php">Changelogs</a>
                                </p>
                                <p align="center">
                                    GUI and Luci App <br><a href="https://github.com/mitralola716" target="blank">Aji Setiawan</a>
                                </p>
                                <p align="center">
                                    GUI Design <br><a href="https://github.com/Putra-0" target="blank">ADI-PUTRA</a>
                                </p>
                                <p align="center">
                                    Icon Theme Design <br><a href="https://google.com/search?q=Fontawesome+theme" target="blank">Fontawesome</a>
                                </p>
                                <p align="center">
                                    GUI Theme <br><a href="https://google.com/search?q=bootstrap+theme+css" target="blank">Bootstrap Theme</a>
                                </p>
                                <p align="center">
                                    Code Improvement <br><a href="https://github.com/mitralola716" target="blank">Aji Setiawan</a>
                                </p>
                                <p align="center">
                                    Original Script and Inspired <br><a href="https://www.facebook.com/1sampai6" target="blank">No Van</a>
                                </p>
                                <p align="center" class="text-danger">
                                    Please do not change existing credits, if you modify this script please add your name to the credits.
                                </p>
                            </strong>
<?php
// Menampilkan IP address dan hostname
global $ipAdress;
global $hostname;
echo "IP Address: $ipAddress<br>";
echo "Hostname: $hostname";

?>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
		
<?php

        // Tampilkan footer
        echo '<div class="footer">';
		include 'inc/ip.php';
        include 'inc/footer.php';
        echo '</div>';
  
    ?>


<?php include("inc/javascript.php"); ?>
<?php include("inc/js.php"); ?>
</body>
</html>