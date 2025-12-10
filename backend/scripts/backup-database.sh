#!/bin/bash

##############################################################################
# SCRIPT DE BACKUP AUTOMÁTICO DE BASE DE DATOS
# Ejecuta backup diario con compresión y retención de 30 días
# Uso: ./backup-database.sh
##############################################################################

# Configuración
BACKUP_DIR="/home/u464516792/backups"
DB_NAME="u464516792_Tienda"
DB_USER="u464516792_Tienda"
DB_PASSWORD="tu_password_aqui"  # CAMBIAR EN PRODUCCIÓN
DATE=$(date +"%Y-%m-%d_%H-%M-%S")
BACKUP_FILE="$BACKUP_DIR/backup_$DATE.sql"
RETENTION_DAYS=30

# Crear directorio de backups si no existe
mkdir -p "$BACKUP_DIR"

# Ejecutar backup con mysqldump
echo "[$(date)] Iniciando backup de base de datos..."
mysqldump -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$BACKUP_FILE"

if [ $? -eq 0 ]; then
    echo "[$(date)] ✅ Backup exitoso: $BACKUP_FILE"
    
    # Comprimir backup
    gzip "$BACKUP_FILE"
    echo "[$(date)] ✅ Comprimido: $BACKUP_FILE.gz"
    
    # Calcular tamaño
    SIZE=$(du -h "$BACKUP_FILE.gz" | cut -f1)
    echo "[$(date)] 📦 Tamaño: $SIZE"
    
    # Eliminar backups antiguos (más de 30 días)
    find "$BACKUP_DIR" -name "backup_*.sql.gz" -type f -mtime +$RETENTION_DAYS -delete
    echo "[$(date)] 🗑️  Backups antiguos eliminados (>$RETENTION_DAYS días)"
    
    # Contar backups disponibles
    BACKUP_COUNT=$(ls -1 "$BACKUP_DIR"/backup_*.sql.gz 2>/dev/null | wc -l)
    echo "[$(date)] 📊 Total de backups disponibles: $BACKUP_COUNT"
    
else
    echo "[$(date)] ❌ ERROR: Backup falló"
    exit 1
fi

echo "[$(date)] ✅ Proceso de backup completado"
