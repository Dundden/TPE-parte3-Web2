<?php
    
    require_once 'libs/router.php';
    require_once 'Controllers/prestamos.api.controller.php';

    $router = new Router();

    #                 endpoint        verbo     controller             metodo
    $router->addRoute('prestamos'      , 'GET',     'PrestamosApiController',   'GetPrestamos');
    $router->addRoute('prestamos/:id'  , 'GET',     'PrestamosApiController',   'GetPrestamoById');
    $router->addRoute('prestamos/:id'  , 'DELETE',  'PrestamosApiController',   'RemovePrestamo');
    $router->addRoute('prestamos'  ,     'POST',    'PrestamosApiController',   'InsertPrestamo');
    $router->addRoute('prestamos/:id'  , 'PUT',     'PrestamosApiController',   'UpdatePrestamo');

    $router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);

?>
