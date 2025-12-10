@echo off
chcp 65001 > nul
echo ========================================
echo   📥 DESCARGANDO ARCHIVOS DEL SERVIDOR
echo ========================================
echo.

set SSH_USER=u464516792
set SSH_HOST=br-asc-web1885.main-hosting.eu
set SSH_PORT=65002
set REMOTE_PATH=domains/ingreso-tienda.kcrsf.com/public_html

echo 📂 Descargando controllers...
scp -P %SSH_PORT% -r %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/app/Http/Controllers/DashboardController.php backend/app/Http/Controllers/
scp -P %SSH_PORT% -r %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/app/Http/Controllers/Tenant backend/app/Http/Controllers/
scp -P %SSH_PORT% -r %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/app/Http/Controllers/VendorRequestController.php backend/app/Http/Controllers/

echo 📂 Descargando middleware...
scp -P %SSH_PORT% %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/app/Http/Middleware/SuperAdminMiddleware.php backend/app/Http/Middleware/
scp -P %SSH_PORT% %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/app/Http/Middleware/DetectTenantBySubdomain.php backend/app/Http/Middleware/

echo 📂 Descargando modelos...
scp -P %SSH_PORT% %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/app/Models/VendorRequest.php backend/app/Models/

echo 📂 Descargando vistas...
scp -P %SSH_PORT% -r %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/resources/views/super-admin backend/resources/views/
scp -P %SSH_PORT% -r %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/resources/views/dashboard backend/resources/views/
scp -P %SSH_PORT% -r %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/resources/views/errors backend/resources/views/
scp -P %SSH_PORT% %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/resources/views/vendor-request.blade.php backend/resources/views/
scp -P %SSH_PORT% %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/resources/views/auth/forgot-password.blade.php backend/resources/views/auth/
scp -P %SSH_PORT% %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/resources/views/auth/reset-password.blade.php backend/resources/views/auth/

echo 📂 Descargando migraciones...
scp -P %SSH_PORT% %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/database/migrations/2025_12_09_000001_add_status_to_users_table.php backend/database/migrations/
scp -P %SSH_PORT% %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/database/migrations/2025_12_09_000002_create_vendor_requests_table.php backend/database/migrations/

echo 📂 Descargando seeders...
scp -P %SSH_PORT% %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/database/seeders/SuperAdminSeeder.php backend/database/seeders/

echo 📂 Descargando servicios...
scp -P %SSH_PORT% %SSH_USER%@%SSH_HOST%:%REMOTE_PATH%/backend/app/Services/SecurityService.php backend/app/Services/

echo.
echo ✅ Descarga completada!
echo.
pause
