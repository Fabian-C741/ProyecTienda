<?php
/**
 * Script temporal para limpiar la base de datos
 * ⚠️ ELIMINAR DESPUÉS DE USAR
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<h2>🗑️ Limpieza de Base de Datos</h2>";
echo "<hr>";

// Mostrar tiendas existentes
echo "<h3>Tiendas actuales:</h3>";
$tenants = \App\Models\Tenant::all(['id', 'name', 'slug', 'email']);

if ($tenants->count() > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Slug</th><th>Email</th></tr>";
    foreach ($tenants as $tenant) {
        echo "<tr>";
        echo "<td>{$tenant->id}</td>";
        echo "<td>{$tenant->name}</td>";
        echo "<td>{$tenant->slug}</td>";
        echo "<td>{$tenant->email}</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p><strong>Total: {$tenants->count()} tiendas</strong></p>";
} else {
    echo "<p>✅ No hay tiendas en la base de datos</p>";
}

// Verificar si se debe ejecutar la limpieza
if (isset($_GET['confirmar']) && $_GET['confirmar'] === 'SI_BORRAR_TODO') {
    echo "<hr>";
    echo "<h3>⚠️ EJECUTANDO LIMPIEZA...</h3>";
    
    // Contar antes
    $totalTenants = \App\Models\Tenant::count();
    $totalRequests = \App\Models\VendorRequest::count();
    $totalUsers = \App\Models\User::where('role', '!=', 'super_admin')->count();
    
    // Eliminar
    \App\Models\Tenant::truncate();
    \App\Models\VendorRequest::truncate();
    \App\Models\User::where('role', '!=', 'super_admin')->delete();
    
    echo "<p style='color: green; font-weight: bold;'>✅ Base de datos limpiada:</p>";
    echo "<ul>";
    echo "<li>🗑️ {$totalTenants} tiendas eliminadas</li>";
    echo "<li>🗑️ {$totalRequests} solicitudes de vendedor eliminadas</li>";
    echo "<li>🗑️ {$totalUsers} usuarios (no super admin) eliminados</li>";
    echo "</ul>";
    
    echo "<p><a href='limpiar-bd.php'>⬅️ Volver</a></p>";
    
} else {
    // Mostrar botón de confirmación
    if ($tenants->count() > 0) {
        echo "<hr>";
        echo "<h3 style='color: red;'>⚠️ ZONA PELIGROSA</h3>";
        echo "<p>Esto eliminará <strong>TODAS</strong> las tiendas, solicitudes de vendedor y usuarios (excepto super admin).</p>";
        echo "<p><a href='limpiar-bd.php?confirmar=SI_BORRAR_TODO' style='background: red; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🗑️ SÍ, BORRAR TODO</a></p>";
    }
}

echo "<hr>";
echo "<p style='color: orange;'>⚠️ <strong>IMPORTANTE:</strong> Elimina este archivo (limpiar-bd.php) después de usarlo por seguridad.</p>";
?>
