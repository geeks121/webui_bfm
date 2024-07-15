<?php
?>
<!DOCTYPE html>
<html>
<head>
        <?php
        $title = "Trojan YAML Generator";
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
    	<h3><i class="fa-solid fa-horse"></i> Trojan YAML Generator</h3>
				</div>
			</div>
                	<div class="col-lg-12"><p>

	<?php
// fungsi untuk menguraikan URL trojan

// cek apakah form telah disubmit dan url trojan config telah dimasukkan
$trojan_url = ''; // Inisialisasi variabel
$reverse_trojan = false;

if (isset($_POST['import'])) {
    $trojan_url = $_POST['trojan_url'];

    if (empty($trojan_url)) {
        // Tampilkan notifikasi error jika URL kosong
        echo "<html>";
        echo "<div class='alert alert-danger'>";
        echo "<h3>Error!</h3>";
        echo "<p>Please enter a valid Trojan URL.</p>";
        echo "</div></html>";
    } elseif (!preg_match('/^(trojan|trojan-go):\/\/[^@]+@/', $trojan_url)) {
        // Tampilkan notifikasi error jika URL tidak sesuai format
        echo "<html>";
        echo "<div class='alert alert-danger'>";
        echo "<h3>Error!</h3>";
        echo "<p>Invalid URL. Please enter a valid URL starting with 'trojan://' or 'trojan-go://'.</p>";
        echo "</div></html>";
    } else {
        $reverse_trojan = isset($_POST['reverse_trojan']);

        // Mengecek komponen 'host', 'port', dan 'user' ada dalam URL
        $parsed_url = parse_url($trojan_url);
        if (isset($parsed_url['host']) && isset($parsed_url['port']) && isset($parsed_url['user'])) {
            // Menguraikan URL trojan atau trojan-go hanya jika format URL sesuai
            $host = $parsed_url['host'];
            $port = $parsed_url['port'];
            $user = $parsed_url['user'];

            // Lakukan operasi lain yang sesuai dengan URL Trojan atau Trojan-Go di sini
        } else {
            // Tampilkan notifikasi error jika salah satu komponen hilang
            echo "<html>";
            echo "<div class='alert alert-danger'>";
            echo "<h3>Error!</h3>";
            echo "<p>The URL must contain 'host', 'port', and 'password' components. Please enter a valid URL with these components.</p>";
            echo "</div></html>";
        }
    }





// menguraikan URL Trojan
	$parsed_url = parse_url($trojan_url);
	$trojan_config = array();
	$trojan_config['name'] = isset($parsed_url['fragment']) ? str_replace(["+", "%2F", "%20"], [" ", "/", " "], urldecode($parsed_url['fragment'])) : 'Trojan';
	$trojan_config['server'] = isset($parsed_url['host']) ? $parsed_url['host'] : 'host';
    $trojan_config['port'] = isset($parsed_url['port']) ? $parsed_url['port'] : '';
	$trojan_config['host]'] = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	
	// ganti nilai $trojan_config['server'] dengan "host" jika nilainya tidak ada
if (!$trojan_config['server']) {
    $trojan_config['server'] = 'host';
}
	
	// mengambil parameter
    $query_string = isset($parsed_url['query']) ? $parsed_url['query'] : '';
    $query_array = array();
    parse_str($query_string, $query_array);
    $trojan_config['security'] = isset($query_array['security']) ? $query_array['security'] : '';
    $trojan_config['headerType'] = isset($query_array['headerType']) ? $query_array['headerType'] : '';
	
	// mencari array host 
	$trojan_config['host'] = '';
	if (strstr($query_string, 'host')) {
    $trojan_config['host'] = substr($query_string, strpos($query_string, 'host') + 4);
    if (strstr($trojan_config['host'], '&')) {
        $trojan_config['host'] = substr($trojan_config['host'], 0, strpos($trojan_config['host'], '&'));
    }
    if (strstr($trojan_config['host'], '=')) {
        $trojan_config['host'] = str_replace('=', '', $trojan_config['host']);
    }
	}
	
	    // Mendapatkan username dan password dari URL
	function extract_password($trojan_url, &$trojan_config, $parsed_url) {
    $trojan_config['password'] = ($parsed_url['user'] ?? '') . (isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : ''); // Mengubah variabel menjadi $password
	}
	extract_password($trojan_url, $trojan_config, $parsed_url);

	
	
		// mencari array path
    $trojan_config['path'] = '';
    $query_string = str_replace('=', '', $query_string); // hilangkan tanda =
	$query_string = urldecode($query_string); // konversi ASCII
	$query_string = rawurldecode($query_string); // konversi semua karakter escape
    if (strstr($query_string, 'path')) {
        $trojan_config['path'] = substr($query_string, strpos($query_string, 'path') + 4);
        if (strstr($trojan_config['path'], '&')) {
            $trojan_config['path'] = substr($trojan_config['path'], 0, strpos($trojan_config['path'], '&'));
        }
    }
	
		// mencari array sni	
    $trojan_config['sni'] = 'bug.com';
    if (strstr($query_string, 'sni')) {
        $trojan_config['sni'] = substr($query_string, strpos($query_string, 'sni') + 3);
        if (strstr($trojan_config['sni'], '&')) {
            $trojan_config['sni'] = substr($trojan_config['sni'], 0, strpos($trojan_config['sni'], '&'));
        }
		
	
	// mencari array type
    $trojan_config['type'] = isset($query_array['type']) ? $query_array['type'] : 'tcp';
    } else {
        $trojan_config['type'] = isset($query_array['type']) ? $query_array['type'] : 'tcp';
    }
	
		// mencari array serviceName for grpc	
    $trojan_config['serviceName'] = '';
    if (strstr($query_string, 'serviceName')) {
        $trojan_config['serviceName'] = substr($query_string, strpos($query_string, 'serviceName') + 11);
        if (strstr($trojan_config['serviceName'], '&')) {
            $trojan_config['serviceName'] = substr($trojan_config['serviceName'], 0, strpos($trojan_config['serviceName'], '&'));
        }
	}
	// mencari array flow	
    $trojan_config['flow'] = '';
    if (strstr($query_string, 'flow')) {
        $trojan_config['flow'] = substr($query_string, strpos($query_string, 'flow') + 4);
        if (strstr($trojan_config['flow'], '&')) {
            $trojan_config['flow'] = substr($trojan_config['flow'], 0, strpos($trojan_config['flow'], '&'));
		}
	}
	// jika reverse vmess diaktifkan, tukar posisi output "post" dari elemen name="sni" dan name="server"
    if($reverse_trojan) {
    $temp = $trojan_config['sni'];
    $trojan_config['sni'] = $trojan_config['server'];
    $trojan_config['server'] = $temp;

    $temp = $trojan_config['host'];
    $trojan_config['host'] = $trojan_config['server'];
    $trojan_config['server'] = $temp;
    
    $temp = $trojan_config['server'];
    $trojan_config['server'] = $trojan_config['host'];
    $trojan_config['host'] = $temp;

    $temp = $trojan_config['sni'];
    $trojan_config['host'] = $trojan_config['sni'];
    $trojan_config['sni'] = $temp;
}


	
	   // Fungsi untuk mengenali type Trojan : ws or grpc
   function getNetType($trojan_config) {
  return isset($trojan_config['type']) ? $trojan_config['type'] : '';
	}

// Fungsi untuk mengenali flow
function getFlowArray($trojan_config) {
    $url = parse_url($trojan_config);

    if (isset($url['query'])) {
        $query_params = array();
        parse_str($url['query'], $query_params);

        if (isset($query_params['flow'])) {
            return 'flow';
        }
    }

    return '';
}

// Penutup fungsi uraikan URL Trojan
}
if(isset($_POST['save'])){
    $trojan_config['name'] = $_POST['name'];
    $trojan_config['server'] = $_POST['server'];
    $trojan_config['port'] = $_POST['port'];
    $trojan_config['type'] = $_POST['type'];
    $trojan_config['password'] = $_POST['password'];
    $trojan_config['sni'] = $_POST['sni'];
    $trojan_config['udp'] = ($_POST['udp'] == 'true') ? true : false;
    $trojan_config['skip_cert_verify'] = ($_POST['skip_cert_verify'] == 'true') ? true : false;
    $trojan_config['security'] = $_POST['security'];
	$trojan_config = array(
  'path' => isset($_POST['path']) && !empty($_POST['path']) ? $_POST['path'] : '',
  'host' => isset($_POST['host']) && !empty($_POST['host']) ? $_POST['host'] : '',
  'serviceName' => isset($_POST['grpc-name']) && !empty($_POST['grpc-name']) ? $_POST['grpc-name'] : ''
);

   // Membaca nama file yaml yang dipilih
    $yaml_name = $_POST['yaml_name'];
	
	// Membaca nama Interface
	$interfaceName = $_POST['interface_name'];
	
	// Membaca nama network
	$networkType = isset($_POST['network']) && !empty($_POST['network']) ? $_POST['network'] : '';

	// jika nilai $_POST['network'] adalah tcp, maka kosongkan nilainya
	if ($networkType == 'tcp') {
    $networkType = '';
	}
	
	// Membaca nama host
	$hostName = isset($_POST['host']) && !empty($_POST['host']) ? $_POST['host'] : '';
	
	// Membaca nama path
	$pathName = isset($_POST['path']) && !empty($_POST['path']) ? $_POST['path'] : '';
	
	// Membaca nama Grpc-Name
	$grpcName = isset($_POST['grpc-name']) && !empty($_POST['grpc-name']) ? $_POST['grpc-name'] : '';
	
	// Membaca nama flow
	$flowName = isset($_POST['flow']) && !empty($_POST['flow']) ? $_POST['flow'] : '';
	
	
    // Membaca isi file yaml
    $yaml_content = file_get_contents("/data/adb/box/clash/config/proxy_provider/" . $yaml_name . ".yaml");	


	// Mengecek isi config yaml, jika ada kata "proxies:" maka jangan tuliskan kata tersebut, jika belum ada maka tuliskan di barisan pertama.
	if(strpos($yaml_content, 'proxies:') === false) {
	$yaml_content = "proxies:\n" . $yaml_content;
	}
	  
// Buatkan fungsi $yaml_content untuk menyimpannya ke dalam config dengan isi berikut
				$yaml_content .= "- name: " . $_POST['name'] . "\n";
                $yaml_content .= "  type: trojan\n";
                $yaml_content .= "  server: " . $_POST['server'] . "\n";
                $yaml_content .= "  port: " . $_POST['port'] . "\n";
                $yaml_content .= "  password: " . $_POST['password'] . "\n";
				
				// xtls
				if (!empty($flowName)) {
				$yaml_content .= "  flow: " . $_POST['flow'] . "\n";
				}
                $yaml_content .= "  sni: " . $_POST['sni'] . "\n";
                $yaml_content .= "  skip-cert-verify: " . $_POST['skip_cert_verify'] . "\n";
				// end of xtls
				
				if (!empty($networkType)) {
                $yaml_content .= "  network: " . $_POST['network'] . "\n";

				}
				// grpc
				if (!empty($grpcName)) {
				$yaml_content .= "  grpc-opts:\n";
				
                                $yaml_content .= "      grpc-service-name: " . $_POST['grpc-name'] . "\n";
				}
				// ws
				if (!empty($pathName)) {
				$yaml_content .= "  ws-opts:\n      path: \"{$_POST['path']}\"\n";
				
                                 $yaml_content .= "      headers:\n";
				}
				if (!empty($hostName)) {
                                 $yaml_content .= "         Host: " . $_POST['host'] . "\n";
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
			echo "<p>File saved at <b>../data/adb/box/clash/config/proxy_provider/</b> as: <a href='/tools/file.php?p=box%2Fclash%2Fconfig%2Fproxy_provider&edit=$yaml_name.yaml'>$yaml_name.yaml</a> name.</p>";
			echo " </div>";
			echo "<div class='row mb-3'>";
			echo "<div class='col-md-6'>";
			echo "<a href='trojan.php' class='btn btn-primary'><i class='fa-solid fa-arrow-left'></i> Back</a>";
			echo "<form method='post'>";
			echo "</div>";
			echo "</div>";
			echo "<button type='submit' class='btn btn-danger' name='restart-oc'><i class='fa fa-refresh' aria-hidden='true'></i> Restart OpenClash</button>";
			echo "</form><p>";
			include "inc/javascript.php";
			include "inc/footer.php";
			exit();
}

if(isset($_POST['restart-oc'])) {
    exec('/etc/init.d/openclash restart > /dev/null 2>&1 &');
    $message = "<b>OpenClash restarted..</b>";
    echo "<div class='alert alert-success' style='padding: 10px; color: #2b2b2b;'>{$message}</div>";
}

?>   
    <?php if (!preg_match('/^(trojan|trojan-go):\/\/[^@]+@/', $trojan_url)): ?>
        <form method="post">
<fieldset id="servers">
    <div class="server">
            <label class="form-label" for="trojan_url"><b>Trojan URL:</b></label><br>
            <textarea class="form-control result-textarea" name="trojan_url" rows="10" cols="50" placeholder="Enter Trojan URL = trojan://abcdefg.. or trojan-go://abcdefg.." required></textarea><br>
               

<label for="reverse-trojan" class="btn btn-success" style="padding: 7px; cursor: pointer; box-shadow: 2px 2px 2px #999; border-radius: 5px; color: white;">
  <input type="checkbox" id="reverse-trojan" name="reverse_trojan" value="1">
  <i class="fa fa-random" aria-hidden="true"></i> Trojan WS Reverse
</label>

			<button type="submit" class="btn btn-primary" name="import" style="margin-top: -6px; margin-left: 10px;"> <i class="fa fa-sign-in" aria-hidden="true"></i> Import</button><br><br>
			<?php include('inc/stats.php'); ?>
			</div>
        </form><br>
</div>
</fieldset>
    <?php else: ?>
	        <div class="form-container">
            <h4>Enter Trojan Details:</h4>
				<div class="form-group" mx-auto">
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
                            <label for="name">Trojan Name:</label>
                            <input type="text" class="form-control" id="name" required name="name" value="<?php echo $trojan_config['name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="type">Type:</label>
                            <input type="text" name="type" class="form-control" id="type" value="trojan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="server">Server:</label>
                            <input type="text" name="server" class="form-control" id="server" value="<?php echo $trojan_config['server']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="port">Port:</label>
                            <input type="number" name="port" class="form-control" id="port" min="0" value="<?php echo $trojan_config['port']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label> 
						<input type='text' name='password' value="<?php echo $trojan_config['password']; ?>" class="form-control" id="password" required>
						  </div>
						  <div class="form-group">
                            <label for="udp">UDP:</label>
                            <select name="udp" class="form-control" id="udp">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sni">SNI:</label>
                            <input type="text" name="sni" class="form-control" id="sni" value="<?php echo $trojan_config['sni']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="security">Security: </label>
                            <input type="text" name="security" class="form-control" id="security" value="<?php echo $trojan_config['security']; ?>" placeholder="none" readonly>
                        </div>
                        <div class="form-group">
                            <label for="skip_cert_verify">Skip Cert Verify:</label>
                            <select name="skip_cert_verify" class="form-control" id="skip_cert_verify">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>
		<?php
	// menentukan tpye Vmess, Ws or Grpc 

  $net_type = getNetType($trojan_config);

  if ($net_type == 'ws') {
    echo '  
                        <div class="form-group">
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="' . (isset($trojan_config['type']) ? $trojan_config['type'] : '') . '" readonly>
                        </div>
		';				
  } else if ($net_type == 'grpc') {
    echo '
	
                        <div class="form-group">
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="' . (isset($trojan_config['type']) ? $trojan_config['type'] : '') . '" readonly>
                        </div>
		';				
  } else {
	echo '
	
                        <div class="form-group">
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="' . (isset($trojan_config['type']) ? $trojan_config['type'] : '') . '" readonly>
                        </div>
		';	
  }



?>
                       
<?php
	// menentukan tpye Vmess, Ws or Grpc 

  $net_type = getNetType($trojan_config);

  if ($net_type == 'ws') {
    echo '
    <div class="form-group">
      <label for="ws_path">WebSocket Path:</label>
      <input type="text" name="path" class="form-control" id="ws_path" value="' . (isset($trojan_config['path']) ? $trojan_config['path'] : '') . '" placeholder="kosongkan jika bukan ws">
    </div>
    <div class="form-group">
      <label for="ws_host">WebSocket Host:</label>
      <input type="text" name="host" class="form-control" id="ws_host" value="' . (isset($trojan_config['host']) ? $trojan_config['host'] : '') . '" placeholder="kosongkan jika bukan ws">
    </div>
		';				
  } else if ($net_type == 'grpc') {
    echo '
	<div class="form-group">
        <label for="grpc-name">gRPC Name:</label>
        <input type="text" name="grpc-name" class="form-control" id="grpc-name" value="' . $trojan_config['serviceName'] . '" placeholder="kosongkan jika bukan grpc">
    </div>
		';				
  } else {

  }

?>

 <?php
	// menentukan array flow 
	
$flow_input_name = getFlowArray($trojan_url);
if (!empty($flow_input_name)) {

    echo '              <div class="form-group">
                            <label for="flow_name">Flow :</label>
                            <input type="text" name="flow" class="form-control" id="flow" value="' . $trojan_config['flow'] . '" readonly>
                        </div>
		';
  } 
?>
                        <div class="form-group">
                            <label for="interface_name">Interface Name (optional):</label>
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
