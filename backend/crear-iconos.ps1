# Script PowerShell para crear iconos PNG placeholder
# Los iconos reales deben ser creados con herramientas de diseño gráfico

# Crear un SVG optimizado para conversión
$svgContent = @"
<svg width="512" height="512" xmlns="http://www.w3.org/2000/svg">
  <rect width="512" height="512" fill="#2563eb" rx="100"/>
  <g fill="#fff">
    <path d="M150 180h212l-15 100H165l-15-100z" stroke="#fff" stroke-width="8" fill="none"/>
    <circle cx="200" cy="340" r="25"/>
    <circle cx="320" cy="340" r="25"/>
    <circle cx="100" cy="180" r="30" fill="none" stroke="#fff" stroke-width="8"/>
    <line x1="130" y1="180" x2="150" y2="180" stroke="#fff" stroke-width="8"/>
  </g>
</svg>
"@

# Guardar SVG temporalmente
$svgContent | Out-File -FilePath "temp-icon.svg" -Encoding UTF8

Write-Host "✅ SVG creado: temp-icon.svg"
Write-Host ""
Write-Host "📝 INSTRUCCIONES PARA CREAR LOS ICONOS PNG:"
Write-Host "==========================================="
Write-Host ""
Write-Host "OPCIÓN 1 - Usar herramienta online (RECOMENDADO):"
Write-Host "1. Abre: https://svgtopng.com/"
Write-Host "2. Sube el archivo 'icon.svg' de la carpeta public"
Write-Host "3. Genera dos versiones: 192x192 y 512x512"
Write-Host "4. Descárgalas como 'icon-192.png' y 'icon-512.png'"
Write-Host "5. Cópialas a: backend/public/"
Write-Host ""
Write-Host "OPCIÓN 2 - Usar el archivo HTML incluido:"
Write-Host "1. Abre 'generar-iconos.html' en tu navegador"
Write-Host "2. Haz clic en los botones de descarga"
Write-Host "3. Guarda los archivos en backend/public/"
Write-Host ""
Write-Host "OPCIÓN 3 - Crear iconos personalizados:"
Write-Host "1. Usa Canva, Figma o Adobe Illustrator"
Write-Host "2. Crea un diseño de 512x512 con tu logo"
Write-Host "3. Exporta en 192x192 y 512x512"
Write-Host "4. Guárdalos como PNG en backend/public/"
Write-Host ""
Write-Host "⚠️  MIENTRAS TANTO: La app usará el SVG como fallback"
