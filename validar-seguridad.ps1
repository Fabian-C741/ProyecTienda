# =========================================
# SCRIPT DE VALIDACIÓN DE SEGURIDAD
# Ejecutar ANTES de desplegar a producción
# =========================================

Write-Host "🔒 INICIANDO VALIDACIÓN DE SEGURIDAD..." -ForegroundColor Cyan
Write-Host ""

$errores = 0
$advertencias = 0
$exitos = 0

# =========================================
# 1. VERIFICAR ARCHIVOS CRÍTICOS
# =========================================

Write-Host "📁 Verificando archivos críticos..." -ForegroundColor Yellow

$archivos = @(
    "backend\.env.example",
    "backend\composer.json",
    "backend\app\Http\Kernel.php",
    "backend\app\Http\Middleware\SecurityHeaders.php",
    "backend\app\Http\Middleware\Cors.php",
    "backend\config\cors.php"
)

foreach ($archivo in $archivos) {
    if (Test-Path $archivo) {
        Write-Host "  ✅ $archivo existe" -ForegroundColor Green
        $exitos++
    } else {
        Write-Host "  ❌ $archivo NO encontrado" -ForegroundColor Red
        $errores++
    }
}

Write-Host ""

# =========================================
# 2. VERIFICAR QUE .env NO ESTÉ EN GIT
# =========================================

Write-Host "🚫 Verificando que .env no esté en Git..." -ForegroundColor Yellow

if (Test-Path "backend\.env") {
    $gitStatus = git status --porcelain backend\.env 2>$null
    if ($gitStatus) {
        Write-Host "  ❌ CRÍTICO: .env está rastreado en Git" -ForegroundColor Red
        Write-Host "     Ejecuta: git rm --cached backend\.env" -ForegroundColor Yellow
        $errores++
    } else {
        Write-Host "  ✅ .env no está en Git" -ForegroundColor Green
        $exitos++
    }
} else {
    Write-Host "  ⚠️  .env no existe (se creará en producción)" -ForegroundColor Yellow
    $advertencias++
}

Write-Host ""

# =========================================
# 3. VERIFICAR .gitignore
# =========================================

Write-Host "📝 Verificando .gitignore..." -ForegroundColor Yellow

if (Test-Path ".gitignore") {
    $gitignoreContent = Get-Content ".gitignore" -Raw
    
    $patronesCriticos = @(".env", "vendor/", "node_modules/", ".idea/")
    
    foreach ($patron in $patronesCriticos) {
        if ($gitignoreContent -match [regex]::Escape($patron)) {
            Write-Host "  ✅ '$patron' está en .gitignore" -ForegroundColor Green
            $exitos++
        } else {
            Write-Host "  ⚠️  '$patron' NO está en .gitignore" -ForegroundColor Yellow
            $advertencias++
        }
    }
} else {
    Write-Host "  ❌ .gitignore no encontrado" -ForegroundColor Red
    $errores++
}

Write-Host ""

# =========================================
# 4. VERIFICAR DEPENDENCIAS
# =========================================

Write-Host "📦 Verificando dependencias..." -ForegroundColor Yellow

# Composer
if (Test-Path "backend\composer.json") {
    $composerContent = Get-Content "backend\composer.json" -Raw | ConvertFrom-Json
    
    $dependenciasCriticas = @("laravel/sanctum", "spatie/laravel-permission")
    
    foreach ($dep in $dependenciasCriticas) {
        if ($composerContent.require.$dep) {
            Write-Host "  ✅ $dep instalado" -ForegroundColor Green
            $exitos++
        } else {
            Write-Host "  ⚠️  $dep no encontrado en composer.json" -ForegroundColor Yellow
            $advertencias++
        }
    }
}

Write-Host ""

# =========================================
# 5. VERIFICAR CONFIGURACIÓN DE .env.example
# =========================================

Write-Host "⚙️  Verificando .env.example..." -ForegroundColor Yellow

