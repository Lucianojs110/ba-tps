



## BACKEND TPS

Luego de clonar el repositorio ejecutar los siguientes comandos 

1) Instalar dependencias
     
       composer update
     
3) Crea la base de datos en tu gestor de base de datos.

4) Edita el archivo .env.example con los datos de tu conexion de base de datos y renombralo como .env setear el valor APP_DEBUG en false.

5) Hacer las migraciones -> esto creara la tablas en la base de datos
     
       php artisan migrate
     
6) Ejecutamos el seed -> esto inserta datos en la tablas de dicha bd
   
       php artisan db:seed
   
   creara el los roles administrador, pyme y eae
   
   usuario: admin@admin.com con clave: admin123 y rol administrador
   
7)  Generar la nueva APP_KEY en en el archivo .env
   
        php artisan key:generate 
   
      esto generara una clave en el archivo .env 
 
8) Incializar el servidor

       php artisan serve
   

