<?php 

require_once("parent.php");

class Movie extends ParentClass {
    public function __construct()
    {
        parent::__construct();
    }

    public function getMovie($cari="", $offset=0, $LIMIT=0) {
        $caripersen = "%".$cari."%";
        if ($LIMIT > 0) {
            $sql = "
                SELECT movie.*, GROUP_CONCAT(genre.nama SEPARATOR ', ') AS genre_names
                FROM movie
                LEFT JOIN genre_has_movie ON movie.idmovie = genre_has_movie.movie_idmovie
                LEFT JOIN genre ON genre_has_movie.genre_idgenre = genre.idgenre
                WHERE judul LIKE ? 
                GROUP BY movie.idmovie
                LIMIT ?,?
                ";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param('sii', $caripersen, $offset, $LIMIT);
        } else {
            $sql = "
                SELECT * from movie WHERE judul LIKE ? 
                ";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param('s', $caripersen);
        }
        
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function addMovie($arr_data) {
        $sql = "insert into movie (judul, rilis, skor, sinopsis, serial) values (?,?,?,?,?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('ssdsi', $arr_data['judul'], $arr_data['rilis'], $arr_data['skor'], $arr_data['sinopsis'], $arr_data['serial']);
        $stmt->execute();

        $last_id = $stmt->insert_id;
        return $last_id;
    }
}

?>