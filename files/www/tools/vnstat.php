<?php
// Tentukan path ke binari Termux
$tmuxBin = "/data/data/com.termux/files/usr/bin/";
$vnstatBin = "/data/data/com.termux/files/usr/bin/vnstat";
$vnstatdBin = "/data/data/com.termux/files/usr/bin/vnstatd";

// Jalankan daemon vnstat di latar belakang jika belum berjalan
if (!shell_exec('pidof vnstatd')) {
    shell_exec('nohup ' . $tmuxBin . 'sudo ' . $vnstatdBin . ' -n > /dev/null 2>&1 &');
    sleep(2); // Tunggu daemon berjalan
}

// Jalankan vnstat untuk penggunaan harian
$vnstatDailyOutput = shell_exec($tmuxBin . 'sudo ' . $vnstatBin . ' -d 2>&1');

// Fungsi untuk mem-parsing output vnstat harian
function parseDataHarian($output) {
    $baris = explode("\n", $output);
    $data = [];

    foreach ($baris as $line) {
        if (preg_match('/(\d{4}-\d{2}-\d{2})\s+([\d.]+) ([KMG]iB)\s+\|\s+([\d.]+) ([KMG]iB)/', $line, $cocok)) {
            $tanggal = $cocok[1];
            $download = konversiKeGiB($cocok[2], $cocok[3]);
            $upload = konversiKeGiB($cocok[4], $cocok[5]);
            $data[$tanggal] = ['download' => $download, 'upload' => $upload];
        }
    }

    return $data;
}

// Fungsi pembantu untuk mengonversi unit (GiB, MiB, KiB) ke GiB
function konversiKeGiB($nilai, $unit) {
    $unit = strtolower($unit);
    switch ($unit) {
        case 'kib': return $nilai / (1024 * 1024);
        case 'mib': return $nilai / 1024;
        case 'gib': return $nilai;
        default: return 0;
    }
}

// Fungsi untuk mengurutkan data berdasarkan tanggal terbaru ke terlama
function urutkanDataHarian($data) {
    uksort($data, function ($a, $b) {
        return strtotime($b) - strtotime($a); // Mengurutkan dari tanggal terbaru ke terlama
    });
    return $data;
}

// Mengelompokkan data berdasarkan minggu
function kelompokkanPerMinggu($data) {
    $dataMingguan = [];
    foreach ($data as $tanggal => $penggunaan) {
        $nomorMinggu = date('W', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));
        $kunci = "$tahun-Minggu $nomorMinggu";

        if (!isset($dataMingguan[$kunci])) {
            $dataMingguan[$kunci] = ['download' => 0, 'upload' => 0, 'harian' => []];
        }
        $dataMingguan[$kunci]['download'] += $penggunaan['download'];
        $dataMingguan[$kunci]['upload'] += $penggunaan['upload'];
        $dataMingguan[$kunci]['harian'][$tanggal] = $penggunaan;
    }
    return $dataMingguan;
}

$dataHarian = parseDataHarian($vnstatDailyOutput);
$dataHarian = urutkanDataHarian($dataHarian); // Urutkan data dari tanggal terbaru
$dataMingguan = kelompokkanPerMinggu($dataHarian);

// Ambil data harian bulan ini
$labels = [];
$downloadData = [];
$uploadData = [];
foreach ($dataHarian as $tanggal => $penggunaan) {
    if (strpos($tanggal, date('Y-m')) === 0) { // Pastikan hanya data bulan ini
        $labels[] = $tanggal;
        $downloadData[] = $penggunaan['download'];
        $uploadData[] = $penggunaan['upload'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penggunaan vnStat</title>
    <style>
        body {
            background-color: #1e1e1e;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
        }
        h2 {
            color: #ffa500;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .kotak-ringkasan {
            display: inline-block;
            width: 80px;
            padding: 15px;
            margin: 12px;
            text-align: center;
            background-color: #444;
            color: #ffa500;
            border-radius: 10px;
            font-size: 14px;
        }
        .kotak-ringkasan span {
            display: block;
            font-size: 24px;
            font-weight: bold;
        }
        .kotak-output {
            background-color: #292929;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border: 1px solid #444;
        }
        th {
            background-color: #444;
            color: #ffa500;
        }
        tr:nth-child(even) {
            background-color: #2a2a2a;
        }
        tr:nth-child(odd) {
            background-color: #333;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Kotak ringkasan -->
    <div class="kotak-ringkasan">
        <span><?php echo number_format(array_sum(array_column($dataHarian, 'download')), 2); ?> GB</span>
        Bulan Ini
    </div>
    <div class="kotak-ringkasan">
        <span><?php echo number_format(reset($dataHarian)['download'], 2); ?> GB</span>
        Hari Ini
    </div>
    <div class="kotak-ringkasan">
        <span><?php echo number_format(next($dataHarian)['download'], 2); ?> GB</span>
        Kemarin
    </div>

    <!-- Diagram Penggunaan Harian Bulan Ini -->
    <div class="kotak-output">
        <h2>Diagram Penggunaan Harian Bulan Ini</h2>
        <canvas id="dailyUsageChart"></canvas>
    </div>

    <!-- Tabel penggunaan mingguan -->
    <h2>Tabel Penggunaan Mingguan</h2>
    <div class="kotak-output">
        <table>
            <thead>
                <tr>
                    <th>Minggu</th>
                    <th>Total Unduhan</th>
                    <th>Total Unggahan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($dataMingguan as $minggu => $penggunaan) {
                    echo "<tr><td colspan='3'><strong>$minggu</strong></td></tr>";
                    echo "<tr><td>Total</td><td>" . number_format($penggunaan['download'], 2) . " GB</td><td>" . number_format($penggunaan['upload'], 2) . " GB</td></tr>";
                    foreach ($penggunaan['harian'] as $hari => $penggunaanHarian) {
                        echo "<tr><td>$hari</td><td>" . number_format($penggunaanHarian['download'], 2) . " GB</td><td>" . number_format($penggunaanHarian['upload'], 2) . " GB</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Mendapatkan konteks canvas
        var ctx = document.getElementById('dailyUsageChart').getContext('2d');

        // Membuat diagram
        var dailyUsageChart = new Chart(ctx, {
            type: 'line', // Jenis diagram: garis
            data: {
                labels: <?php echo json_encode($labels); ?>, // Label (tanggal)
                datasets: [{
                    label: 'Unduhan (GB)',
                    data: <?php echo json_encode($downloadData); ?>, // Data unduhan
                    borderColor: '#FFA500',
                    backgroundColor: 'rgba(255, 165, 0, 0.2)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Unggahan (GB)',
                    data: <?php echo json_encode($uploadData); ?>, // Data unggahan
                    borderColor: '#00BFFF',
                    backgroundColor: 'rgba(0, 191, 255, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Penggunaan (GB)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>