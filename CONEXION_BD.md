# 🔒 SCRIPT DE CONEXIÓN SEGURA A BASE DE DATOS

## Paso a Paso para Conectar tu Base de Datos

### 1️⃣ OBTENER CREDENCIALES DE HOSTINGER

1. **Accede a tu panel de Hostinger (hPanel)**
2. Ve a la sección **"Bases de datos"** → **"Bases de datos MySQL"**
3. Verás tu base de datos creada. Anota estos datos:

```
📌 Host: localhost
📌 Puerto: 3306
📌 Nombre de BD: u123456789_nombre_bd
📌 Usuario: u123456789_usuario
📌 Contraseña: [la que generaste]
```

**IMPORTANTE:** 
- En Hostinger, el host SIEMPRE es `localhost` (NO uses 127.0.0.1)
- El puerto SIEMPRE es `3306`
- El nombre de usuario y BD empiezan con `u` + números

---

### 2️⃣ CONFIGURAR .env EN TU SERVIDOR

#### Opción A: Por File Manager (Recomendado para principiantes)

1. Ve a **File Manager** en Hostinger
2. Navega a: `public_html/api/`
3. Busca el archivo `.env.example`
4. **Haz clic derecho** → **"Copy"**
5. Nómbralo: `.env` (sin el .example)
6. **Haz clic derecho en .env** → **"Edit"**
7. Busca la sección de base de datos y modifica:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_tu_nombre_bd_real
DB_USERNAME=u123456789_tu_usuario_real
DB_PASSWORD=tu_contraseña_real_aqui
```

8. **Guarda el archivo** (Ctrl+S o botón Save)
9. **Cierra el editor**

#### Opción B: Por SSH (Más rápido si tienes acceso)

```bash
# Conectar por SSH
ssh u123456789@tudominio.com

# Navegar al directorio
cd public_html/api

# Copiar ejemplo
cp .env.example .env

# Editar
nano .env

# Modificar estas líneas:
# DB_HOST=localhost
# DB_PORT=3306
# DB_DATABASE=u123456789_tu_bd
# DB_USERNAME=u123456789_usuario
# DB_PASSWORD=contraseña_segura

# Guardar y salir: Ctrl+O, Enter, Ctrl+X
```

---

### 3️⃣ GENERAR APP_KEY (CRÍTICO)

La `APP_KEY` es lo que Laravel usa para encriptar datos. **DEBE ser única**.

#### Por SSH:
```bash
cd public_html/api
php artisan key:generate
```

#### Sin SSH (Manual):
```bash
# En tu computadora local (PowerShell):
cd "d:\Proyectos 2\Tienda_online_multiplataformas\backend"
php artisan key:generate --show

# Copia el resultado (ejemplo: base64:xxxxxxxxxxxxxxxxxxxx)
# Ve a File Manager en Hostinger
# Edita .env
# Pega en la línea: APP_KEY=base64:xxxxxxxxxxxxxxxxxxxx
```

---

### 4️⃣ VERIFICAR CONEXIÓN A BASE DE DATOS

#### Método 1: Test Script PHP

Crea este archivo en `public_html/test-db.php`:

```php
<?php

$host = 'localhost';
$port = '3306';
$database = 'u123456789_tu_bd'; // CAMBIA ESTO
$username = 'u123456789_usuario'; // CAMBIA ESTO
$password = 'tu_contraseña'; // CAMBIA ESTO

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ ¡Conexión exitosa a la base de datos!<br>";
    echo "Base de datos: $database<br>";
    echo "Usuario: $username<br>";
    
    // Test de tablas
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<br>Tablas encontradas: " . count($tables) . "<br>";
    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>
```

**Accede a:** `https://tudominio.com/test-db.php`

✅ Si ves "Conexión exitosa", ¡tu BD está conectada!
❌ Si ves un error, revisa las credenciales.

**⚠️ IMPORTANTE:** Después de verificar, **ELIMINA** el archivo `test-db.php` por seguridad.

#### Método 2: Laravel Tinker (Requiere SSH)

```bash
cd public_html/api
php artisan tinker

# Dentro de tinker:
>>> DB::connection()->getPdo();
# Si muestra "PDO {...}", ¡funciona!

>>> DB::select('SELECT DATABASE()');
# Muestra el nombre de tu base de datos

>>> exit
```

---

### 5️⃣ EJECUTAR MIGRACIONES

Una vez conectado, crea las tablas:

#### Por SSH:
```bash
cd public_html/api

# Ejecutar migraciones
php artisan migrate --force

# Crear roles y permisos
php artisan db:seed --class=RolesAndPermissionsSeeder --force
```

#### Sin SSH (phpMyAdmin):
1. Ve a **phpMyAdmin** en Hostinger
2. Selecciona tu base de datos
3. Ve a la pestaña **SQL**
4. Copia y pega el SQL de cada migración manualmente (más tedioso)

