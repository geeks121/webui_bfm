<?php
?>
<!DOCTYPE html>
<html>
<head>
<?php
        $title = "Vmess YAML Generator";
        include("inc/header.php");
    ?>
    <style>
        .server {
            border: 2px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
        .container {
            margin-top: 50px;
        }
        .result-textarea {
            width: 100%;
        }
    </style>
</head>
<body class="<?php echo getThemeClass(); ?>">
    <?php include('inc/navbar.php'); ?>
    <div class="container">
        <div class="row py-2">
            <div class="col-lg-6 col-md-12 mx-auto mt-3">
                <div class="card bg-info bg-transparent box-shadow">
				<div class="card">
			<div class="card-header">
                <div class="text-center">
    	<h3><i class="fa fa-snowflake-o" aria-hidden="true"></i> Vmess YAML Generator</h3>
				</div>
			</div>
                	<div class="col-lg-12"><p>
			
		
	<?php
// fungsi untuk menguraikan URL vmess
function parse_vmess_url($url) {
    $vmess = base64_decode(str_replace(['vmess://', '\n'], ['', ''], $url));
    $vmess_json = json_decode($vmess, true);
    return $vmess_json;
}

// cek apakah form telah disubmit
$vmess_url = ''; // Inisialisasi variabel
$reverse_vmess = false;
$tls = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import'])) {
    if (empty($_POST['vmess_url'])) {
        // Tampilkan notifikasi error jika URL kosong
        echo "<html>";
        echo "<div class='alert alert-danger'>";
        echo "<h3>Error!</h3>";
        echo "<p>Please enter a valid VMess URL.</p>";
        echo "</div></html>";
    } elseif (strpos($_POST['vmess_url'], 'vmess://') !== 0) {
        // Tampilkan notifikasi error jika URL tidak sesuai format
        echo "<html>";
        echo "<div class='alert alert-danger'>";
        echo "<h3>Error!</h3>";
        echo "<p>Invalid URL. Please enter a valid URL starting with 'vmess://'.</p>";
        echo "</div></html>";
    } else {
        $vmess_url = $_POST['vmess_url'];
        $reverse_vmess = isset($_POST['reverse_vmess']);
        $vmess_json = parse_vmess_url($vmess_url);

        if ($vmess_json !== null) {
            $port = $vmess_json['port'];
            if ($port == 443 || strpos($port, '43') !== false) {
                $tls = true;
                echo "";
            } else {
                echo "";
            }
        } else {
				echo "<html>";
				echo "<div class='alert alert-danger'>";
				echo "<h3>Error!</h3>";
				echo "<p>Invalid VMess URL. Please check the URL format.</p>";
				echo "</div></html>";
        }
    }
}

// menguraikan URL vmess
    $vmess_json = parse_vmess_url($vmess_url);


    // jika reverse vmess diaktifkan, tukar posisi output "post" dari elemen name="server" dan name="ws_host"
    if($reverse_vmess) {
        $temp = $vmess_json['add'];
        $vmess_json['add'] = $vmess_json['host'];
        $vmess_json['host'] = $temp;
   }	
   // Fungsi untuk mengenali type VMESS : ws or grpc
   function getNetType($vmess_json) {
  return isset($vmess_json['net']) ? $vmess_json['net'] : '';
	}

