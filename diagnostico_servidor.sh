#!/bin/bash
echo "=== DIAGNÓSTICO RÁPIDO ==="
echo "1. Verificando commit actual:"
git log --oneline -1

echo -e "\n2. Verificando controladores actualizados:"
grep -n "vendedor.productos.index" app/Http/Controllers/Tenant/TenantProductController.php || echo "ERROR: Controlador no actualizado"

echo -e "\n3. Verificando vistas vendedor:"
ls -la resources/views/vendedor/ | head -5

echo -e "\n4. Verificando si existen vistas tenant (no deberían existir):"
ls resources/views/tenant/ 2>/dev/null || echo "OK: carpeta tenant eliminada"

echo -e "\n5. Último error en logs:"
tail -n 20 storage/logs/laravel.log | grep -A 3 "ERROR\|exception" | tail -5