---

### 6️⃣ CREAR USUARIO ADMINISTRADOR

#### Por SSH con Tinker:
```bash
php artisan tinker

>>> $admin = \App\Models\User::create([
    'name' => 'Administrador Principal',
    'email' => 'admin@tudominio.com',
    'password' => bcrypt('TuPasswordSeguro123!@#'),
    'is_active' => true,
]);

>>> $admin->assignRole('super_admin');

>>> echo "Usuario creado con ID: " . $admin->id;

>>> exit
```

#### Guarda estas credenciales de forma SEGURA:
```
Email: admin@tudominio.com
Password: TuPasswordSeguro123!@#
```

---

### 7️⃣ VERIFICAR TODO FUNCIONA

#### Test 1: API Health Check
Accede a: `https://tudominio.com/api/health`

Debería responder:
```json
{
  "status": "ok",
  "database": "connected"
}
```

#### Test 2: Login de Admin
```bash
# PowerShell o terminal
curl -X POST https://tudominio.com/api/v1/login `
  -H "Content-Type: application/json" `
  -d '{\"email\":\"admin@tudominio.com\",\"password\":\"TuPasswordSeguro123!@#\"}'
```

Debería responder con un token.

---

## 🔍 SOLUCIÓN DE PROBLEMAS COMUNES

### ❌ Error: "SQLSTATE[HY000] [2002] Connection refused"

**Causa:** Host incorrecto o servidor MySQL apagado

**Solución:**
```env
# Verifica en .env:
DB_HOST=localhost  # NO uses 127.0.0.1 en Hostinger
DB_PORT=3306
```

---

### ❌ Error: "SQLSTATE[HY000] [1045] Access denied for user"

**Causa:** Usuario o contraseña incorrectos

**Solución:**
1. Ve a **Bases de datos** en Hostinger
2. Verifica el nombre de usuario exacto
3. Si no recuerdas la contraseña, **cámbiala**:
   - Clic en el usuario → "Change Password"
   - Genera una nueva contraseña SEGURA
   - Actualiza `.env` con la nueva contraseña

---

### ❌ Error: "SQLSTATE[HY000] [1049] Unknown database"

**Causa:** Nombre de base de datos incorrecto

**Solución:**
```env
# Verifica que DB_DATABASE coincida EXACTAMENTE con el nombre en Hostinger
DB_DATABASE=u123456789_nombre_exacto
```

---

### ❌ Error: "Class 'PDO' not found"

**Causa:** Extensión PDO de PHP no activada

**Solución:**
1. Ve a **Configuración de PHP** en Hostinger
2. Activa: `pdo_mysql`
3. Reinicia el servidor

---

### ❌ Error: "No application encryption key has been set"

**Causa:** APP_KEY no generada

**Solución:**
```bash
# Por SSH:
php artisan key:generate

# O manualmente:
# Genera localmente y copia a .env en servidor
```

---

### ❌ Error: "SQLSTATE[42S02] Table doesn't exist"

**Causa:** Migraciones no ejecutadas

**Solución:**
```bash
# Por SSH:
php artisan migrate --force

# O importa las migraciones manualmente en phpMyAdmin
```

---

## 📊 CHECKLIST DE CONEXIÓN

- [ ] Base de datos creada en Hostinger
- [ ] Usuario de BD creado con permisos
- [ ] Archivo `.env` creado (NO `.env.example`)
- [ ] DB_HOST=localhost (NO 127.0.0.1)
- [ ] DB_PORT=3306
- [ ] DB_DATABASE correcto (u123456789_...)
- [ ] DB_USERNAME correcto
- [ ] DB_PASSWORD correcto
- [ ] APP_KEY generada
- [ ] Test de conexión exitoso
- [ ] Migraciones ejecutadas
- [ ] Usuario admin creado
- [ ] Login funciona

---

## 🆘 ÚLTIMA OPCIÓN: SOPORTE

Si después de todo esto no funciona:

1. **Verifica phpMyAdmin:**
   - Intenta conectar con las mismas credenciales
   - Si phpMyAdmin funciona pero Laravel no, el problema es en `.env`

2. **Revisa logs:**
   ```bash
   # Por SSH
   tail -f storage/logs/laravel.log
   
   # Por File Manager
   # Descarga: storage/logs/laravel.log
   ```

3. **Contacta a Hostinger:**
   - Chat en vivo 24/7
   - Pregunta por problemas de conexión MySQL
   - Pide que verifiquen permisos del usuario

---

## ✅ CONEXIÓN EXITOSA

Si llegaste aquí y todo funciona, ¡felicidades! 🎉

**Próximos pasos:**
1. Cambia la contraseña del admin a algo MÁS seguro
2. Revisa `SECURITY_CHECKLIST.md`
3. Ejecuta `php artisan security:check`
4. ¡Comienza a usar tu plataforma!