if(isset($_POST['save'])){
    $vmess_json['ps'] = $_POST['name'];
    $vmess_json['add'] = $_POST['server'];
    $vmess_json['port'] = $_POST['port'];
    $vmess_json['id'] = $_POST['uuid'];
    $vmess_json['aid'] = $_POST['alterId'];
    $vmess_json['net'] = $_POST['network'];
    $vmess_json['tls'] = ($_POST['tls'] == 'true') ? true : false;
$vmess_json = array(
  'path' => isset($_POST['ws_path']) && !empty($_POST['ws_path']) ? $_POST['ws_path'] : '',
  'host' => isset($_POST['ws_host']) && !empty($_POST['ws_host']) ? $_POST['ws_host'] : '',
  'serviceName' => isset($_POST['grpc-name']) && !empty($_POST['grpc-name']) ? $_POST['grpc-name'] : ''
);

   // Membaca nama file yaml yang dipilih
    $yaml_name = $_POST['yaml_name'];
	
	// Membaca nama Interface
	$interfaceName = $_POST['interface_name'];

	// Membaca nama host
	$hostName = isset($_POST['ws_host']) && !empty($_POST['ws_host']) ? $_POST['ws_host'] : '';

	// Membaca nama path
	$pathName = isset($_POST['ws_path']) && !empty($_POST['ws_path']) ? $_POST['ws_path'] : '';

	// Membaca nama Grpc-Name
	$grpcName = isset($_POST['grpc-name']) && !empty($_POST['grpc-name']) ? $_POST['grpc-name'] : '';

    // Membaca isi file yaml
    $yaml_content = file_get_contents("/data/adb/box/clash/config/proxy_provider/" . $yaml_name . ".yaml");

	// Mengecek isi config yaml, jika ada kata "proxies:" maka jangan tuliskan kata tersebut, jika belum ada maka tuliskan di barisan pertama.
	if(strpos($yaml_content, 'proxies:') === false) {
	$yaml_content = "proxies:\n" . $yaml_content;
	}

// Buatkan fungsi $yaml_content untuk menyimpannya ke dalam config dengan isi berikut
	$yaml_content .= "- name: " . $_POST['name'] . "\n";
	$yaml_content .= "  type: " . $_POST['type'] . "\n";
	$yaml_content .= "  server: " . $_POST['server'] . "\n";
	$yaml_content .= "  port: " . $_POST['port'] . "\n";
	$yaml_content .= "  uuid: " . $_POST['uuid'] . "\n";
	$yaml_content .= "  alterId: " . $_POST['alterId'] . "\n";
	$yaml_content .= "  cipher: " . $_POST['cipher'] . "\n";
	$yaml_content .= "  skip-cert-verify: " . $_POST['skip_cert_verify'] . "\n";
	$yaml_content .= "  tls: " . $_POST['tls'] . "\n";
	$yaml_content .= "  servername: " . $_POST['servername'] . "\n";
	$yaml_content .= "  network: " . $_POST['network'] . "\n";
	// grpc
	if (!empty($grpcName)) {
	$yaml_content .= "  grpc-opts:\n";
	$yaml_content .= "      grpc-service-name: " . $_POST['grpc-name'] . "\n";
	}
	// ws
	if (!empty($pathName)) {
	$yaml_content .= "  ws-opts:\n      path: " . $_POST['ws_path'] . "\n";
	$yaml_content .= "      headers:\n";
	}
				
	if (!empty($hostName)) {
    $yaml_content .= "         Host: " . $_POST['ws_host'] . "\n";
	}
	
	if (!empty($interfaceName)) {
    $yaml_content .= "  interface-name: " . $_POST['interface_name'] . "\n";
	}

	$yaml_content .= "  udp: " . $_POST['udp'] . "\n";


// Menyimpan isi file yaml yang telah diperbarui
	file_put_contents("/data/adb/box/clash/config/proxy_provider/" . $yaml_name . ".yaml", $yaml_content);

// Tampilkan notifikasi sukses menyimpan config
			echo "<div class='alert alert-success'>";
			echo "<h4>YAML Configuration Saved!</h4>";
			echo "<p>File saved at <b>../data/adb/box/clash/config/proxy_provider/</b> as: <a href='/tools/file.php?p=box%2Fclash%2Fconfig%2Fproxy_provider&edit=$yaml_name.yaml'>$yaml_name.yaml</a> name</p>";
			echo " </div>";
			echo "<div class='row mb-3'>";
			echo "<div class='col-md-6'>";
			echo "<a href='vmess.php' class='btn btn-primary'><i class='fa-solid fa-arrow-left'></i> Back</a>";
			echo " </div>";
			echo " </div>";
			echo "<form method='post'>";
			echo "<button type='submit' class='btn btn-danger' name='restart-oc'><i class='fa fa-refresh' aria-hidden='true'></i> Restart OpenClash</button>";
			echo "</form><p>";
			include "inc/javascript.php";
			include "inc/footer.php";
			exit();
}

if(isset($_POST['restart-oc'])) {
    exec('su -c /data/adb/box/scripts/box.iptables disable && su -c /data/adb/box/scripts/box.service stop &');
    exec('su -c /data/adb/box/scripts/box.service start &&  su -c /data/adb/box/scripts/box.iptables enable &');
    $message = "<b>BFR restarted..</b>";
    echo "<div class='alert alert-success' style='padding: 10px; color: #2b2b2b;'>{$message}</div>";
	
}


?> 
    <?php if(!isset($vmess_json)): ?>
        <form method="post">
<fieldset id="servers">
    <div class="server">
            <label class="form-label" for="vmess_url"><b>VMess URL:</b></label><br>
            <textarea class="form-control result-textarea" name="vmess_url" rows="10" cols="50" placeholder="Enter Vmess URL = vmess://abcdefg.." required></textarea><br>
               

<label for="reverse-vmess" class="btn btn-success" style="padding: 7px; cursor: pointer; box-shadow: 2px 2px 2px #999; border-radius: 5px; color: white;">
  <input type="checkbox" id="reverse-vmess" name="reverse_vmess" value="1">
  <i class="fa fa-random" aria-hidden="true"></i> Vmess WS Reverse
</label>

			<button type="submit" class="btn btn-primary" name="import" style="margin-top: -6px; margin-left: 10px;"> <i class="fa fa-sign-in" aria-hidden="true"></i> Import</button><br><br>
			<p>
			<?php include('inc/stats.php'); ?>
			</div>
</div>

