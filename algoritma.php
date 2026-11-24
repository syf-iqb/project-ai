<?php
// Setiap aturan mengembalikan [lolos (bool), pesan (string)]

const MARGIN_PSU = 1.4;

function r1_socket($cpu, $mobo) {
    if ($cpu["socket"] === $mobo["socket"]) {
        return [true, "Socket CPU dan motherboard sama ({$cpu['socket']})"];
    } else {
        return [false, "Socket tidak cocok, CPU {$cpu['socket']} sedangkan motherboard {$mobo['socket']}"];
    }
}

function r2_tipe_ram($ram, $mobo) {
    if ($ram["ram_type"] === $mobo["ram_type"]) {
        return [true, "Tipe RAM cocok ({$ram['ram_type']})"];
    } else {
        return [false, "Tipe RAM tidak cocok, motherboard {$mobo['ram_type']} sedangkan RAM {$ram['ram_type']}"];
    }
}

function r3_psu($cpu, $gpu, $psu) {
    $butuh = round(($cpu["tdp"] + $gpu["tdp"]) * MARGIN_PSU);
    
    if ($psu["wattage"] >= $butuh) {
        return [true, "PSU {$psu['wattage']}W cukup (kebutuhan sekitar {$butuh}W)"];
    } else {
        return [false, "PSU {$psu['wattage']}W kurang (kebutuhan sekitar {$butuh}W)"];
    }
}

function r4_slot_ram($ram, $mobo) {
    if ($ram["jumlah_keping"] <= $mobo["ram_slots"]) {
        return [true, "Slot RAM cukup ({$ram['jumlah_keping']} keping, slot motherboard {$mobo['ram_slots']})"];
    } else {
        return [false, "Slot RAM tidak cukup, RAM {$ram['jumlah_keping']} keping sedangkan slot motherboard {$mobo['ram_slots']}"];
    }
}

function r5_kapasitas_ram($ram, $mobo) {
    if ($ram["kapasitas_gb"] <= $mobo["max_ram_gb"]) {
        return [true, "Kapasitas RAM {$ram['kapasitas_gb']}GB dalam batas motherboard ({$mobo['max_ram_gb']}GB)"];
    } else {
        return [false, "Kapasitas RAM {$ram['kapasitas_gb']}GB melebihi batas motherboard ({$mobo['max_ram_gb']}GB)"];
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

// kompatibel jika semua aturan lolos
function kesimpulan($hasil) {
    foreach ($hasil as [$lolos, $pesan]) {
        if (!$lolos) {
            return false;
        }
    }
    return true;
}