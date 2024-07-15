<?php
?>
<!DOCTYPE html>
<html>
<head>
<?php
        $title = "Shadowsocks YAML Generator";
        include("inc/header.php");
    ?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Proxy Provider Shadowsock YAML Generator</title>
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
    	<h3><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Shadowsocks YAML Generator</h3>
				</div>
			</div>
                	<div class="col-lg-12"><p>
	<?php
// fungsi untuk menguraikan URL ss
$ss_url = '';
$reverse_ss = false; // Inisialisasi variabel reverse_ss di luar blok if
// cek apakah form telah disubmit dan url ss config telah dimasukkan
if (isset($_POST['import'])) {
    $ss_url = $_POST['ss_url'];

    if (empty($ss_url)) {
        // Tampilkan notifikasi error jika URL kosong
        echo "<html>";
        echo "<div class='alert alert-danger'>";
        echo "<h3>Error!</h3>";
        echo "<p>Please enter a valid SS URL.</p>";
        echo "</div></html>";
    } elseif (!preg_match('/^(ss):\/\/[^@]+@/', $ss_url)) {
        // Tampilkan notifikasi error jika URL tidak sesuai format
        echo "<html>";
        echo "<div class='alert alert-danger'>";
        echo "<h3>Error!</h3>";
        echo "<p>Invalid URL. Please enter a valid URL starting with 'ss://'.</p>";
        echo "</div></html>";
    } else {
        $ss_url = $_POST['ss_url'];
        $reverse_ss = isset($_POST['reverse_ss']);
        
        // Mengecek nilai TLS
        function isTLSEnabled($ss_url) {
            $parsed_url = parse_url($ss_url);

            // Periksa apakah komponen 'host', 'port', dan 'user' ada dalam URL
            if (isset($parsed_url['host']) && isset($parsed_url['port']) && isset($parsed_url['user'])) {
                $port = $parsed_url['port'];

                if ($port == 443 || strpos($port, '43') !== false) {
                    return true;
                } else {
                    return false;
                }
            } else {
                // Tampilkan notifikasi error jika salah satu komponen hilang
                echo "<html>";
                echo "<div class='alert alert-danger'>";
                echo "<h3>Error!</h3>";
                echo "<p>The URL must contain 'host', 'port', and 'user' components. Please enter a valid URL with these components.</p>";
                echo "</div></html>";
                return false;
            }
        }

        // Output TLS
        $TLS = isTLSEnabled($ss_url);

        if ($TLS) {
            // menguraikan URL SS hanya jika TLS valid
            $parsed_url = parse_url($ss_url);
        }
    }
}



// menguraikan URL ss
	$parsed_url = parse_url($ss_url);
	$query_string = isset($parsed_url['query']) ? str_replace(["%2F", "%20"], ["/", " "], urldecode($parsed_url['query'])) : '';
	$ss_config = array();
	$ss_config['name'] = isset($parsed_url['fragment']) ? str_replace(["+", "%2F", "%20"], [" ", "/", " "], urldecode($parsed_url['fragment'])) : 'Shadowsocks';
	$ss_config['server'] = isset($parsed_url['host']) ? $parsed_url['host'] : 'host';
    $ss_config['port'] = isset($parsed_url['port']) ? $parsed_url['port'] : '';
	$ss_config['host]'] = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	
	// ganti nilai $ss_config['server'] dengan "host" jika nilainya tidak ada
	if (!$ss_config['server']) {
		$ss_config['server'] = 'host';
	}
	
// NEW //	
// mencari array Plugin
$plugin_pos = strpos($query_string, 'plugin');
if ($plugin_pos !== false) {
  $plugin_str = substr($query_string, $plugin_pos + 7);
  $delimiter_pos = strpos($plugin_str, '&');
  
  // tambahkan pengecekan menggunakan tanda ; sebagai delimiter
  if ($delimiter_pos === false) {
    $delimiter_pos = strpos($plugin_str, ';');
  }
  
  if ($delimiter_pos !== false) {
    $plugin_arr = explode(';', $plugin_str);
    
    // tambahkan pengecekan menggunakan tanda & sebagai delimiter
    $plugin_arr2 = explode('&', $plugin_arr[0]);
    $ss_config['plugin'] = $plugin_arr2[0];
  } else {
    $plugin_arr = explode('&', $plugin_str);
    $ss_config['plugin'] = $plugin_arr[0];
  }

  $ss_config['plugin'] = trim($ss_config['plugin']);
  
  // Fungsi untuk menghilangkan tanda "=" pada plugin
  $ss_config['plugin'] = str_replace('=', '', $ss_config['plugin']);
  
  // periksa apakah nilai plugin adalah obfs-local
  if ($ss_config['plugin'] === 'obfs-local') {
    $ss_config['plugin'] = 'obfs';
  }
}

