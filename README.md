# TPE-parte3-Web2


#Endpoints

1) 'api/prestamos': Con este endpoint podemos llamar al metodo GET el cual nos va a retornar todos los prestamos cargados en nuestra base de datos. A su vez podemos pasar como parametro el filtro "devueltos", podemos ordenar los prestamos por algunos de sus campos (id_usuario, id_libro, fecha_prestamo, fecha_devolucion, devuelto) y elegir si queremos ordenarlos de forma ascendente o descendente pasando como parametro "direction". A su vez la api cuenta con un paginado el cual se puede acceder pasando como parametro "page". Por defecto el valor es 1.

2) 'api/prestamos/:id': Con este endpoint podemos llamar al metodo GET el cual nos va a retornar un prestamo con un id especifico.

3) 'api/prestamos/:id': Con este endpoint podemos llamar al metodo DELETE el cual va a eliminar un prestamo con el id especificado.

4) 'api/prestamos': Con este endpoing podemos llamar al metodo POST el cual pasandole datos en el body de la request podemos insertar un prestamo en la base de datos.

5) 'api/prestamos/:id': Con este endpoint podemos llamar al metodo PUT el cual pasandole datos en el body de la request y el id del prestamo especificado, podemos modificar los datos del mismo. 
