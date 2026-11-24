<?php
const MARGIN_PSU = 1.4;

function r1_socket($cpu, $mobo) {
    if ($cpu["socket"] === $mobo["socket"]) {
        return [true, "R1: Socket CPU dan motherboard sama ({$cpu['socket']})"];
    } else {
        return [false, "R1: Socket tidak cocok, CPU {$cpu['socket']} sedangkan motherboard {$mobo['socket']}"];
    }
}

function r2_tipe_ram($ram, $mobo) {
    if ($ram["ram_type"] === $mobo["ram_type"]) {
        return [true, "R2: Tipe RAM cocok ({$ram['ram_type']})"];
    } else {
        return [false, "R2: Tipe RAM tidak cocok, motherboard {$mobo['ram_type']} sedangkan RAM {$ram['ram_type']}"];
    }
}

function r3_psu($cpu, $gpu, $psu) {
    $butuh = round(($cpu["tdp"] + $gpu["tdp"]) * MARGIN_PSU);
    if ($psu["wattage"] >= $butuh) {
        return [true, "R3: PSU {$psu['wattage']}W cukup (kebutuhan sekitar {$butuh}W)"];
    } else {
        return [false, "R3: PSU {$psu['wattage']}W kurang (kebutuhan sekitar {$butuh}W)"];
    }
}

function r4_slot_ram($ram, $mobo) {
    if ($ram["jumlah_keping"] <= $mobo["ram_slots"]) {
        return [true, "R4: Slot RAM cukup ({$ram['jumlah_keping']} keping, slot motherboard {$mobo['ram_slots']})"];
    } else {
        return [false, "R4: Slot RAM tidak cukup, RAM {$ram['jumlah_keping']} keping sedangkan slot motherboard {$mobo['ram_slots']}"];
    }
}

function r5_kapasitas_ram($ram, $mobo) {
    if ($ram["kapasitas_gb"] <= $mobo["max_ram_gb"]) {
        return [true, "R5: Kapasitas RAM {$ram['kapasitas_gb']}GB dalam batas motherboard ({$mobo['max_ram_gb']}GB)"];
    } else {
        return [false, "R5: Kapasitas RAM {$ram['kapasitas_gb']}GB melebihi batas motherboard ({$mobo['max_ram_gb']}GB)"];
    }
}

function jalankan_aturan($cpu, $mobo, $ram, $gpu, $psu) {
    return [
        r1_socket($cpu, $mobo),
        r2_tipe_ram($ram, $mobo),
        r3_psu($cpu, $gpu, $psu),
        r4_slot_ram($ram, $mobo),
        r5_kapasitas_ram($ram, $mobo),
    ];
}

function kesimpulan($hasil) {
    foreach ($hasil as [$lolos, $pesan]) {
        if (!$lolos) {
            return false;
        }
    }
    return true;
}

// AMBIL DAFTAR KOMPONEN UNTUK DROPDOWN
$list_cpu  = mysqli_query($conn, "SELECT id, nama, tdp FROM cpu ORDER BY nama ASC");
$list_mobo = mysqli_query($conn, "SELECT id, nama FROM motherboard ORDER BY nama ASC");
$list_ram  = mysqli_query($conn, "SELECT id, nama, jumlah_keping, kapasitas_gb FROM ram ORDER BY nama ASC");
$list_gpu  = mysqli_query($conn, "SELECT id, nama, chipset, tdp FROM gpu ORDER BY nama ASC");
$list_psu  = mysqli_query($conn, "SELECT id, nama, wattage FROM psu ORDER BY nama ASC");