// END NEW //
// array ck-client//
// mencari array stream-to
	$ss_config['stream-to'] = '';
	if (strstr($query_string, 'StreamTimeout')) {
    $ss_config['stream-to'] = substr($query_string, strpos($query_string, 'StreamTimeout') + 13);
    if (strstr($ss_config['stream-to'], ';')) {
        $ss_config['stream-to'] = substr($ss_config['stream-to'], 0, strpos($ss_config['stream-to'], ';'));
    }
    $ss_config['stream-to'] = urldecode($ss_config['stream-to']);
    if (strstr($ss_config['stream-to'], '=')) {
        $ss_config['stream-to'] = str_replace('=', '', $ss_config['stream-to']);
		}
	}
// mencari array UID
	$ss_config['UID'] = '';
	if (strstr($query_string, 'UID')) {
    $ss_config['UID'] = substr($query_string, strpos($query_string, 'UID=') + 4);
    if (strstr($ss_config['UID'], ';')) {
        $ss_config['UID'] = substr($ss_config['UID'], 0, strpos($ss_config['UID'], ';'));
		}
	}
// mencari array PublicKey
	$ss_config['PublicKey'] = '';
	if (strstr($query_string, 'PublicKey')) {
    $ss_config['PublicKey'] = substr($query_string, strpos($query_string, 'PublicKey=') + 10);
    if (strstr($ss_config['PublicKey'], ';')) {
        $ss_config['PublicKey'] = substr($ss_config['PublicKey'], 0, strpos($ss_config['PublicKey'], ';'));
		}
	}
// mencari array ServerName
	$ss_config['ServerName'] = '';
	if (strstr($query_string, 'ServerName')) {
    $ss_config['ServerName'] = substr($query_string, strpos($query_string, 'ServerName=') + 11);
    if (strstr($ss_config['ServerName'], ';')) {
        $ss_config['ServerName'] = substr($ss_config['ServerName'], 0, strpos($ss_config['ServerName'], ';'));
		}
	}
// mencari array BrowserSig
	$ss_config['BrowserSig'] = '';
	if (strstr($query_string, 'BrowserSig')) {
    $ss_config['BrowserSig'] = substr($query_string, strpos($query_string, 'BrowserSig=') + 11);
    if (strstr($ss_config['BrowserSig'], ';')) {
        $ss_config['BrowserSig'] = substr($ss_config['BrowserSig'], 0, strpos($ss_config['BrowserSig'], ';'));
		}
	}
// mencari array EncryptionMethod
	$ss_config['EncryptionMethod'] = '';
	if (strstr($query_string, 'EncryptionMethod')) {
    $ss_config['EncryptionMethod'] = substr($query_string, strpos($query_string, 'EncryptionMethod=') + 17);
    if (strstr($ss_config['EncryptionMethod'], ';')) {
        $ss_config['EncryptionMethod'] = substr($ss_config['EncryptionMethod'], 0, strpos($ss_config['EncryptionMethod'], ';'));
		}
	}
// mencari array Transport
	$ss_config['Transport'] = '';
	if (strstr($query_string, 'Transport')) {
    $ss_config['Transport'] = substr($query_string, strpos($query_string, 'Transport=') + 10);
    if (strstr($ss_config['Transport'], ';')) {
        $ss_config['Transport'] = substr($ss_config['Transport'], 0, strpos($ss_config['Transport'], ';'));
		}
	}
// mencari array ProxyMethod
	$ss_config['ProxyMethod'] = '';
	if (strstr($query_string, 'ProxyMethod')) {
    $ss_config['ProxyMethod'] = substr($query_string, strpos($query_string, 'ProxyMethod=') + 12);
    if (strstr($ss_config['ProxyMethod'], ';')) {
        $ss_config['ProxyMethod'] = substr($ss_config['ProxyMethod'], 0, strpos($ss_config['ProxyMethod'], ';'));
		}
	}
// mencari array NumConn
	$ss_config['NumConn'] = '';
	if (strstr($query_string, 'NumConn')) {
    $ss_config['NumConn'] = substr($query_string, strpos($query_string, 'NumConn=') + 8);
    if (strstr($ss_config['NumConn'], ';')) {
        $ss_config['NumConn'] = substr($ss_config['NumConn'], 0, strpos($ss_config['NumConn'], ';'));
		}
	}					
