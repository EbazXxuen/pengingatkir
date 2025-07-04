<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Home - Pengingat KIR</title>
</head>
<body>
  <h1>Daftar Kendaraan</h1>
  <a href="form.php">+ Tambah Data Kendaraan</a>
    <table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Nama Pemilik</th>
        <th>Nomor HP</th>
        <th>Nama Kendaraan</th>
        <th>Plat Nomor</th>
        <th>Tanggal KIR</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM kendaraan ORDER BY tanggal_kir DESC");
    $no = 1;
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama']}</td>
                <td>{$row['nomor_hp']}</td>
                <td>{$row['nama_kendaraan']}</td>
                <td>{$row['plat_nomor']}</td>
                <td>{$row['tanggal_kir']}</td>
            </tr>";
        $no++;
    }
    ?>
    </table>

    <script>
        function cekKIR() {
            fetch('kir_check.php')
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    let pesan = "PERHATIAN!\nAda " + data.length + " kendaraan yang waktunya KIR:\n\n";
                    data.forEach(k => {
                        pesan += `• ${k.nama_kendaraan} (${k.plat_nomor}) - Pemilik: ${k.nama}, HP: ${k.nomor_hp}\n`;
                    });
                    alert(pesan);
                }
            });
        }

        // Jalankan saat halaman dibuka
        window.onload = cekKIR;
    </script>

</body>
</html>