// PROSES PENGECEKAN SAAT FORM DISUBMIT
$hasil = null;
$status_akhir = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cpu_id  = (int) $_POST["cpu_id"];
    $mobo_id = (int) $_POST["mobo_id"];
    $ram_id  = (int) $_POST["ram_id"];
    $gpu_id  = (int) $_POST["gpu_id"];
    $psu_id  = (int) $_POST["psu_id"];

    $cpu = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT c.*, s.nama AS socket 
        FROM cpu c 
        JOIN sockets s ON c.socket_id = s.id 
        WHERE c.id = $cpu_id
    "));

    $mobo = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT m.*, s.nama AS socket, rt.nama AS ram_type 
        FROM motherboard m 
        JOIN sockets s ON m.socket_id = s.id 
        JOIN ram_types rt ON m.ram_type_id = rt.id 
        WHERE m.id = $mobo_id
    "));

    $ram = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT r.*, rt.nama AS ram_type 
        FROM ram r 
        JOIN ram_types rt ON r.ram_type_id = rt.id 
        WHERE r.id = $ram_id
    "));

    $gpu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM gpu WHERE id = $gpu_id"));
    $psu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM psu WHERE id = $psu_id"));

    if ($cpu && $mobo && $ram && $gpu && $psu) {
        $hasil = jalankan_aturan($cpu, $mobo, $ram, $gpu, $psu);
        $status_akhir = kesimpulan($hasil);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pakar Kompatibilitas PC</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 750px; margin: 30px auto; padding: 0 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        select { width: 100%; padding: 8px; }
        button { padding: 10px 20px; background: #0d6efd; color: white; border: none; cursor: pointer; font-size: 16px; }
        .box-hasil { margin-top: 25px; padding: 15px; border: 1px solid #ccc; border-radius: 6px; background: #f9f9f9; }
        .ok { color: green; }
        .gagal { color: red; }
    </style>
</head>
<body>

<h2>Cek Kompatibilitas Rakitan PC</h2>

<form method="POST">
    <div class="form-group">
        <label>Processor (CPU)</label>
        <select name="cpu_id" required>
            <option value="">-- Pilih CPU --</option>
            <?php while ($row = mysqli_fetch_assoc($list_cpu)): ?>
                <option value="<?= $row['id'] ?>" <?= (isset($_POST['cpu_id']) && $_POST['cpu_id'] == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['nama']) ?> (TDP: <?= $row['tdp'] ?>W)
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Motherboard</label>
        <select name="mobo_id" required>
            <option value="">-- Pilih Motherboard --</option>
            <?php while ($row = mysqli_fetch_assoc($list_mobo)): ?>
                <option value="<?= $row['id'] ?>" <?= (isset($_POST['mobo_id']) && $_POST['mobo_id'] == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['nama']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Memori (RAM)</label>
        <select name="ram_id" required>
            <option value="">-- Pilih RAM --</option>
            <?php while ($row = mysqli_fetch_assoc($list_ram)): ?>
                <option value="<?= $row['id'] ?>" <?= (isset($_POST['ram_id']) && $_POST['ram_id'] == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['nama']) ?> (<?= $row['jumlah_keping'] ?> keping, Total <?= $row['kapasitas_gb'] ?>GB)
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Kartu Grafis (GPU)</label>
        <select name="gpu_id" required>
            <option value="">-- Pilih GPU --</option>
            <?php while ($row = mysqli_fetch_assoc($list_gpu)): ?>
                <option value="<?= $row['id'] ?>" <?= (isset($_POST['gpu_id']) && $_POST['gpu_id'] == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['nama']) ?> - <?= htmlspecialchars($row['chipset']) ?> (TDP: <?= $row['tdp'] ?>W)
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Power Supply (PSU)</label>
        <select name="psu_id" required>
            <option value="">-- Pilih PSU --</option>
            <?php while ($row = mysqli_fetch_assoc($list_psu)): ?>
                <option value="<?= $row['id'] ?>" <?= (isset($_POST['psu_id']) && $_POST['psu_id'] == $row['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['nama']) ?> (<?= $row['wattage'] ?>W)
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <button type="submit">Cek Kompatibilitas</button>
</form>

<?php if ($hasil !== null): ?>
    <div class="box-hasil">
        <h3>Hasil Evaluasi Aturan:</h3>
        <ul>
            <?php foreach ($hasil as [$lolos, $pesan]): ?>
                <li class="<?= $lolos ? 'ok' : 'gagal' ?>">
                    <strong><?= $lolos ? '[LOLOS]' : '[GAGAL]' ?></strong> <?= htmlspecialchars($pesan) ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <hr>
        <h3>
            Kesimpulan Akhir: 
            <?php if ($status_akhir): ?>
                <span class="ok">KOMPATIBEL (Semua aturan terpenuhi)</span>
            <?php else: ?>
                <span class="gagal">TIDAK KOMPATIBEL (Ada aturan yang gagal)</span>
            <?php endif; ?>
        </h3>
    </div>
<?php endif; ?>

</body>
</html>