// END NEW //

	// mencari array OBFS/mode 
	$ss_config['obfs'] = '';
	if (strstr($query_string, 'obfs=')) {
    $ss_config['obfs'] = substr($query_string, strpos($query_string, 'obfs=') + 5);
    if (strstr($ss_config['obfs'], ';')) {
        $ss_config['obfs'] = substr($ss_config['obfs'], 0, strpos($ss_config['obfs'], ';'));
    }
    $ss_config['obfs'] = urldecode($ss_config['obfs']);
    if (strstr($ss_config['obfs'], '=')) {
        $ss_config['obfs'] = str_replace('=', '', $ss_config['obfs']);
		}
	}

	// mengambil parameter
    $query_string = isset($parsed_url['query']) ? $parsed_url['query'] : '';
    $query_array = array();
    parse_str($query_string, $query_array);
    $ss_config['security'] = isset($query_array['security']) ? $query_array['security'] : '';

	// mencari array host 
	$ss_config['host'] = 'bug.com';
	if (strstr($query_string, 'host')) {
    $ss_config['host'] = substr($query_string, strpos($query_string, 'host') + 4);
    if (strstr($ss_config['host'], '&')) {
        $ss_config['host'] = substr($ss_config['host'], 0, strpos($ss_config['host'], '&'));
    }
    $ss_config['host'] = urldecode($ss_config['host']);
    if (strstr($ss_config['host'], '=')) {
        $ss_config['host'] = str_replace('=', '', $ss_config['host']);
		}
	}
	
		// mencari array path
    $ss_config['path'] = '';
	$query_string = str_replace('=', '', $query_string);
	$query_string = rawurldecode(urldecode($query_string)); 
	
	if (strpos($query_string, 'path') !== false) {
    $ss_config['path'] = substr($query_string, strpos($query_string, 'path') + 4);
    if (strpos($ss_config['path'], ';') !== false) {
        $ss_config['path'] = substr($ss_config['path'], 0, strpos($ss_config['path'], ';'));
	} else if (strpos($ss_config['path'], '&') !== false) {
        $ss_config['path'] = substr($ss_config['path'], 0, strpos($ss_config['path'], '&'));
			}
		if (strstr($ss_config['path'], '=')) {
        $ss_config['path'] = str_replace('=', '', $ss_config['path']);
		}	
	}
	// mencari array obfs 
	$ss_config['mode'] = '';
	$plugin_arr = explode(';',$query_string);
	if (in_array('obfs', $plugin_arr)) {
		$obfs_key = array_search('obfs', $plugin_arr);
		$obfs_val = explode('=', $plugin_arr[$obfs_key + 1])[0];
		$ss_config['mode'] = urldecode($obfs_val);
	}

	
	
// Mendapatkan username dan password dari URL
function extract_password($ss_url, &$ss_config, $parsed_url) {
    $ss_config['password'] = base64_decode($parsed_url['user'] ?? '') . (isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : ''); // Mengubah variabel menjadi $password
    
    // Perbaikan script untuk memisahkan username dan cipher
    $cipher_pass = base64_decode($parsed_url['user'] ?? ''); // Mendapatkan query dari URL dan mendekode menjadi string
    $cipher_pass_arr = explode(':', $cipher_pass); // Memisahkan chiper dan password dengan delimiter titik dua
    $ss_config['cipher'] = $cipher_pass_arr[0]; // Menyimpan nilai username
    $ss_config['password'] = $cipher_pass_arr[1] ?? ''; // Menyimpan nilai password (kosong jika tidak ada)
}

extract_password($ss_url, $ss_config, $parsed_url);

	
		// mencari array sni	
    $ss_config['sni'] = 'bug.com';
    if (strstr($query_string, 'sni')) {
        $ss_config['sni'] = substr($query_string, strpos($query_string, 'sni') + 3);
        if (strstr($ss_config['sni'], '&')) {
            $ss_config['sni'] = substr($ss_config['sni'], 0, strpos($ss_config['sni'], '&'));
        }
		
	
	// mencari array type
    $ss_config['type'] = isset($query_array['type']) ? $query_array['type'] : 'tcp';
    } else {
        $ss_config['type'] = isset($query_array['type']) ? $query_array['type'] : 'tcp';
    }
	
		// mencari array serviceName for grpc	
    $ss_config['serviceName'] = '';
    if (strstr($query_string, 'serviceName')) {
        $ss_config['serviceName'] = substr($query_string, strpos($query_string, 'serviceName') + 11);
        if (strstr($ss_config['serviceName'], '&')) {
            $ss_config['serviceName'] = substr($ss_config['serviceName'], 0, strpos($ss_config['serviceName'], '&'));
        }
	}

	
	// jika reverse vmess diaktifkan, tukar posisi output "post" dari elemen name="sni" dan name="server"
if ($reverse_ss) { // Jika $reverse_ss bernilai true
        // Tukar nilai server dengan sni
        $temp = $ss_config['server'];
        $ss_config['server'] = $ss_config['host'];
        $ss_config['host'] = $temp;
} else { // Jika $reverse_ss bernilai false

}

	
	// enf of fungsi Reverse
	// Fungsi untuk mengenali type ss : ws or grpc
   function getNetType($ss_config) {
  return isset($ss_config['type']) ? $ss_config['type'] : '';
	}
	   // Fungsi untuk mengenali type plugin ss : obfs or v2ray
   function getPluginType($ss_config) {
  return isset($ss_config['plugin']) ? $ss_config['plugin'] : '';
	}
	
	   // Fungsi untuk mengenali type ss : ws or grpc
   function getCipherType($ss_config) {
  return isset($ss_config['cipher']) ? $ss_config['cipher'] : '';
	}
	
	// Fungsi untuk mengenali plugin
   function getPluginArray($ss_config) {
  $url = parse_url($ss_config);
  $query_params = array();
  parse_str($url['query'], $query_params);
  if (isset($query_params['plugin'])) {
    return 'plugin';
  } else {
    return '';
  }
}

