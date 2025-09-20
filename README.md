# 🚀 Guía de Instalación y Arranque del Proyecto (Versión Completa)

Sigue estos pasos en orden para configurar tu entorno de desarrollo.

## Resumen de Versiones Requeridas

-   **PHP**: 8.2 o superior.
-   **Servidor de Base de Datos**: MySQL o MariaDB.
-   **Composer**: 2.2 o superior.
-   **Node.js**: 20.x (LTS recomendado).
-   **npm**: 10.x (se instala con Node.js).

---

## Paso 1: Instalar PHP y un Servidor de Base de Datos

### Opción A: Con XAMPP (Recomendado para principiantes)

1. **Descargar e Instalar XAMPP**: Ve a la página oficial de descargas de XAMPP, descarga una versión con PHP 8.2 y sigue los pasos del instalador.
2. **Añadir PHP al PATH de Windows**:
    - Busca la carpeta de PHP dentro de tu instalación de XAMPP (ej. `C:\xampp\php`).
    - Copia esa ruta y añádela a las variables de entorno del sistema en la variable `Path`.
3. **Iniciar los Servicios**: Abre el panel de control de XAMPP y presiona "Start" en los módulos Apache y MySQL.
4. **Verificar PHP**: Abre una nueva terminal y ejecuta `php -v`. Deberías ver la versión 8.2.

### Opción B: Instalación Manual (Sin XAMPP)

1. **Instalar PHP Manualmente**:
    - **Descargar PHP**: Ve a la página oficial de PHP para Windows. Busca la sección de PHP 8.2 y descarga el archivo ZIP de la versión "VS16 x64 Thread Safe".
    - **Crear Carpeta**: Crea una carpeta en tu disco, por ejemplo `C:\php`.
    - **Extraer Archivos**: Extrae todo el contenido del archivo ZIP que descargaste dentro de `C:\php`.
    - **Configurar `php.ini`**: Renombra `php.ini-development` a `php.ini` y activa las extensiones necesarias:
        ```ini
        extension=curl
        extension=fileinfo
        extension=mbstring
        extension=openssl
        extension=pdo_mysql
        ```
    - **Añadir PHP al PATH**: Añade la ruta `C:\php` a tus variables de entorno del sistema en la variable `Path`.
    - **Verificar PHP**: Abre una nueva terminal y ejecuta `php -v`.
2. **Instalar un Servidor de Base de Datos**:
    - Descarga el Instalador de la Comunidad de MySQL desde su página oficial.
    - Durante la instalación, establece una contraseña para el usuario `root`. Anótala.

---

## Paso 2: Instalar Composer

1. **Descargar e Instalar**: Ve a [getcomposer.org](https://getcomposer.org) y usa el instalador (Composer-Setup.exe).
2. **Verificar**: Abre una nueva terminal y ejecuta `composer -v`.

---

## Paso 3: Instalar Node.js y npm (con NVM)

1. **Descargar e Instalar NVM**: Descarga `nvm-setup.zip` desde la página de lanzamientos de [nvm-windows](https://github.com/coreybutler/nvm-windows/releases).
2. **Instalar y Usar Node**:
    ```bash
    nvm install lts
    nvm use 20.x.x # Usa la versión que se acaba de instalar
    ```
3. **Verificar**: Abre una nueva terminal y ejecuta `node -v` y `npm -v`.

---

## Paso 4: Clonar y Configurar el Proyecto

1. **Clonar y Entrar al Proyecto**:
    ```bash
    git clone <URL_DEL_REPOSITORIO>
    cd <NOMBRE_DE_LA_CARPETA_DEL_PROYECTO>
    ```
2. **Copiar Archivo de Entorno**:
    ```bash
    copy .env.example .env
    ```
3. **Configurar el `.env`**:
    - Modifica las credenciales de la base de datos para que coincidan con tu configuración:
        ```ini
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=tu_base_de_datos
        DB_USERNAME=tu_usuario
        DB_PASSWORD=tu_contraseña
        ```
    - Asegúrate de que `DB_DATABASE` exista en tu gestor de base de datos.
4. **Instalar Dependencias**:
    ```bash
    composer install
    npm install
    ```
5. **Generar Clave y Migrar Base de Datos**:
    ```bash
    php artisan key:generate
    php artisan migrate
    ```

---

## Paso 5: Levantar el Proyecto

1. **Terminal 1 (Backend - Laravel)**:
    ```bash
    php artisan serve
    ```
2. **Terminal 2 (Frontend - Vite)**:
    ```bash
    npm run dev
    ```

¡Listo! 🎉 Abre tu navegador y visita [http://127.0.0.1:8000](http://127.0.0.1:8000) para ver tu aplicación funcionando.
