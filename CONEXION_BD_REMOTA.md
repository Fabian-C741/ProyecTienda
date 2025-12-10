# 🔄 Gestión de Base de Datos Local vs Remota

## 📌 Problema que resuelve

Antes tenías **DOS bases de datos separadas**:
- Una en tu computadora (local) con datos de prueba
- Otra en Hostinger (producción) con datos reales

Esto causaba que:
- ❌ Los cambios en local no se veían en producción
- ❌ Tenías que crear manualmente las mismas tiendas en ambos lados
- ❌ Error "slug ya existe" cuando intentabas crear algo que ya existía en el otro lado

## ✅ Solución

Ahora puedes **elegir** a qué base de datos conectarte:

### Opción 1: Trabajar con BD Local (desarrollo/pruebas)
```powershell
.\cambiar-bd.ps1 local
cd backend
php artisan config:clear
php artisan serve
```

**Ventajas:**
- ✅ Puedes hacer pruebas destructivas sin miedo
- ✅ Más rápido (no depende de internet)
- ✅ Puedes borrar/recrear datos libremente

**Desventajas:**
- ❌ Los datos NO están sincronizados con producción
- ❌ Tienes que crear tiendas de prueba manualmente

---

### Opción 2: Trabajar con BD Remota (producción compartida)
```powershell
.\cambiar-bd.ps1 remote
cd backend
php artisan config:clear
php artisan serve
```

**Ventajas:**
- ✅ **MISMO sistema que producción** (datos compartidos)
- ✅ Si creas una tienda en local, existe también en la web
- ✅ No hay duplicados ni desincronización
- ✅ Todos trabajan con los mismos datos

**Desventajas:**
- ⚠️ **Trabajas con datos REALES** - ten cuidado
- ⚠️ Si borras algo, se borra para todos
- ⚠️ Requiere conexión a internet

---

## 🚀 Configuración Inicial

### 1. Obtener datos de conexión de Hostinger

Ve al panel de Hostinger → **Bases de datos MySQL** y copia:

- **DB_HOST**: generalmente `localhost` o `127.0.0.1`
- **DB_DATABASE**: algo como `u275467800_tienda`
- **DB_USERNAME**: algo como `u275467800_admin`
- **DB_PASSWORD**: tu contraseña de MySQL

### 2. Configurar `.env.remote`

Edita el archivo `backend\.env.remote` y reemplaza:

```env
DB_HOST=localhost
DB_DATABASE=u275467800_nombre_real_aqui
DB_USERNAME=u275467800_usuario_real_aqui
DB_PASSWORD=contraseña_real_aqui
```

### 3. Copiar tu APP_KEY

Ejecuta en producción:
```bash
ssh -p 65002 u275467800@br-asc-web1885.main-hosting.eu
cd domains/ingreso-tienda.kcrsf.com/public_html/backend
cat .env | grep APP_KEY
```

Copia el valor y pégalo en `.env.remote`:
```env
APP_KEY=base64:EL_VALOR_QUE_COPIASTE_AQUI
```

---

## 📖 Uso Diario

### Desarrollo normal (pruebas locales):
```powershell
.\cambiar-bd.ps1 local
cd backend
php artisan serve
```

### Trabajar con producción:
```powershell
.\cambiar-bd.ps1 remote
cd backend
php artisan config:clear
php artisan serve
```

### Ver a qué BD estás conectado:
```powershell
cd backend
php artisan tinker
DB::connection()->getDatabaseName();
```

---

## ⚠️ Advertencias Importantes

### Al usar BD Remota:

1. **Ten cuidado con migraciones:**
   ```bash
   # ❌ NO ejecutes esto en remoto sin saber qué hace
   php artisan migrate:fresh
   
   # ✅ Mejor solo ejecuta migraciones nuevas
   php artisan migrate
   ```

2. **Ten cuidado con seeders:**
   ```bash
   # ❌ NO ejecutes esto (borra todo y recrea)
   php artisan db:seed
   
   # ✅ Solo si sabes exactamente qué datos crea
   php artisan db:seed --class=NombreEspecifico
   ```

3. **Siempre confirma antes de borrar:**
   - Al eliminar una tienda
   - Al eliminar productos
   - Al modificar usuarios

---

## 🔐 Seguridad

### ✅ NUNCA subas a Git:
- `.env` (tu archivo activo)
- `.env.remote` (contiene contraseñas reales)
- `.env.local` (si lo creas)

Estos archivos ya están en `.gitignore`, pero verifica:
```bash
git status
# No deben aparecer archivos .env*
```

### ✅ Solo sube a Git:
- `.env.example` (plantilla sin contraseñas)
- `.env.production.example` (plantilla de producción)

---

## 🆘 Solución de Problemas

### Error "Access denied for user"
→ Verifica las credenciales en `.env.remote`

### Error "SQLSTATE[HY000] [2002] Connection refused"
→ Verifica que DB_HOST sea correcto (prueba con `127.0.0.1` o `localhost`)

### Los cambios no se reflejan
→ Limpia la caché:
```bash
php artisan config:clear
php artisan cache:clear
```

### No sé a qué BD estoy conectado
→ Ejecuta:
```bash
php artisan tinker
echo DB::connection()->getDatabaseName();
```

---

## 📚 Recomendación

### Para ti (desarrollador principal):
**Usa BD Remota** para que todo esté sincronizado automáticamente.

### Para colaboradores:
**Usa BD Local** para pruebas, y solo cambia a remota cuando necesites ver datos reales.

---

## 🎯 Resultado Final

Con esta configuración:

✅ Creas una tienda en local → **Aparece automáticamente en la web**  
✅ Un cliente se registra en la web → **Lo ves en tu local**  
✅ Borras un producto en local → **Se borra también en producción**  
✅ **NO más errores de "slug ya existe"** por duplicados  
✅ **Una sola fuente de verdad** para todos los datos  

---

**Creado el:** 10 de diciembre de 2025  
**Sistema:** Multi-tenant con arquitectura PATH-based