if(isset($_POST['save'])){
    $ss_config['name'] = $_POST['name'];
    $ss_config['server'] = $_POST['server'];
    $ss_config['port'] = $_POST['port'];
    $ss_config['type'] = $_POST['type'];
    $ss_config['password'] = $_POST['password'];
    $ss_config['cipher'] = $_POST['cipher'];
    $ss_config['udp'] = ($_POST['udp'] == 'true') ? true : false;
    $ss_config['security'] = $_POST['security'];
    $ss_config['skip_cert_verify'] = isset($_POST['skip_cert_verify']) ? ($_POST['skip_cert_verify'] == 'true') : false;
	$ss_config['tls'] = isset($_POST['tls']) ? ($_POST['tls'] == 'true') : false;
	$ss_config['udp-tcp'] = isset($_POST['udp-tcp']) ? ($_POST['udp-tcp'] == 'true') : false;
	$ss_config = array(
  'path' => isset($_POST['path']) && !empty($_POST['path']) ? $_POST['path'] : '',
  'host' => isset($_POST['host']) && !empty($_POST['host']) ? $_POST['host'] : '',
  'modeName' => isset($_POST['mode-name']) && !empty($_POST['mode-name']) ? $_POST['mode-name'] : '',
  'StreamTimeout' => isset($_POST['stream-to']) && !empty($_POST['stream-to']) ? $_POST['stream-to'] : '',
  'UID' => isset($_POST['UID']) && !empty($_POST['UID']) ? $_POST['UID'] : '',
  'PublicKey' => isset($_POST['pub-key']) && !empty($_POST['pub-key']) ? $_POST['pub-key'] : '',
  'ServerName' => isset($_POST['server-name']) && !empty($_POST['server-name']) ? $_POST['server-name'] : '',
  'BrowserSig' => isset($_POST['BrowserSig']) && !empty($_POST['BrowserSig']) ? $_POST['BrowserSig'] : '',
  'EncryptionMeyhod' => isset($_POST['encryp-method']) && !empty($_POST['encryp-method']) ? $_POST['encryp-method'] : '',
  'Transport' => isset($_POST['Transport']) && !empty($_POST['Transport']) ? $_POST['Transport'] : '',
  'ProxyMethod' => isset($_POST['ProxyMethod']) && !empty($_POST['ProxyMethod']) ? $_POST['ProxyMethod'] : '',
  'NumConn' => isset($_POST['num-conn']) && !empty($_POST['num-conn']) ? $_POST['num-conn'] : '',
  'serviceName' => isset($_POST['grpc-name']) && !empty($_POST['grpc-name']) ? $_POST['grpc-name'] : ''
);

   // Membaca nama file yaml yang dipilih
    $yaml_name = $_POST['yaml_name'];
	
	// Membaca nama Interface
	$interfaceName = $_POST['interface_name'];
	
	// Membaca nama network
	$networkType = isset($_POST['network']) && !empty($_POST['network']) ? $_POST['network'] : '';
	
	// Membaca nama network
	$networkType = isset($_POST['network']) && !empty($_POST['network']) ? $_POST['network'] : '';
	
	// Membaca info dari array plugin ck-client
	// Membaca nama uid
	$UID = isset($_POST['uid']) && !empty($_POST['uid']) ? $_POST['uid'] : '';
	// Membaca nama streamTO
	$streamTO = isset($_POST['stream-to']) && !empty($_POST['stream-to']) ? $_POST['stream-to'] : '';
	// Membaca nama pubKey
	$pubKey = isset($_POST['pub-key']) && !empty($_POST['pub-key']) ? $_POST['pub-key'] : '';
	// Membaca nama servName
	$servName = isset($_POST['server-name']) && !empty($_POST['server-name']) ? $_POST['server-name'] : '';
	// Membaca nama brwsSig
	$brwsSig = isset($_POST['BrowserSig']) && !empty($_POST['BrowserSig']) ? $_POST['BrowserSig'] : '';
	// Membaca nama encrypMethod
	$encrypMethod = isset($_POST['encryp-method']) && !empty($_POST['encryp-method']) ? $_POST['encryp-method'] : '';
	// Membaca nama transport
	$transport = isset($_POST['Transport']) && !empty($_POST['Transport']) ? $_POST['Transport'] : '';
	// Membaca nama proxyMethod
	$proxyMethod = isset($_POST['ProxyMethod']) && !empty($_POST['ProxyMethod']) ? $_POST['ProxyMethod'] : '';
	// Membaca nama numConn
	$numConn = isset($_POST['num-conn']) && !empty($_POST['num-conn']) ? $_POST['num-conn'] : '';

	// End of array ck-client
	
	// Array websocket2022
	$udpTcp= isset($_POST['udp-tcp']) && !empty($_POST['udp-tcp']) ? $_POST['udp-tcp'] : '';
	$cHost= isset($_POST['chost']) && !empty($_POST['chost']) ? $_POST['chost'] : '';
	$mux= isset($_POST['mux']) && !empty($_POST['mux']) ? $_POST['mux'] : '';
	$Tls= isset($_POST['tls']) && !empty($_POST['tls']) ? $_POST['tls'] : '';
	$Udp= isset($_POST['udp']) && !empty($_POST['udp']) ? $_POST['udp'] : '';
	// End of Array websocket2022
	
	// jika nilai $_POST['network'] adalah tcp, maka kosongkan nilainya
	if ($networkType == 'tcp') {
    $networkType = '';
	}
	// jika nilai $_POST['network'] adalah grpc, maka kosongkan nilainya
	if ($networkType == 'grpc') {
    $networkType = '';
	}
	
	// Membaca nama host
	$hostName = isset($_POST['host']) && !empty($_POST['host']) ? $_POST['host'] : '';
	
	// Membaca nama path
	$pathName = isset($_POST['path']) && !empty($_POST['path']) ? $_POST['path'] : '';
	
	// Membaca nama Mode-Name
	$modeName = isset($_POST['mode-name']) && !empty($_POST['mode-name']) ? $_POST['mode-name'] : '';
	
	// Membaca nama Grpc-Name
	$grpcName = isset($_POST['grpc-name']) && !empty($_POST['grpc-name']) ? $_POST['grpc-name'] : '';
	
	// Membaca nama plugin
	$pluginName = isset($_POST['plugin']) && !empty($_POST['plugin']) ? $_POST['plugin'] : '';
	
    // Membaca isi file yaml
    $yaml_content = file_get_contents("/data/adb/box/clash/config/proxy_provider/" . $yaml_name . ".yaml");	


	// Mengecek isi config yaml, jika ada kata "proxies:" maka jangan tuliskan kata tersebut, jika belum ada maka tuliskan di barisan pertama.
	if(strpos($yaml_content, 'proxies:') === false) {
	$yaml_content = "proxies:\n" . $yaml_content;
	}
	  
// Buatkan fungsi $yaml_content untuk menyimpannya ke dalam config dengan isi berikut
				$yaml_content .= "- name: " . $_POST['name'] . "\n";
                $yaml_content .= "  type: ss\n";
                $yaml_content .= "  server: " . $_POST['server'] . "\n";
                $yaml_content .= "  port: " . $_POST['port'] . "\n";	
				$yaml_content .= "  cipher: " . $_POST['cipher'] . "\n";
                $yaml_content .= "  password: " . $_POST['password'] . "\n";
				if (!empty($pluginName)) {
				$yaml_content .= "  plugin: " . $_POST['plugin'] . "\n";
				}
				if (!empty($networkType)) {
                $yaml_content .= "  plugin-opts: " . $_POST['network'] . "\n";
				}
				
				// grpc (also work websocket 2022)
				if (!empty($grpcName)) {
				$yaml_content .= "  skip-cert-verify: " . $_POST['skip_cert_verify'] . "\n";
				$yaml_content .= "  tls: " . $_POST['tls'] . "\n";
				$yaml_content .= "  servername: " . $_POST['server'] . "\n";
				$yaml_content .= "  network: " . $_POST['network'] . "\n";	
				$yaml_content .= "  grpc-opts:\n";
				$yaml_content .= "      grpc-service-name: " . $_POST['grpc-name'] . "\n";
				}
				
				// plugin obfs
				if (!empty($modeName)) {
                   $yaml_content .= "  plugin-opts:\n      mode: " . $_POST['mode-name'] . "\n";
				}
				if (!empty($UID)) {
                    $yaml_content .= "  plugin-opts:\n      UID: " . $_POST['uid'] . "\n";
				}
				
				// plugin v2ray-plugin WS 2022
				if (!empty($udpTcp)) {
					$yaml_content .= "  udp-over-tcp: " . $_POST['udp'] . "\n";	
					$yaml_content .= "  plugin: v2ray-plugin\n";
					$yaml_content .= "  plugin-opts:\n";
                    $yaml_content .= "      mode: websocket\n";
				}
				
				if (!empty($hostName)) {
                    $yaml_content .= "      host: " . $_POST['host'] . "\n";
				}
				if (!empty($Tls)) {
                    $yaml_content .= "      tls: " . $_POST['tls'] . "\n";
				
                    $yaml_content .= "      skip-cert-verify: " . $_POST['skip_cert_verify'] . "\n";
				}	
				if (!empty($pathName)) {	
                    $yaml_content .= "      path: \"{$_POST['path']}\"\n";
				}	
                if (!empty($cHost)) {
				$yaml_content .= "      mux: " . $_POST['mux'] . "\n";
                    $yaml_content .= "      headers:\n         custom: " . $_POST['chost'] . "\n";
				}
				// end of websocket2022
				
				// array plugin ck-client
				if (!empty($streamTO)) {
                    $yaml_content .= "      StreamTimeout: " . $_POST['stream-to'] . "\n";
				}
				if (!empty($pubKey)) {
                    $yaml_content .= "      PublicKey: " . $_POST['pub-key'] . "\n";
				}
				if (!empty($servName)) {
                    $yaml_content .= "      ServerName: " . $_POST['server-name'] . "\n";
				}
				if (!empty($brwsSig)) {
					$yaml_content .= "      BrowserSig: " . $_POST['BrowserSig'] . "\n";
				}
				if (!empty($encrypMethod)) {
					$yaml_content .= "      EncryptionMethod: " . $_POST['encryp-method'] . "\n";
				}
				if (!empty($transport)) {
					$yaml_content .= "      Transport: " . $_POST['Transport'] . "\n";
				}
				if (!empty($proxyMethod)) {
                    $yaml_content .= "      ProxyMethod: " . $_POST['ProxyMethod'] . "\n";
				}
				if (!empty($numConn)) {
                    $yaml_content .= "      NumConn: " . $_POST['num-conn'] . "\n";
				}
				// end of array plugin ck-client
                if (!empty($interfaceName)) {
                    $yaml_content .= "  interface-name: " . $_POST['interface_name'] . "\n";
                }
                if (!empty($Udp)) {
					$yaml_content .= "  udp: " . $_POST['udp'] . "\n";
				}

// Menyimpan isi file yaml yang telah diperbarui
file_put_contents("/data/adb/box/clash/config/proxy_provider/" . $yaml_name . ".yaml", $yaml_content);

// Tampilkan notifikasi sukses menyimpan config
			echo "<div class='alert alert-success'>";
			echo "<h4>YAML Configuration Saved!</h4>";
			echo "<p>File saved at <b>../data/adb/box/clash/config/proxy_provider/</b> as: <a href='/tools/file.php?p=box%2Fclash%2Fconfig%2Fproxy_provider&edit=$yaml_name.yaml'>$yaml_name.yaml</a> name.</p>";
			echo " </div></p>";
			echo "<div class='row mb-3'>";
			echo "<div class='col-md-6'>";
			echo "<a href='ss.php' class='btn btn-primary'><i class='fa-solid fa-arrow-left'></i> Back</a>";
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
    exec('su -c /data/adb/box/scripts/box.iptables disable && su -c /data/adb/box/scripts/box.service stop &');
    exec('su -c /data/adb/box/scripts/box.service start &&  su -c /data/adb/box/scripts/box.iptables enable &');
    $message = "<b>BFR restarted..</b>";
    echo "<div class='alert alert-success' style='padding: 10px; color: #2b2b2b;'>{$message}</div>";
}

