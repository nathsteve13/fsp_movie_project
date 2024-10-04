<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    $now = strtotime("now");
    $next = true;

    if(isset($_SESSION['timestamp'])) {
        $diff = $now - $_SESSION['timestamp'];

        if($diff < 60) {
            $next = false;
        } else {
            $_SESSION['timestamp'] = $now;
        }
    } else {
        $_SESSION['timestamp'] = $now;
    }
    

    if ($next) {
        $nama = $_POST['nama'];
        echo $nama;
    } else {
        echo "Mohon tunggu ". (60-$diff) ." sebelum mengirim kembali";
    }
    ?>
</body>
</html>