</fieldset>
        </form>
    <?php else: ?>
	        <div class="form-container">
            <h4>Enter VMess Details:</h4>
				<div class="form-group">
        <form method="post">
            <?php
            // Nama folder yang akan ditampilkan isinya
            $folder = "/data/adb/box/clash/config/proxy_provider/";

            // Buka folder
            if ($handle = opendir($folder)) {
            ?>
                <!-- Tampilkan pilihan file di dalam folder -->
                <fieldset id="servers">
				      <div class="server">
				<label for="yaml_name">Select YAML Config <a href='/tools/file.php?p=box%2Fclash%2Fconfig%2Fproxy_provider'>(check here)</a> :</label>
                <select name="yaml_name" class="form-control" id="yaml_name">
                    <?php
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != "..") {
                            // Hilangkan ekstensi file
                            $entry_name = pathinfo($entry, PATHINFO_FILENAME);
                    ?>
                            <option value="<?= $entry_name ?>"><?= $entry ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
				</fieldset>
				                    </div>
                        </div>
            <?php
                // Tutup folder
                closedir($handle);
            }
            ?><br>
                <fieldset id="servers">
                    <legend>Servers:</legend>

    <div class="server">
                        <div class="form-group">
                            <label for="name">Vmess Name:</label>
                            <input type="text" class="form-control" id="name" required name="name" value="<?php echo $vmess_json['ps']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="type">Type:</label>
                            <input type="text" name="type" class="form-control" id="type" value="vmess" readonly>
                        </div>
                        <div class="form-group">
                            <label for="server">Server:</label>
                            <input type="text" name="server" class="form-control" id="server" value="<?php echo $vmess_json['add']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="port">Port:</label>
                            <input type="number" name="port" class="form-control" id="port" min="0" value="<?php echo $vmess_json['port']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="uuid">UUID:</label>
                            <input type="text" name="uuid" class="form-control" id="uuid" value="<?php echo $vmess_json['id']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alterId">Alter ID:</label>
                            <input type="number" name="alterId" class="form-control" id="alterId" min="0" value="<?php echo $vmess_json['aid']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="cipher">Cipher:</label>
                            <input type="text" name="cipher" class="form-control" id="cipher" value="auto" readonly>
                        </div>
                        <div class="form-group">
                            <label for="udp">UDP:</label>
                            <select name="udp" class="form-control" id="udp">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="skip_cert_verify">Skip Cert Verify:</label>
                            <select name="skip_cert_verify" class="form-control" id="skip_cert_verify">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tls">TLS:</label>
                            <select name="tls" class="form-control" id="tls">
                                
    <option value="true" <?php echo ($tls) ? 'selected' : ''; ?>>True</option>
    <option value="false" <?php echo (!$tls) ? 'selected' : ''; ?>>False</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="servername">Server Name:</label>
                            <input type="text" name="servername" class="form-control" id="servername" value="<?php echo isset($vmess_json['host']) ? $vmess_json['host'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="<?php echo $vmess_json['net']; ?>" readonly>
                        </div>
	<?php
	// menentukan tpye Vmess, Ws or Grpc 
if (isset($_POST['import']) && !empty($_POST['vmess_url']) && strpos($_POST['vmess_url'], 'vmess://') === 0) {
  $vmess_url = $_POST['vmess_url'];
  $reverse_vmess = isset($_POST['reverse_vmess']);

  $vmess_json = json_decode(base64_decode(substr($vmess_url, 8)), true);
  $net_type = getNetType($vmess_json);

  if ($net_type == 'ws') {
    echo '
                        <div class="form-group">
      <label for="ws_path">WebSocket Path:</label>
      <input type="text" name="ws_path" class="form-control" id="ws_path" value="' . (isset($vmess_json['path']) ? $vmess_json['path'] : '') . '" placeholder="kosongkan jika bukan ws">
    </div>
    <div class="form-group">
      <label for="ws_host">WebSocket Host:</label>
      <input type="text" name="ws_host" class="form-control" id="ws_host" value="' . (isset($vmess_json['host']) ? $vmess_json['host'] : '') . '" placeholder="kosongkan jika bukan ws">
    </div>
		';				
  } else if ($net_type == 'grpc') {
    echo '
	
                        <div class="form-group">
                            <label for="grpc-name">gRPC Name:</label>
                            <input type="text" name="grpc-name" class="form-control" id="grpc-name" value="' . $vmess_json['path'] . '" placeholder="kosongkan jika bukan grpc">
                        </div>
		';				
  } else {
	  
  }

  // continue with the rest of the code
}

?>
                        <div class="form-group">
                            <label for="interface_name">Interface Name:</label>
                            <input type="text" name="interface_name" class="form-control" id="interface_name" placeholder="eth01">
                        </div></p>
						
                        <button type="submit" class="btn btn-danger" name="new"><i class="fa-solid fa-circle-plus"></i> New</button></br>
                    </div>
                </fieldset>
                
                <div class="d-grid gap-2 col-4 mx-auto">                   
                    <button name="save" type="submit" class="btn btn-success" align="center"><i class="fa-regular fa-floppy-disk"></i> Generate</button>
                </div>
            </form>
        <br>
	</div>
    <?php endif; ?>
<?php include("inc/javascript.php"); ?>
<?php include("inc/js.php"); ?>
</body>
</html>
<?php include 'inc/footer.php'; ?>