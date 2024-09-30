<?php 

session_start();

if (!isset($_SESSION['userid'])) {
    header("location: login.php");
}

require_once("class/user.php");
require_once("class/movie.php");

$user = new User();
$current_user = $user->getUser($_SESSION['userid']);

$movie = new Movie();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Movie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .insert-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            text-align: center;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .insert-button:hover {
            background-color: #0056b3;
        }

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .movie-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .movie-card:hover {
            transform: scale(1.05);
        }

        .movie-content {
            padding: 15px;
        }

        .movie-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 10px;
        }

        .movie-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .movie-score.teks-merah {
            color: red;
            font-weight: bold;
        }

        .movie-synopsis {
            font-size: 14px;
            color: #444;
            margin-bottom: 10px;
        }

        .movie-actions {
            text-align: center;
            padding: 10px;
            background-color: #f9f9f9;
            border-top: 1px solid #eee;
        }

        .movie-actions a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
            margin: 0 10px;
        }

        .movie-actions a:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <p>Welcome, <?php echo htmlspecialchars($current_user['username']); ?>!</p>
    <a href="logout.php">Logout</a>
    <h1>Daftar Movie</h1>
    <p>
        <form action="">
            <label for="cari">Masukkan judul</label>
            <input type="text" name="cari">
            <input type="submit" value="Cari">
        </form>
    </p>

    <?php 
    
    $no_hal = (isset($_GET["page"])) ? $_GET["page"] : 1;
    $LIMIT = 3;
    $offset = ($no_hal - 1)* $LIMIT;
    
    $cari = "";
        if(isset($_GET['cari'])) {
            $cari = $_GET['cari'];
            echo "<p><i>Hasil pencarian judul film '$cari': </i></p>";
        }

        $mysqli = new mysqli("localhost", "root", "", "fspw1");
        if ($mysqli->connect_errno) {
            die("Failed to connect to MySQL: " . $mysqli->connect_error);
        }

        $res = $movie->getMovie($cari, $offset, $LIMIT);

        if ($res->num_rows == 0) {
            echo "No movies found!";
        } else {
            echo "<div class='movie-grid'>";
            while ($row = $res->fetch_assoc()) {
                $class = $row['skor'] < 5 ? 'teks-merah' : '';
                echo "<div class='movie-card'>";
                echo "<div class='movie-content'>";
                echo "<div class='movie-title'>" . $row['judul'] . "</div>";
                echo "<div class='movie-meta'>Rilis: " . $row['rilis'] . "</div>";
                echo "<div class='movie-meta'>Genre: " . $row['genre_names'] . "</div>";
                echo "<div class='movie-score $class'>Skor: " . $row['skor'] . "</div>";
                echo "<div class='movie-synopsis'>" . $row['sinopsis'] . "</div>";
                echo "</div>";
                echo "<div class='movie-actions'>";
                echo "<a href='editmovie.php?idmovie=" . $row['idmovie'] . "'>Ubah Data</a>";
                echo "<a href='deletemovie.php?idmovie=" . $row['idmovie'] . "'>Hapus Data</a>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        }


        $res_jumlah = $movie->getMovie($cari, $offset, $LIMIT);
        $jml_data = $res_jumlah->num_rows;

        include "paging.php";
        echo generate_page($jml_data, $LIMIT, $cari, $no_hal);

        $mysqli->close();
    ?>
    <a href="insertmovie.php" class="insert-button">Insert Data</a>

</body>

</html>