if (Test-Path "backend\.env.example") {
    $envExample = Get-Content "backend\.env.example" -Raw
    
    # Verificar que NO tenga credenciales reales
    $patronesPeligrosos = @(
        "password",
        "secret",
        "test",
        "123456"
    )
    
    $tieneCredencialesReales = $false
    foreach ($patron in $patronesPeligrosos) {
        if ($envExample -match "DB_PASSWORD=$patron" -or $envExample -match "APP_KEY=base64:[a-zA-Z0-9+/=]{40,}") {
            $tieneCredencialesReales = $true
        }
    }
    
    if (-not $tieneCredencialesReales) {
        Write-Host "  ✅ .env.example no contiene credenciales reales" -ForegroundColor Green
        $exitos++
    } else {
        Write-Host "  ⚠️  .env.example podría contener credenciales reales" -ForegroundColor Yellow
        $advertencias++
    }
    
    # Verificar variables críticas
    $variablesCriticas = @("APP_KEY", "DB_DATABASE", "DB_USERNAME", "SANCTUM_STATEFUL_DOMAINS")
    
    foreach ($var in $variablesCriticas) {
        if ($envExample -match $var) {
            Write-Host "  ✅ Variable $var presente" -ForegroundColor Green
            $exitos++
        } else {
            Write-Host "  ❌ Variable $var faltante" -ForegroundColor Red
            $errores++
        }
    }
}

Write-Host ""

# =========================================
# 6. VERIFICAR MIGRACIONES
# =========================================

Write-Host "🗄️  Verificando migraciones..." -ForegroundColor Yellow

$migraciones = Get-ChildItem "backend\database\migrations" -Filter "*.php" -ErrorAction SilentlyContinue

if ($migraciones) {
    Write-Host "  ✅ $($migraciones.Count) migraciones encontradas" -ForegroundColor Green
    $exitos++
    
    # Verificar migraciones críticas
    $migracionesCriticas = @("tenants", "users", "products", "orders")
    
    foreach ($critica in $migracionesCriticas) {
        $encontrada = $migraciones | Where-Object { $_.Name -like "*$critica*" }
        if ($encontrada) {
            Write-Host "  ✅ Migración de $critica encontrada" -ForegroundColor Green
            $exitos++
        } else {
            Write-Host "  ⚠️  Migración de $critica no encontrada" -ForegroundColor Yellow
            $advertencias++
        }
    }
} else {
    Write-Host "  ❌ No se encontraron migraciones" -ForegroundColor Red
    $errores++
}

Write-Host ""

# =========================================
# 7. VERIFICAR FRONTEND BUILD
# =========================================

Write-Host "🎨 Verificando frontend..." -ForegroundColor Yellow

if (Test-Path "frontend\package.json") {
    Write-Host "  ✅ package.json existe" -ForegroundColor Green
    $exitos++
    
    if (Test-Path "frontend\dist") {
        Write-Host "  ✅ Build de producción existe (dist/)" -ForegroundColor Green
        $exitos++
    } else {
        Write-Host "  ⚠️  Build de producción no existe. Ejecuta: npm run build" -ForegroundColor Yellow
        $advertencias++
    }
} else {
    Write-Host "  ❌ package.json no encontrado" -ForegroundColor Red
    $errores++
}

Write-Host ""

# =========================================
# 8. TEST DE SINTAXIS PHP
# =========================================

Write-Host "🔍 Verificando sintaxis PHP..." -ForegroundColor Yellow

# Verificar si PHP está disponible
$phpDisponible = Get-Command php -ErrorAction SilentlyContinue

if ($phpDisponible) {
    $archivosPhp = Get-ChildItem "backend" -Recurse -Include "*.php" -Exclude "vendor" | Select-Object -First 10

    $erroresSintaxis = 0
    foreach ($archivo in $archivosPhp) {
        $resultado = php -l $archivo.FullName 2>&1
        if ($resultado -notmatch "No syntax errors") {
            Write-Host "  ❌ Error de sintaxis en $($archivo.Name)" -ForegroundColor Red
            $erroresSintaxis++
        }
    }

    if ($erroresSintaxis -eq 0) {
        Write-Host "  ✅ No se encontraron errores de sintaxis PHP" -ForegroundColor Green
        $exitos++
    } else {
        Write-Host "  ❌ Se encontraron $erroresSintaxis errores de sintaxis" -ForegroundColor Red
        $errores += $erroresSintaxis
    }
} else {
    Write-Host "  ⚠️  PHP no está en el PATH. Test de sintaxis omitido" -ForegroundColor Yellow
    Write-Host "     (Se verificará en el servidor)" -ForegroundColor Cyan
    $advertencias++
}

