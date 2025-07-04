<?php
file_put_contents("log.txt", "[".date("Y-m-d H:i:s")."] Mulai cek KIR...\n", FILE_APPEND);
include 'db.php';

// Ambil tanggal hari ini
$hari_ini = date('Y-m-d');

// Ambil semua data kendaraan
$sql = "SELECT * FROM kendaraan";
$result = $conn->query($sql);

// Simpan hasil pengecekan
$kir_jatuh_tempo = [];

$token = "7974899938:AAGDlu3G5aKA1tA3eaCHuc4BsA83e5N35lo";

while($row = $result->fetch_assoc()) {
    $tanggal_kir = $row['tanggal_kir'];
    $tgl_kir_berikutnya = date('Y-m-d', strtotime($tanggal_kir . ' +6 months'));

    if ($tgl_kir_berikutnya <= $hari_ini) {
        $kir_jatuh_tempo[] = $row;

        // Chat ID bisa disesuaikan, atau mapping berdasarkan nama/nomor HP
        $chat_id = "1586262535"; 
        $pesan = "🚨 Halo {$row['nama']},\n"
               . "KIR untuk kendaraan *{$row['nama_kendaraan']}* ({$row['plat_nomor']}) sudah jatuh tempo.\n"
               . "Mohon segera diperiksa.\n\n"
               . "Terima kasih.";

       $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($pesan) . "&parse_mode=Markdown";

        $response = @file_get_contents($url); // ✅ hanya satu kali

        if ($response === FALSE) {
            file_put_contents("log.txt", "[".date("Y-m-d H:i:s")."] GAGAL kirim ke Telegram: $url\n", FILE_APPEND);
        } else {
            file_put_contents("log.txt", "[".date("Y-m-d H:i:s")."] Berhasil kirim: $response\n", FILE_APPEND);
        }


    }
}

// Tampilkan hasil dalam format JSON (bisa dipanggil dengan JS)
header('Content-Type: application/json');
echo json_encode($kir_jatuh_tempo);

file_put_contents("log.txt", "[".date("Y-m-d H:i:s")."] Selesai cek KIR\n", FILE_APPEND);
?>
