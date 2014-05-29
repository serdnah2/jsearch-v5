jsearch 5
=======

Buscador interno web creado para las personas que se les hace complicado programar o que tienen conocimientos mínimos de software, sin excluir a los desarrolladores web que pueden contribuir con mejoras al código!

Este buscador tiene la opción de buscar por los archivos que existan en un directorio específico, (directorio que puedes configurar)
o buscar por palabras que añadimos en la página "add.html"

Cómo configurar ésta opción?
En el archivo "search.js" que está ubicado en el directorio "js" debes editarlo y hacer un pequeño cambio.
Debes que cambiar el valor de el atributo "automatically" a "true" para que busque dentro del directorio o "false" para que busque por las palabras que hemos añadido

    this.automatically = true ó false
    
Para editar la carpeta de búsqueda debemos ir al directorio "php/toIndex.php" y modificar la variable $folderToFind al nombre de la carpeta que necesites

    $folderToFind = "files/";
    
Nota:
Debes escribir el nombre desde la raíz

Cuando configures el atributo en "true" debes indexar los archivos que existan en el directorio establecido en el archivo "php/toIndex.php" en la variable $folderToFind. Para hacerlo debes ir al archivo "add.html" y dar click en el botón "Indexar archivos"

Nota:
- Si el buscador no agrega ninguún dato, por favor cambia los permisos a los archivos "database.js" y "databasefolder.js"
- Cada vez que añades un nuevo archivo debes que indexar de nuevo el buscador

Para verlo funcionando puedes acceder a este enlace:
[Jsearch](http://www.cornersopensource.com/jsearch_v5)

Muchas gracias por el apoyo brindado!

serdnah2
