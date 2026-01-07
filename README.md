# Acuarela Web Page

Sitio web corporativo de Acuarela - Plataforma de gestión para Daycares.

**Sitio en Producción:** https://acuarela.app/

## 📋 Requisitos Previos

- Docker Desktop instalado
- Docker Compose
- Puerto 8080 disponible

## 🚀 Inicio Rápido con Docker

### 1. Construir e Iniciar el Contenedor

```bash
docker-compose up -d --build
```

Este comando:
- Construye la imagen Docker con PHP 8.2 + Apache
- Configura el servidor web con los módulos necesarios
- Monta el código local en el contenedor
- Inicia el servidor en http://localhost:8080

### 2. Verificar que el Contenedor Está Corriendo

```bash
docker-compose ps
```

### 3. Ver Logs en Tiempo Real

```bash
docker-compose logs -f acuarela-web
```

### 4. Detener el Contenedor

```bash
docker-compose down
```

## 🔧 Comandos Útiles

### Reiniciar el Contenedor

```bash
docker-compose restart acuarela-web
```

### Reconstruir Desde Cero (Sin Caché)

```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

O usa el script incluido:

**Windows:**
```bash
.\rebuild-docker.bat
```

**Linux/Mac:**
```bash
./rebuild-docker.sh
```

### Variables de Entorno

Puedes agregar variables de entorno en `docker-compose.yml`:
```yaml
environment:
  - PHP_MEMORY_LIMIT=256M
  - APACHE_LOG_LEVEL=debug
```


## 📄 Licencia

© Acuarela - Professional Child Care Training INC
l CMS WordPress:
```
https://acuarelaadmin.acuarela.app/wp-json/wp/v2/
```
## 📝 Notas de Desarrollo

- Los archivos son montados como volumen (hot-reload automático)
- Cambios en PHP/CSS/JS se reflejan al recargar el navegador
- Para cambios en Apache, reinicia el contenedor
- Limpia caché del navegador con `Ctrl + Shift + R