?>   
    <?php if (!preg_match('/^(ss):\/\/[^@]+@/', $ss_url)): ?>
        <form method="post">
<fieldset id="servers">
    <div class="server">
            <label class="form-label" for="ss_url"><b>Shadowsocks URL:</b></label><br>
            <textarea class="form-control result-textarea" name="ss_url" rows="10" cols="50" placeholder="Enter Shadowsocks URL = ss://abcdefg.." required></textarea><br>
               

<label for="reverse-ss" class="btn btn-success" style="padding: 7px; cursor: pointer; box-shadow: 2px 2px 2px #999; border-radius: 5px; color: white;">
  <input type="checkbox" id="reverse-ss" name="reverse_ss" value="1">
  <i class="fa fa-random" aria-hidden="true"></i> SS WS Reverse
</label>

			<button type="submit" class="btn btn-primary" name="import" style="margin-top: -6px; margin-left: 10px;"> <i class="fa fa-sign-in" aria-hidden="true"></i> Import</button><br><br>
			<?php include('inc/stats.php'); ?>
			</div>
        </form><br>
</div>
</fieldset>
    <?php else: ?>
	        <div class="form-container">
            <h4>Enter Shadowsock Details:</h4>
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
                            <label for="name">Shadowsock Name:</label>
                            <input type="text" class="form-control" id="name" required name="name" value="<?php echo $ss_config['name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="type">Type:</label>
                            <input type="text" name="type" class="form-control" id="type" value="ss" readonly>
                        </div>
                        <div class="form-group">
                            <label for="server">Server:</label>
                            <input type="text" name="server" class="form-control" id="server" value="<?php echo $ss_config['server']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="port">Port:</label>
                            <input type="number" name="port" class="form-control" id="port" min="0" value="<?php echo $ss_config['port']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label> 
						<input type='text' name='password' value="<?php echo $ss_config['password']; ?>" class="form-control" id="password" required>
						  </div>
						  <div class="form-group">
                            <label for="udp">UDP:</label>
                            <select name="udp" class="form-control" id="udp">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>
						<div class="form-group">
                            <label for="cipher">Cipher:</label>
                            <input type="text" name="cipher" class="form-control" id="cipher" value="<?php echo $ss_config['cipher']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="security">Security: </label>
                            <input type="text" name="security" class="form-control" id="security" value="<?php echo $ss_config['security']; ?>" placeholder="none" readonly>
                        </div>
