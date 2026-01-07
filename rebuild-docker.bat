@echo off
echo 🔄 Reconstruyendo contenedor Docker de Acuarela...

REM Detener y eliminar contenedores existentes
echo 📦 Deteniendo contenedores...
docker-compose down

REM Reconstruir la imagen sin cache
echo 🏗️  Reconstruyendo imagen...
docker-compose build --no-cache

REM Iniciar los servicios
echo 🚀 Iniciando servicios...
docker-compose up -d

REM Mostrar estado
echo 📋 Estado de los contenedores:
docker-compose ps

echo.
echo ✅ Proceso completado!
echo 🌐 La aplicación está disponible en: http://localhost:8080
echo.
echo Para ver los logs en tiempo real, ejecuta:
echo docker-compose logs -f
pause
