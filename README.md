# Prueba de países

El backend de la aplicación ha sido desarrollado con:

* PHP 8.2.4
* MySQL 8.1.0

El frontend de la aplicación ha sido desarrollado con:

* Javascript
* JQuery 3.7.1
* Bootstrap 5.3

He seguido la arquitectura Modeo, Vista, Controlador.


## Base de Datos

Las consultas necesarias para desplegar la base de datos están en el directorio ***/doc***.

Todo lo relativo a la base de datos está en el directorio ***/database***.

Los parámetros de conexión con Base de Datos están configurados en la clase **Database**, en el archivo ***Database.php***.

Cabe la posibilidad de conectar, justo antes de realizar la consulta, con los distintos DSN configurados en este archivo, simplemente cuando se crea la instancia de la clase **Conn**, en el archivo *****Conn.php*****, la cual realiza conexión a la base de datos utilizando PDO.


## API externa

La API REST externa que se ha propuesto no funciona correctamente en estos momentos, por lo que he tenido que buscar una API alternativa.

API para realizar las consultas externas: [ipwhois](https://ipwho.is).

La documentación de la API: [Documentación](https://ipwhois.io/documentation).


## Funcionamiento

Este proyecto muestra el país donde se encuentra una IP utilizando una API Rest externa.

Una vez localizada la IP, almacena la información, insertando o actualizando el registro de esa IP en la base de datos.

Posteriormente, se envía la información al frontend y se muestra en el navegador.


### Localizar una IP concreta

Hay que introducir una IP en el buscador ubicado en la parte superior derecha.


### Localizar la IP propia

Hay que hacer clic en el elemento del menú superior con la etiqueta "Mi IP".


### Eliminación de una IP almacenada

Una vez localizada la IP, es posible eliminar el registro de la Base de Datos haciendo clic en el botón "Eliminar".