<?php
	// menentukan tpye Plugin, obfs or Other 

  $plugin_type = getPluginType($ss_config);

  if ($plugin_type == 'obfs') {
    echo '  
                        <div class="form-group">
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="' . (isset($ss_config['type']) ? $ss_config['type'] : '') . '" readonly>
                        </div>
		';				
  } else if ($plugin_type == 'v2ray') {
    echo '
	
                        <div class="form-group">
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="' . (isset($ss_config['type']) ? $ss_config['type'] : '') . '" readonly>
                        </div>
		';				
  } else if ($plugin_type == 'v2ray-plugin') {
    echo '
		<div class="form-group">
			<label for="plugin_name">Plugin :</label>
			<input type="text" name="plugin" class="form-control" id="plugin" value="' . $ss_config['plugin'] . '" readonly>
		</div>
		<div class="form-group">
            <label for="network">Network:</label>
            <input type="select" name="network" class="form-control" id="network" value="' . (isset($ss_config['type']) ? $ss_config['type'] : '') . '" readonly>
         </div>
		';				
  } else {
	echo '
	
                      
		';	
  }


?>
<?php
	// menentukan type Chiper Shadowsock2022

$cipher_type = getCipherType($ss_config);

if (strpos($cipher_type, '2022') !== false) {
    // Jika $cipher_type mengandung "2022" dan $ss_config['path'] tidak kosong
    if (!empty($ss_config['path'])) {
		// Mendapatkan port dari $ss_config, pastikan sesuaikan dengan cara mendapatkan port dari konfigurasi Anda
            $port = $ss_config['port'];

            // Mengatur nilai "TLS" berdasarkan port
            $TLS = ($port == 443) ? 'true' : 'false';
			
        echo '
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
                <option value="true" ' . ($TLS == 'true' ? 'selected' : '') . '>True</option>
                <option value="false" ' . ($TLS == 'false' ? 'selected' : '') . '>False</option>
                </select>
        </div>
        <div class="form-group">
            <label for="udp-tcp">UDP Over TCP:</label>
            <select name="udp-tcp" class="form-control" id="udp">
                <option value="true">True</option>
                <option value="false">False</option>
            </select>
        </div>
        <div class="form-group">
            <label for="mux">Mux:</label>
            <select name="mux" class="form-control" id="mux">
                <option value="false">False</option>
                <option value="true">True</option>
            </select>
        </div>
        <div class="form-group">
            <label for="host">Host (SNI):</label>
            <input type="text" name="host" class="form-control" id="host" value="' . (isset($ss_config['sni']) ? $ss_config['sni'] : '') . '" required>
        </div>
        <div class="form-group">
            <label for="chost">Custom Header Host:</label>
            <input type="text" name="chost" class="form-control" id="chost" value="' . (isset($ss_config['host']) ? $ss_config['host'] : '') . '" required>
        </div>
        ';
    } else {
        // Jika $cipher_type mengandung "2022" tetapi $pathName kosong
        // Tambahkan tindakan yang sesuai di sini jika diperlukan
		
    }
} else if ($cipher_type == 'auto') {
    // Jika $cipher_type adalah 'auto'
    echo '
    <div class="form-group">
        <label for="network">Network:</label>
        <input type="select" name="network" class="form-control" id="network" value="' . (isset($ss_config['type']) ? $ss_config['type'] : '') . '" readonly>
    </div>
    ';
} else {
    // Jika $cipher_type tidak mengandung "2022" dan bukan 'auto'
    // Tambahkan tindakan yang sesuai di sini jika diperlukan
}



