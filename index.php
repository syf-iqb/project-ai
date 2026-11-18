<?php
require_once 'koneksi.php';

$result = $conn->query("SELECT nama, brand, cores, tdp FROM cpu ");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar CPU</title>
</head>
<body>
    <h2>Daftar CPU</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Nama</th>
            <th>Brand</th>
            <th>Cores</th>
            <th>TDP (Watt)</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['brand']) ?></td>
            <td><?= htmlspecialchars($row['cores']) ?></td>
            <td><?= htmlspecialchars($row['tdp']) ?> W</td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
