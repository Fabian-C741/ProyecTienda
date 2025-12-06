# Inicializar Git y subir a GitHub

## Paso 1: Crear repositorio en GitHub

1. Ve a https://github.com/new
2. Nombre: `tienda-online-multitenant`
3. Descripción: "Plataforma e-commerce multi-tenant con Laravel y React"
4. Privado o Público (tu eliges)
5. NO inicialices con README (ya lo tienes)
6. Clic en "Create repository"

## Paso 2: Inicializar Git local

```powershell
# En la raíz del proyecto
git init
git add .
git commit -m "Initial commit: Plataforma e-commerce multi-tenant completa"
```

## Paso 3: Conectar con GitHub

```powershell
# Reemplaza TU_USUARIO con tu usuario de GitHub
git remote add origin https://github.com/TU_USUARIO/tienda-online-multitenant.git

# Subir a GitHub
git branch -M main
git push -u origin main
```

## Paso 4: Para futuras actualizaciones

```powershell
# Después de hacer cambios
git add .
git commit -m "Descripción de los cambios"
git push
```

## Ventajas de tener el código en GitHub:

✅ Respaldo seguro en la nube
✅ Control de versiones
✅ Puedes clonar en cualquier PC
✅ Facilita colaboración
✅ Portfolio profesional
✅ Deploy automático (opcional)

## IMPORTANTE:

**Nunca subas archivos sensibles:**
- ❌ `.env` con credenciales reales
- ❌ `vendor/` (se genera con composer install)
- ❌ `node_modules/` (se genera con npm install)

El `.gitignore` ya está configurado para evitar esto.
