# Prueba de países

El backend de la aplicación ha sido desarrollado con:

* PHP 8.2.4
* MySQL 8.1.0

El frontend de la aplicación ha sido desarrollado con:

* Javascript
* JQuery 3.7.1
* Bootstrap 5.3

He seguido la arquitectura Modeo, Vista, Controlador.

## Apache

### Configuración global de apache

##### Archivo httpd.conf

```plaintext
# Ocultar versión y sistema
ServerSignature Off
ServerTokens Prod


# Evita mostrar el número de inodo, el límite MIME multiparte y el proceso hijo a través de la cabecera Etag
FileETag none


# Cierra las conexiones de los clientes que tardan demasiado en enviar su solicitud, 
# como se ve en un ataque de Slowloris. 
# Necesita el módulo 'mod_reqtimeout': Este módulo proporciona una directiva que permite a Apache
# cerrar la conexión si detecta que el cliente no está enviando datos con la suficiente rapidez
RequestReadTimeout header=10-20,MinRate=500 body=20,MinRate=500


# Config proyectos
IncludeOptional "${DIR_PRUEBAS}/*/httpd.conf"
```

### Configuración en el proyecto

##### Archivo httpd.conf

```plaintext
# Define variables
Define PROJECT_PORT "8081"
Define PROJECT_ROOT "paises-auren"
Define DOCUMENT_ROOT "${DIR_PRUEBAS}/${PROJECT_ROOT}"


# Bloquea el acceso a archivos y directorios de Git y otros que no deben ser públicos
RedirectMatch 404 /\.git
RedirectMatch 404 /\.vscode
RedirectMatch 404 .conf
RedirectMatch 404 .gitignore
RedirectMatch 404 README.md


IncludeOptional "${DOCUMENT_ROOT}/httpd-vhost.conf"
```

##### Archivo httpd-vhost.conf

```plaintext
Listen 8081


# Configura un VirtualHost con un puerto asignado para cada proyecto
<VirtualHost *:8081>
    # ServerName localhost


    # DocumentRoot: The directory out of which you will serve your
    # documents. By default, all requests are taken from this directory, but
    # symbolic links and aliases may be used to point to other locations.
    DocumentRoot "${DOCUMENT_ROOT}"


    # Directorio raiz del proyecto
    <Directory "${DOCUMENT_ROOT}">
        # Possible values for the Options directive are "None", "All",
        # or any combination of:
        #   Indexes Includes FollowSymLinks SymLinksifOwnerMatch ExecCGI MultiViews
        #
        # Note that "MultiViews" must be named *explicitly* --- "Options All"
        # doesn't give it to you.
        #
        # The Options directive is both complicated and important.  Please see
        # http://httpd.apache.org/docs/2.4/mod/core.html#options
        # for more information.
        # -Indexes Desactiva listado de directorios
        Options -Indexes +FollowSymLinks +Includes +ExecCGI

        # AllowOverride controls what directives may be placed in .htaccess files.
        # It can be "All", "None", or any combination of the keywords:
        #   AllowOverride FileInfo AuthConfig Limit
        AllowOverride None

        # Controls who can get stuff from this server.
        Require all denied
        # Require all granted

        # Bloquea que se suban contenido de más de 1MB
        LimitRequestBody 1024000

        # Permite que se acceda al index.php indicándolo en la url o no
        <FilesMatch "^(index\.php|)$">
            Require all granted
        </FilesMatch>
    </Directory>

    # Directorios de controlador y vista
    <DirectoryMatch "^/(controller|view)">
        Require all granted
    </DirectoryMatch>


    # Customizable error responses come in three flavors:
    # 1) plain text 2) local redirects 3) external redirects
    #
    # Some examples:
    #ErrorDocument 500 "The server made a boo boo."
    #ErrorDocument 404 "/missing.html"
    #ErrorDocument 404 "/cgi-bin/missing_handler.pl"
    #ErrorDocument 402 http://www.example.com/subscription_info.html

    ErrorDocument 403 "403 - Acceso denegado al recurso"
    ErrorDocument 404 "404 - No se ha encontrado el recurso"
</VirtualHost>
```

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
