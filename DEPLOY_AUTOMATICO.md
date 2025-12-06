# Deploy Automático: Git → Hostinger

## 🎯 Configuración Recomendada para Mejoras Continuas

Este método te permite:
- ✅ Hacer cambios en tu PC
- ✅ Probar localmente
- ✅ Hacer `git push`
- ✅ Hostinger se actualiza automáticamente

---

## PASO 1: Preparar el Proyecto para Git

### 1.1 Inicializar Git

```powershell
# En la raíz del proyecto
git init
```

### 1.2 Crear repositorio en GitHub

1. Ve a: https://github.com/new
2. Nombre: `tienda-online-multitenant`
3. **IMPORTANTE:** Marca como **Privado** (tiene credenciales sensibles)
4. NO inicialices con README
5. Crea el repositorio

### 1.3 Configurar archivos sensibles

Antes de subir, asegúrate que `.env` NO se suba:

```powershell
# Verificar que .gitignore incluye .env
cat .gitignore | Select-String ".env"
```

### 1.4 Hacer el primer commit

```powershell
git add .
git commit -m "Initial commit: E-commerce multi-tenant platform"

# Conectar con GitHub (reemplaza TU_USUARIO)
git remote add origin https://github.com/TU_USUARIO/tienda-online-multitenant.git

git branch -M main
git push -u origin main
```

---

## PASO 2: Configurar Deploy Automático en Hostinger

### 2.1 Acceder a Git en Hostinger

1. Entra a tu panel de Hostinger (hPanel)
2. Ve a: **Avanzado** → **Git**
3. O busca "Git" en el buscador del panel

### 2.2 Crear Deploy desde GitHub

1. Clic en **"Crear"** o **"Add New Repository"**
2. Selecciona **GitHub**
3. Autoriza a Hostinger para acceder a GitHub
4. Selecciona tu repositorio: `tienda-online-multitenant`
5. Configuración:
   - **Branch:** `main`
   - **Path del Backend:** `public_html/api` 
   - **Path del Frontend:** `public_html`

### 2.3 Configurar dos deploys (Backend y Frontend separados)

**Deploy 1 - Backend:**
- Repository: tu-usuario/tienda-online-multitenant
- Branch: main
- Deploy path: `public_html/api`
- Carpeta del repo: `backend/`

**Deploy 2 - Frontend:**
- Repository: tu-usuario/tienda-online-multitenant  
- Branch: main
- Deploy path: `public_html`
- Carpeta del repo: `frontend/dist/` (después del build)

### 2.4 Configurar Scripts de Deploy

En Hostinger, puedes agregar scripts post-deploy:

**Para Backend (opcional):**
```bash
cd public_html/api
composer install --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```

**Para Frontend:**
```bash
cd ~/repositorio
npm install
npm run build
cp -r frontend/dist/* ~/public_html/
```

---

## PASO 3: Estructura Recomendada para Git

Para facilitar el deploy automático, organiza así:

```
tienda-online-multitenant/
├── backend/           # Laravel
├── frontend/          # React
├── .github/
│   └── workflows/
│       └── deploy.yml # GitHub Actions (opcional)
└── README.md
```

---

## PASO 4: Workflow de Desarrollo

### Cuando hagas cambios:

```powershell
# 1. Hacer cambios en tu código

# 2. Probar localmente
cd backend
php artisan serve

cd ../frontend
npm run dev

# 3. Si funciona, hacer commit
git add .
git commit -m "Descripción de los cambios"
git push

# 4. Hostinger se actualiza automáticamente en 1-2 minutos
```

---

## PASO 5: Variables de Entorno en Hostinger

**IMPORTANTE:** El `.env` no se sube a Git por seguridad.

### Configurar .env en Hostinger:

**Opción A: File Manager**
1. Ve a File Manager
2. Navega a `public_html/api/`
3. Crea `.env` manualmente
4. Copia el contenido de tu `.env.example`
5. Configura las variables de producción

**Opción B: SSH**
```bash
cd public_html/api
cp .env.example .env
nano .env  # Editar y guardar
```

**Variables críticas a configurar:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-subdominio.com

DB_DATABASE=tu_bd
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# Las demás según necesites
```

---

## PASO 6: Deploy del Frontend (Build)

El frontend necesita compilarse antes de subir.

### Opción A: Build local + Git
```powershell
cd frontend
npm run build
git add dist/
git commit -m "Build frontend"
git push
```

### Opción B: Build automático con GitHub Actions

Crea `.github/workflows/deploy.yml`:

```yaml
name: Deploy Frontend

on:
  push:
    branches: [ main ]
    paths:
      - 'frontend/**'

jobs:
  build:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup Node
      uses: actions/setup-node@v2
      with:
        node-version: '18'
    
    - name: Install and Build
      run: |
        cd frontend
        npm install
        npm run build
    
    - name: Deploy to Hostinger
      run: |
        # Script para subir dist/ a Hostinger
```

---

## PASO 7: Configurar Webhooks (Opcional)

Para deploy instantáneo:

1. En Hostinger → Git → tu repo
2. Copia el **Webhook URL**
3. En GitHub → Settings → Webhooks
4. Pega la URL de Hostinger
5. Events: `push`
6. Activa el webhook

Ahora cada `git push` dispara el deploy automáticamente.

---

## 🔄 FLUJO COMPLETO DE TRABAJO

```
1. Haces cambios en tu PC
   ↓
2. Pruebas localmente (localhost:8000 y :5173)
   ↓
3. git add . && git commit -m "mensaje"
   ↓
4. git push
   ↓
5. GitHub recibe los cambios
   ↓
6. Webhook notifica a Hostinger
   ↓
7. Hostinger hace pull automático
   ↓
8. Scripts post-deploy se ejecutan
   ↓
9. Tu sitio se actualiza en 1-2 minutos ✅
```

---

## ✅ VENTAJAS de este método:

- ✅ Deploy automático en cada push
- ✅ Fácil hacer rollback (volver a versión anterior)
- ✅ Historial completo de cambios
- ✅ Puedes trabajar en branches (dev, staging, production)
- ✅ Colaboración en equipo facilitada
- ✅ CI/CD profesional

---

## 🚨 IMPORTANTE: Seguridad

### Archivos que NUNCA deben estar en Git:

```
❌ .env (con credenciales reales)
❌ vendor/ (se genera con composer)
❌ node_modules/ (se genera con npm)
❌ storage/logs/*.log
❌ Archivos de usuarios subidos
```

El `.gitignore` ya está configurado para evitar esto.

### Crear .env.example para el equipo:

```powershell
# En backend/
cp .env .env.example

# Editar .env.example y remover valores sensibles
# Dejar solo la estructura
```

---

## 📝 Comandos Útiles

```powershell
# Ver status de Git
git status

# Ver historial
git log --oneline

# Crear branch para nueva feature
git checkout -b feature/nueva-funcionalidad

# Volver a versión anterior
git revert HEAD

# Ver diferencias
git diff

# Actualizar desde GitHub
git pull
```

---

## 🎯 PRÓXIMO PASO:

1. **Ahora:** Sube a GitHub
2. **Después:** Configura Git en Hostinger
3. **Prueba:** Haz un cambio pequeño y push
4. **Verifica:** Que se actualizó en Hostinger

¿Listo para empezar?
