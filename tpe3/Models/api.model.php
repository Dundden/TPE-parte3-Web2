<?php

class ApiModel {
    
    private $db;

    public function __construct() {
       $this->db = new PDO('mysql:host=localhost;dbname=biblioteca;charset=utf8', 'root', '');
    }   
    
    public function GetPrestamos($orderBy, $direction, $page, $pageItems, $offset, $filtrarDevueltos) {

        $sql = 'SELECT * FROM prestamo';

        //Filtrar por devueltos
        if($filtrarDevueltos != null) {
            if($filtrarDevueltos == 'true'){
                $sql .= ' WHERE devuelto = 1';
            }else{
                $sql .= ' WHERE devuelto = 0';
            }     
        }

        //Ordenar por campo de la tabla
        if($orderBy) {
            switch($orderBy) {
                case 'id_usuario':
                    $sql .= ' ORDER BY id_usuario' . " " . $direction;
                    break;
                case 'id_libro':
                    $sql .= ' ORDER BY id_libro' . " " . $direction;
                    break;
                case 'fecha_prestamo':
                    $sql .= ' ORDER BY fecha_prestamo' . " " . $direction;
                    break;
                case 'fecha_devolucion':
                    $sql .= ' ORDER BY fecha_devolucion' . " " . $direction;
                    break;
            }
        }

        //Paginado. Por defecto siempre es pagina 1
        $sql .= ' LIMIT ' . $pageItems . ' OFFSET ' . $offset; 


        //Prepara y ejecuta consulta SQL
        $query = $this->db->prepare($sql);
        $query->execute();
        $prestamos = $query->fetchAll(PDO::FETCH_OBJ);

        //Retornamos prestamos y datos del paginado
        return [
            'data' => $prestamos,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $this->GetTotalPages($pageItems)
            ]
        ];
    }
 
    public function GetPrestamoById($id) {   

        $query = $this->db->prepare('SELECT * FROM prestamo WHERE id_prestamo = ?');
        $query->execute([$id]);   

        $prestamo = $query->fetch(PDO::FETCH_OBJ);

        return $prestamo;
    }

    public function RemovePrestamo($id) {
        $query = $this->db->prepare('DELETE FROM prestamo WHERE id_prestamo = ?');
        $query->execute([$id]);
    }
 
    public function InsertPrestamo($id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion, $devuelto) { 
        $query = $this->db->prepare('INSERT INTO prestamo(id_usuario, id_libro, fecha_prestamo, fecha_devolucion, devuelto) VALUES (?, ?, ?, ?, ?)');
        $query->execute([$id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion, $devuelto]);
    
        $id = $this->db->lastInsertId();
    
        return $id;
    }
 
    function UpdatePrestamo($id, $id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion, $devuelto) {    
        $query = $this->db->prepare('UPDATE prestamo SET id_usuario = ?, id_libro = ?, fecha_prestamo = ?, fecha_devolucion = ?, devuelto = ? WHERE id_prestamo = ?');
        $query->execute([$id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion, $devuelto, $id]);
    }

    public function GetUsuarioById($id) {   

        $query = $this->db->prepare('SELECT * FROM usuario WHERE id_usuario = ?');
        $query->execute([$id]);   

        $usuario = $query->fetch(PDO::FETCH_OBJ);

        return $usuario;
    }

    public function GetLibroById($id) {   

        $query = $this->db->prepare('SELECT * FROM libros WHERE id_libro = ?');
        $query->execute([$id]);   

        $libro = $query->fetch(PDO::FETCH_OBJ);

        return $libro;
    }

    //Funcion para calcular total de paginas
    public function GetTotalPages($pageItems){
        
        $countSql = 'SELECT COUNT(*) FROM prestamo';
        $countQuery = $this->db->prepare($countSql);
    
        $countQuery->execute();
        $totalItems = $countQuery->fetchColumn();

        $totalPages = ceil($totalItems / $pageItems);

        return $totalPages;
    }
}
