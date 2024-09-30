<?php
require_once("movie.php");
$mysqli = new mysqli("localhost", "root", "", "fspw1");
if ($mysqli->connect_errno) {
    die ("Failed to connect to MySQL: " . $mysqli->connect_error);
}
    $judul = $_POST['judul'];
    $rilis = $_POST['rilis']; 
    $skor = $_POST['skor'];
    $sinopsis = $_POST['sinopsis'];
    $serial = $_POST['serial'];
    $arr_genre = $_POST['genre'];

    $movie = new Movie();

    $arr_movie = [
        'judul' => $judul,
        'rilis' => $rilis,
        'skor' => $skor,
        'sinopsis' => $sinopsis,
        'serial' => $serial
    ];

    $last_id = $movie->addMovie($last_id);

    if($last_id) {
        $sql = "insert into genre_has_movie values (?,?)";
        foreach($arr_genre as $idgenre) {
            $stmt = $mysqli->prepare($sql);
            $stmt-> bind_param("ii", $idgenre, $last_id);
            $stmt->execute();
        }
    }

    if($last_id){
        $arr_peran = $_POST['peran'];
        $sql = "Insert into movie_has_pemain (movie_idmovie, pemain_idpemain, peran)   VALUES (?, ?, ?)";

        foreach($arr_peran as $idpemain=>$peran) {
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('iis',$last_id, $idpemain, $peran);
            $stmt->execute();
        }
    }   

    if($last_id){
        $poster = $_FILES['poster'];
        foreach($poster['name'] as $key=>$name_poster) {
            if(!empty($name_poster)) {
                $ext = pathinfo($name_poster, PATHINFO_EXTENSION);
                
                $sql = "insert into gambar (movie_idmovie, extention) values (?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt-> bind_param("is", $last_id, $ext);
                $stmt->execute();

                $idgambar = $stmt->insert_id;
                move_uploaded_file($poster['tmp_name'][$key], "image/".$idgambar.".".$ext);
            }
        }
    }

    $stmt->close();
    $mysqli->close();
    header("location: index.php");
?>