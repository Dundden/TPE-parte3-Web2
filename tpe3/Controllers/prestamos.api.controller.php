<?php
require_once './Models/api.model.php';
require_once './Views/json.view.php';

class PrestamosApiController {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new ApiModel();
        $this->view = new JSONView();
    }

    public function GetPrestamos($req, $res) {

        $filtrarDevueltos = null; //Filtro por default
        $page = 1; //Pagina por defecto
        $pageItems = 3; //Total de Items a mostrar por Pagina.
        $orderBy = false; //Orden por default
        $direction = 'asc';  //Direccion por default

        if(isset($req->query->devueltos)) {
            if($req->query->devueltos == true || $req->query->devueltos == false){
                $filtrarDevueltos = $req->query->devueltos;
            } 
        }

        if(isset($req->query->direction)){
            if($req->query->direction == 'desc'){
                $direction = $req->query->direction;
            } 
        }

        if(isset($req->query->orderBy)){
            $orderBy = $req->query->orderBy;
        }

        if(isset($req->query->page)){
            $page = $req->query->page;
        }

        if($page > $this->model->GetTotalPages($pageItems) || $page < 1){
            return $this->view->response("Pagina invalida", 400);
        }
        
        $offset = ($page - 1) * $pageItems;

        $prestamos = $this->model->GetPrestamos($orderBy, $direction, $page, $pageItems, $offset, $filtrarDevueltos);
        return $this->view->response($prestamos);

    }

    public function GetPrestamoById($req, $res) {
    
        $id = $req->params->id;

        $prestamo = $this->model->GetPrestamoById($id);

        if(!$prestamo) {
            return $this->view->response("El prestamo con el id=$id no existe", 404);
        }

        return $this->view->response($prestamo);
    }

    public function RemovePrestamo($req, $res) {
        $id = $req->params->id;

        $prestamo = $this->model->GetPrestamoById($id);

        if (!$prestamo) {
            return $this->view->response("El prestamo con el id=$id no existe", 404);
        }

        $this->model->RemovePrestamo($id);

        $this->view->response("El prestamo con el id=$id se eliminó con éxito");
    }

    public function InsertPrestamo($req, $res) {

        $devuelto = 0;

        if (empty($req->body->id_usuario) || empty($req->body->id_libro) || empty($req->body->fecha_prestamo) || empty($req->body->fecha_devolucion)) {
            return $this->view->response('Faltan completar datos', 400);
        }

        if(isset($req->body->devuelto)){
            if($req->body->devuelto >= 0 && $req->body->devuelto <= 1){
                $devuelto = $req->body->devuelto;
            } 
        }

        $id_usuario = $req->body->id_usuario;       
        $id_libro = $req->body->id_libro;       
        $fecha_prestamo = $req->body->fecha_prestamo; 
        $fecha_devolucion = $req->body->fecha_devolucion;
       
        $usuario = $this->model->GetUsuarioById($id_usuario);
        $libro = $this->model->GetLibroById($id_libro);

        if (!$usuario) {
            return $this->view->response("El usuario con el id=$id_usuario no existe", 404);
        }
        
        if (!$libro) {
            return $this->view->response("El libro con el id=$id_libro no existe", 404);
        }

        $id = $this->model->InsertPrestamo($id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion, $devuelto);

        if (!$id) {
            return $this->view->response("Error al insertar prestamo", 500);
        }

        $prestamo = $this->model->GetPrestamoById($id);
        return $this->view->response($prestamo, 201);
    }

    public function UpdatePrestamo($req, $res) {

        $id = $req->params->id;
        $devuelto = 0;
        
        $prestamo = $this->model->GetPrestamoById($id);
        if (!$prestamo) {
            return $this->view->response("El prestamo con el id=$id no existe", 404);
        }

        if (empty($req->body->id_usuario) || empty($req->body->id_libro) || empty($req->body->fecha_prestamo) || empty($req->body->fecha_devolucion)) {
            return $this->view->response('Faltan completar datos', 400);
        }

        if(isset($req->body->devuelto)){
            if($req->body->devuelto >= 0 && $req->body->devuelto <= 1){
                $devuelto = $req->body->devuelto;
            } 
        }

        $id_usuario = $req->body->id_usuario;       
        $id_libro = $req->body->id_libro;       
        $fecha_prestamo = $req->body->fecha_prestamo; 
        $fecha_devolucion = $req->body->fecha_devolucion;
        
        $usuario = $this->model->GetUsuarioById($id_usuario);
        $libro = $this->model->GetLibroById($id_libro);

        if (!$usuario) {
            return $this->view->response("El usuario con el id=$id_usuario no existe", 404);
        }
        
        if (!$libro) {
            return $this->view->response("El libro con el id=$id_libro no existe", 404);
        }

        $this->model->UpdatePrestamo($id, $id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion, $devuelto);

        $prestamo = $this->model->GetPrestamoById($id);
        $this->view->response($prestamo, 200);
    }

}