?>

 <?php
	// menentukan tpye ss, Ws or Grpc 

  $net_type = getNetType($ss_config);

  if ($net_type == 'ws') {
    echo '
                        <div class="form-group">
      <label for="ws_path">WebSocket Path:</label>
      <input type="text" name="path" class="form-control" id="ws_path" value="' . (isset($ss_config['path']) ? $ss_config['path'] : '') . '" placeholder="kosongkan jika bukan ws">
    </div>
    <div class="form-group">
      <label for="ws_host">WebSocket Host:</label>
      <input type="text" name="host" class="form-control" id="ws_host" value="' . (isset($ss_config['host']) ? $ss_config['host'] : '') . '" placeholder="kosongkan jika bukan ws">
    </div>
		';				
  } else if ($net_type == 'grpc') {
    echo '
                        <div class="form-group">
							<label for="tls">TLS:</label>
							<select name="tls" class="form-control" id="tls">
								<option value="true" ' . ($TLS ? 'selected' : '') . '>True</option>
								<option value="false" ' . (!$TLS ? 'selected' : '') . '>False</option>
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
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="' . (isset($ss_config['type']) ? $ss_config['type'] : '') . '" readonly>
                        </div>
                        <div class="form-group">
                            <label for="servername">Server Name:</label>
                            <input type="text" name="servername" class="form-control" id="servername" value="' . (isset($ss_config['sni']) ? $ss_config['sni'] : '') . '" required>
                        </div>
                        <div class="form-group">
                            <label for="grpc-name">gRPC Name:</label>
                            <input type="text" name="grpc-name" class="form-control" id="grpc-name" value="' . $ss_config['serviceName'] . '" placeholder="kosongkan jika bukan grpc">
                        </div>
		';				
  } else {

  }