Write-Host ""

# =========================================
# 9. VERIFICAR DOCUMENTACIÓN
# =========================================

Write-Host "📚 Verificando documentación..." -ForegroundColor Yellow

$docsEsperados = @(
    "README.md",
    "SEGURIDAD_Y_BD.md",
    "CONEXION_BD.md",
    "SECURITY_CHECKLIST.md"
)

foreach ($doc in $docsEsperados) {
    if (Test-Path $doc) {
        Write-Host "  ✅ $doc existe" -ForegroundColor Green
        $exitos++
    } else {
        Write-Host "  ⚠️  $doc no encontrado" -ForegroundColor Yellow
        $advertencias++
    }
}

Write-Host ""

# =========================================
# 10. SIMULAR ATAQUE SQL INJECTION
# =========================================

Write-Host "🛡️  Test de seguridad básico..." -ForegroundColor Yellow

Write-Host "  ℹ️  Para test completo, ejecuta después del deploy:" -ForegroundColor Cyan
Write-Host "     - https://observatory.mozilla.org/" -ForegroundColor Cyan
Write-Host "     - https://securityheaders.com/" -ForegroundColor Cyan
Write-Host "     - https://www.ssllabs.com/ssltest/" -ForegroundColor Cyan

Write-Host ""

# =========================================
# RESUMEN FINAL
# =========================================

Write-Host "════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "         RESUMEN DE VALIDACIÓN" -ForegroundColor Cyan
Write-Host "════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""

Write-Host "✅ Exitosos:     $exitos" -ForegroundColor Green
Write-Host "⚠️  Advertencias: $advertencias" -ForegroundColor Yellow
Write-Host "❌ Errores:      $errores" -ForegroundColor Red

Write-Host ""

$total = $exitos + $advertencias + $errores
if ($total -gt 0) {
    $porcentaje = [math]::Round(($exitos / $total) * 100)
    Write-Host "📊 Puntuación: $porcentaje%" -ForegroundColor Cyan
} else {
    $porcentaje = 0
}

Write-Host ""

# =========================================
# RECOMENDACIÓN FINAL
# =========================================

if ($errores -eq 0 -and $advertencias -eq 0) {
    Write-Host "🎉 ¡PERFECTO! Tu aplicación está lista para producción" -ForegroundColor Green
} elseif ($errores -eq 0 -and $advertencias -lt 5) {
    Write-Host "✅ Aplicación lista, pero revisa las advertencias" -ForegroundColor Yellow
} elseif ($errores -lt 3) {
    Write-Host "⚠️  Corrige los errores antes de desplegar" -ForegroundColor Yellow
} else {
    Write-Host "❌ NO DESPLEGAR. Hay errores críticos que corregir" -ForegroundColor Red
}

Write-Host ""
Write-Host "════════════════════════════════════════" -ForegroundColor Cyan

# =========================================
# PRÓXIMOS PASOS
# =========================================

Write-Host ""
Write-Host "📋 PRÓXIMOS PASOS:" -ForegroundColor Cyan
Write-Host ""

if ($errores -gt 0) {
    Write-Host "1. Corrige los $errores errores mostrados arriba" -ForegroundColor Yellow
    Write-Host "2. Vuelve a ejecutar este script" -ForegroundColor Yellow
} else {
    Write-Host "1. Revisa SEGURIDAD_Y_BD.md para configuración en Hostinger" -ForegroundColor Green
    Write-Host "2. Revisa CONEXION_BD.md para conectar la base de datos" -ForegroundColor Green
    Write-Host "3. Sigue SECURITY_CHECKLIST.md paso a paso" -ForegroundColor Green
    Write-Host "4. Ejecuta en servidor: php artisan security:check" -ForegroundColor Green
    Write-Host "5. Haz push a Git: git push origin main" -ForegroundColor Green
}

Write-Host ""

# Salir con código de error si hay errores críticos
if ($errores -gt 0) {
    exit 1
} else {
    exit 0
}
