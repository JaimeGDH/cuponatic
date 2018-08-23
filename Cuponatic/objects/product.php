<?php

include_once '../conn/conexion.php';
// include_once '../product/search.php';

class Product{
 
    // database connection and table name
    private $conn;
 
    // object properties
    public $titulo;
    public $descripcion;
    public $fecha_inicio;
    public $fecha_termino;
    public $precio;
    public $imagen;
    public $vendidos;
    public $tags;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function search($keywords) {
        
        // select all query
        $query =    "SELECT * FROM datos WHERE titulo LIKE ? LIMIT 20";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
     
        // bind
        $stmt->bindParam(1, $keywords);
     
        // execute query
        $stmt->execute();

        return $stmt;
    }

    function registro($keywords, $titulo) {

        $query = "INSERT INTO estadistica (keyword, titulo) VALUES (?, ?)";

        $stmt = $this->conn->prepare($query);

        $keywords=htmlspecialchars(strip_tags($keywords));

        $titulo=htmlspecialchars(strip_tags($titulo));

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $titulo);

        $stmt->execute();
        
        return $stmt;
    }
    
    function ranking() {
        
        $query = "  CREATE TEMPORARY TABLE IF NOT EXISTS tabla_tmp SELECT titulo, COUNT(*) Total
                    FROM estadistica                                        
                    GROUP BY titulo
                    HAVING COUNT(*) > 1
                    ORDER BY Total DESC LIMIT 20";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        
        return $stmt;

    }

    function palabraUsada() {

        $query = "  SELECT estadistica.titulo, estadistica.keyword 
                    FROM estadistica, tabla_tmp 
                    WHERE tabla_tmp.titulo = estadistica.titulo 
                    GROUP BY estadistica.keyword LIMIT 5";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        
        return $stmt;
    }
    
}