?>
<?php
	// menentukan tpye Shadowsocks, Ws or Grpc 

  $plugin_type = getPluginType($ss_config);

  if ($plugin_type == 'obfs') {
    echo '
  <div class="form-group">
        <label for="plugin_name">Plugin :</label>
        <input type="text" name="plugin" class="form-control" id="plugin" value="' . $ss_config['plugin'] . '" readonly>
  </div>
	<div class="form-group">
        <label for="plugin_name">Mode :</label>
        <input type="text" name="mode-name" class="form-control" id="mode-obfs" value="' . $ss_config['obfs'] . '" readonly>
    </div>
    <div class="form-group">
      <label for="host">Host:</label>
      <input type="text" name="host" class="form-control" id="host" value="' . (isset($ss_config['host']) ? $ss_config['host'] : '') . '" required>
    </div>
		';				
  } else if ($plugin_type == 'v2ray') {
    echo '
  <div class="form-group">
                            <label for="plugin_name">Plugin :</label>
                            <input type="text" name="plugin" class="form-control" id="plugin" value="' . $ss_config['plugin'] . '" readonly>
                        </div>
						<div class="form-group">
                            <label for="plugin_name">Mode :</label>
                            <input type="text" name="mode" class="form-control" id="mode" value="websocket" readonly>
                        </div>
						<div class="form-group">
                            <label for="plugin_name">Path :</label>
                            <input type="text" name="path" class="form-control" id="path" value="' . $ss_config['path'] . '" readonly>
                        </div>
    <div class="form-group">
      <label for="host">Host:</label>
      <input type="text" name="host" class="form-control" id="host" value="' . (isset($ss_config['host']) ? $ss_config['host'] : '') . '" required>
    </div>
		';				
  } else if ($plugin_type == 'xray-plugin') {
    echo '
						<div class="form-group">
                            <label for="plugin_name">Plugin :</label>
                            <input type="text" name="plugin" class="form-control" id="plugin" value="' . $ss_config['plugin'] . '" readonly>
                        </div>
						<div class="form-group">
                            <label for="plugin_name">Mode :</label>
                            <input type="text" name="mode" class="form-control" id="mode" value="websocket" readonly>
                        </div>
						<div class="form-group">
                            <label for="plugin_name">Path :</label>
                            <input type="text" name="path" class="form-control" id="path" value="' . $ss_config['path'] . '" readonly>
                        </div>
    <div class="form-group">
      <label for="host">Host:</label>
      <input type="text" name="host" class="form-control" id="host" value="' . (isset($ss_config['host']) ? $ss_config['host'] : '') . '" required>
    </div>
		';				
  } else if ($plugin_type == 'ck-client') {
    echo '
						<div class="form-group">
                            <label for="plugin_name">Plugin :</label>
                            <input type="text" name="plugin" class="form-control" id="plugin" value="' . $ss_config['plugin'] . '" readonly>
                        </div>
						<div class="form-group">
                            <label for="plugin_name">Stream Time Out:</label>
                            <input type="text" name="stream-to" class="form-control" id="mode" value="' . $ss_config['stream-to'] . '" readonly>
                        </div>
						<div class="form-group">
                            <label for="plugin_name">UID :</label>
                            <input type="text" name="uid" class="form-control" id="UID" value="' . $ss_config['UID'] . '" readonly>
                        </div>
    <div class="form-group">
      <label for="pub-key">Public Key:</label>
      <input type="text" name="pub-key" class="form-control" id="pub-key" value="' . $ss_config['PublicKey'] . '" readonly>
    </div>
    <div class="form-group">
      <label for="server-name"">Server Name:</label>
      <input type="text" name="server-name" class="form-control" id="server-name" value="' . (isset($ss_config['ServerName']) ? $ss_config['ServerName'] : '') . '" required>
    </div>
    <div class="form-group">
      <label for="BrowserSig">Browser:</label>
      <input type="text" name="BrowserSig" class="form-control" id="server-name" value="' . $ss_config['BrowserSig'] . '" readonly>
    </div>
    <div class="form-group">
      <label for="host">Encryption Method:</label>
      <input type="text" name="encryp-method" class="form-control" id="encryp-method" value="' . $ss_config['EncryptionMethod'] . '" readonly>
    </div>
    <div class="form-group">
      <label for="host">Transport:</label>
      <input type="text" name="transport" class="form-control" id="transport" value="' . $ss_config['Transport'] . '" readonly>
    </div>
    <div class="form-group">
      <label for="ProxyMethod">Proxy Method:</label>
      <input type="text" name="ProxyMethod" class="form-control" id="transport" value="' . $ss_config['ProxyMethod'] . '" readonly>
    </div>
    <div class="form-group">
      <label for="Connection Number">Connection Number:</label>
      <input type="text" name="num-conn" class="form-control" id="num-conn" value="' . $ss_config['NumConn'] . '" readonly>
    </div>
		';				
  } else {

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