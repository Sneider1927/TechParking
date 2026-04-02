# Instrucciones de Despliegue para TechParking

## Preparación del Proyecto
El proyecto ya está optimizado para producción:
- Dependencias instaladas y optimizadas
- Assets compilados
- Configuraciones cacheadas
- Clave de aplicación generada
- Enlace de storage creado

## Pasos para Subir a la Web

### 1. Subir Archivos al Servidor
Sube todo el contenido del proyecto a tu servidor web (excepto las carpetas `.git`, `node_modules` si no las necesitas).

### 2. Configurar el Entorno
- Copia `.env.example` a `.env` en el servidor
- Edita `.env` con la configuración de producción:
  ```
  APP_ENV=production
  APP_DEBUG=false
  APP_URL=https://tu-dominio.com
  DB_CONNECTION=mysql
  DB_HOST=tu-host-db
  DB_PORT=3306
  DB_DATABASE=tu-base-datos
  DB_USERNAME=tu-usuario
  DB_PASSWORD=tu-contraseña
  ```

### 3. Instalar Dependencias en el Servidor
```bash
composer install --no-dev --optimize-autoloader
```

### 4. Generar Clave y Cachear
```bash
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### 5. Ejecutar Migraciones
```bash
php artisan migrate --force
```

### 6. Ejecutar Seeders (si es necesario)
```bash
php artisan db:seed
```

### 7. Configurar Permisos
Asegúrate de que las carpetas `storage` y `bootstrap/cache` sean escribibles por el servidor web.

### 8. Configurar el Servidor Web
- Para Apache: Asegúrate de que `mod_rewrite` esté habilitado y que el `DocumentRoot` apunte a la carpeta `public`.
- Para Nginx: Configura el servidor para que sirva desde `public/index.php`.

## Verificación
- Accede a tu dominio y verifica que la aplicación funcione.
- Revisa los logs en `storage/logs` si hay errores.

## Notas Adicionales
- Asegúrate de que el servidor tenga PHP 8.2 o superior.
- Configura SSL/HTTPS para seguridad.
- Haz backups regulares de la base de datos.