
<?php
$hasil = "";

if (isset($_POST['submit'])) {

    $cpu = htmlspecialchars($_POST['cpu']);
    $ram = htmlspecialchars($_POST['ram']);
    $motherboard = htmlspecialchars($_POST['motherboard']);
    $vga = htmlspecialchars($_POST['vga']);
    $psu = htmlspecialchars($_POST['psu']);

    $hasil = "
        CPU: $cpu <br>
        RAM: $ram <br>
        Motherboard: $motherboard <br>
        VGA: $vga <br>
        PSU: $psu
    ";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>cek komponen PC</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-white">

<div class="container mt-5">

    <h1 class="mb-4">cek kompabilitas rakitan pc</h1>

    
    <div class="border rounded-4 p-4 shadow">

        <h3>Input Komponen PC</h3>

        
        <form method="POST" action="">

            
            <label class="form-label mt-3">CPU</label>
            <select name="cpu" class="form-select mb-3">
                <option value="Intel Core i5">Intel Core i5</option>
                <option value="Intel Core i7">Intel Core i7</option>
                <option value="AMD Ryzen 5">AMD Ryzen 5</option>
                <option value="AMD Ryzen 7">AMD Ryzen 7</option>
            </select>

            
            <label class="form-label">RAM</label>
            <select name="ram" class="form-select mb-3">
                <option value="8 GB">8 GB</option>
                <option value="16 GB">16 GB</option>
                <option value="32 GB">32 GB</option>
            </select>

            
            <label class="form-label">Motherboard</label>
            <select name="motherboard" class="form-select mb-3">
                <option value="ASUS">ASUS</option>
                <option value="MSI">MSI</option>
                <option value="Gigabyte">Gigabyte</option>
            </select>

            
            <label class="form-label">VGA</label>
            <select name="vga" class="form-select mb-3">
                <option value="RTX 4060">RTX 4060</option>
                <option value="RTX 3060">RTX 3060</option>
                <option value="RX 6600">RX 6600</option>
            </select>

            
            <label class="form-label">PSU</label>
            <select name="psu" class="form-select mb-3">
                <option value="500 Watt">500 Watt</option>
                <option value="650 Watt">650 Watt</option>
                <option value="750 Watt">750 Watt</option>
            </select>

            
            <div class="d-grid d-md-block">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>  

        </form>

        <hr class="my-4">

        
        <h3>hasil</h3>

        <div class="border rounded-3 p-3 mt-3">

            <?php if ($hasil != "") { ?>

                <?php echo $hasil; ?>

            <?php } else { ?>

                <p class="text-secondary mb-0">
                    Hasil akan ditampilkan di sini.
                </p>

            <?php } ?>

        </div>

    </div>

</div>

   <div class="container my-4">
  <div class="row g-4 justify-content-center">

    <div class="col-12 col-md-4">
      <div class="card h-100">
        <img src="foto/cpufoto.jpg" class="card-img-top" alt="CPU">
        <div class="card-body">
          <h5 class="card-title">CPU</h5>
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="card h-100">
        <img src="foto/ramfoto.jpg" class="card-img-top" alt="RAM">
        <div class="card-body">
          <h5 class="card-title">RAM</h5>
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="card h-100">
        <img src="foto/motherboardfoto.jpg" class="card-img-top" alt="Motherboard">
        <div class="card-body">
          <h5 class="card-title">Motherboard</h5>
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="card h-100">
        <img src="foto/vgafoto.jpg" class="card-img-top" alt="VGA">
        <div class="card-body">
          <h5 class="card-title">VGA</h5>
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="card h-100">
        <img src="foto/psufoto.jpg" class="card-img-top" alt="PSU">
        <div class="card-body">
          <h5 class="card-title">PSU</h5>
          <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
      </div>
    </div>

  </div>
</div>



</body>
</html>
