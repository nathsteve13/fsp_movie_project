<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete data</title>
</head>
<body>
    <?php 
        $mysqli = new mysqli("localhost", "root", "", "fspw1");
        if ($mysqli->connect_errno) {
            die("Failed to connect to MySQL: " . $mysqli->connect_error);
        }

        $idmovie = $_GET['idmovie'];

        $stmt = $mysqli->prepare("DELETE FROM movie where idmovie = ?");
        $stmt->bind_param("i", $idmovie);

        if($stmt->execute()) {
            echo "Data berhasil dihapus";
        } else {
            echo "Data gagal dihapus : ".$stmt->error;
        }

        $stmt->close();
        $mysqli->close();

        
    ?>
    <p><a href="index.php">Kembali</a></p>
</body>
</html>