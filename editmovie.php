<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            background-color: #fafafa;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 12px 20px;
            font-size: 18px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-group input[type="submit"]:active {
            background-color: #3e8e41;
        }
    </style>
    <title>Edit Movie</title>
</head>
<body>
    <?php
    $mysqli = new mysqli("localhost", "root", "", "fspw1");
    if ($mysqli->connect_errno) {
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }

    // Dapatkan idmovie dari parameter URL
    $idmovie = $_GET['idmovie'];

    // Ambil data film berdasarkan idmovie
    $stmt = $mysqli->prepare("SELECT * FROM movie WHERE idmovie = ?");
    $stmt->bind_param("i", $idmovie);
    $stmt->execute();
    $result = $stmt->get_result();
    $movie = $result->fetch_assoc();

    // Ambil genre yang sudah terkait dengan film ini
    $stmt_genre = $mysqli->prepare("SELECT genre_idgenre FROM genre_has_movie WHERE movie_idmovie = ?");
    $stmt_genre->bind_param("i", $idmovie);
    $stmt_genre->execute();
    $result_genre = $stmt_genre->get_result();

    // Simpan idgenre dalam array
    $selected_genres = [];
    while ($row_genre = $result_genre->fetch_assoc()) {
        $selected_genres[] = $row_genre['genre_idgenre'];
    }

    // Jika form disubmit, proses penyimpanan data
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $judul = $_POST['judul'];
        $rilis = $_POST['rilis'];
        $skor = $_POST['skor'];
        $sinopsis = $_POST['sinopsis'];
        $serial = $_POST['serial'];
        $extention = $_POST['extention'];
        $genre = $_POST['genre'];

        // Update data film
        $stmt = $mysqli->prepare("UPDATE movie SET judul = ?, rilis = ?, skor = ?, sinopsis = ?, serial = ?, extention = ?, genre = ? WHERE idmovie = ?");
        $stmt->bind_param("ssdssisi", $judul, $rilis, $skor, $sinopsis, $serial, $extention, $genre, $idmovie);

        if ($stmt->execute()) {
            echo "Data movie berhasil diperbarui!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $mysqli->close();
    ?>

<div class="container">
        <h2>Edit Movie</h2>
        <form method="POST">
            <div class="form-group">
                <label for="judul">Judul:</label>
                <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($movie['judul']); ?>" required>
            </div>
            <div class="form-group">
                <label for="rilis">Tanggal Rilis:</label>
                <input type="date" id="rilis" name="rilis" value="<?php echo $movie['rilis']; ?>" required>
            </div>
            <div class="form-group">
                <label for="skor">Skor:</label>
                <input type="text" id="skor" name="skor" value="<?php echo $movie['skor']; ?>" required>
            </div>
            <div class="form-group">
                <label for="sinopsis">Sinopsis:</label>
                <textarea id="sinopsis" name="sinopsis" rows="4" required><?php echo htmlspecialchars($movie['sinopsis']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="serial">Serial:</label>
                <input type="text" id="serial" name="serial" value="<?php echo $movie['serial']; ?>" required>
            </div>
            <div class="form-group">
                <label for="extention">Extension:</label>
                <input type="text" id="extention" name="extention" value="<?php echo $movie['extention']; ?>" required>
            </div>
            <p>
                <label for="genre">Genre</label>
                <?php
                $mysqli = new mysqli("localhost", "root", "", "fspw1");
                if ($mysqli->connect_errno) {
                    die("Failed to connect to MySQL: " . $mysqli->connect_error);
                }
                $stmt = $mysqli->prepare("Select * From genre");
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    echo "<br><input type='checkbox'
                        name='genre[]' id='".$row['nama']."' value='".$row['idgenre']."'>
                        <label for='".$row['nama']."'>".$row['nama']."</label>";
                }
                ?>
            </p>
            <div class="form-group">
                <input type="submit" value="Simpan">
            </div>
        </form>
    </div>
</body>
</html>
