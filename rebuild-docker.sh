#!/bin/bash

echo "🔄 Reconstruyendo contenedor Docker de Acuarela..."

# Detener y eliminar contenedores existentes
echo "📦 Deteniendo contenedores..."
docker-compose down

# Limpiar imágenes antiguas (opcional)
# docker system prune -f

# Reconstruir la imagen sin cache
echo "🏗️  Reconstruyendo imagen..."
docker-compose build --no-cache

# Iniciar los servicios
echo "🚀 Iniciando servicios..."
docker-compose up -d

# Mostrar logs
echo "📋 Mostrando logs..."
docker-compose logs -f

echo "✅ Proceso completado!"
echo "🌐 La aplicación está disponible en: http://localhost:8080"
