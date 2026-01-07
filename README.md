# Acuarela Web Page

Sitio web corporativo de Acuarela - Plataforma de gestión para Daycares.

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

### Ejecutar Comandos Dentro del Contenedor

```bash
# Abrir bash en el contenedor
docker-compose exec acuarela-web bash

# Ver archivos CSS
docker-compose exec acuarela-web ls -la /var/www/html/css/

# Ver logs de Apache
docker-compose exec acuarela-web cat /var/log/apache2/error.log
```

### Verificar Permisos de Archivos

```bash
docker-compose exec acuarela-web ls -la /var/www/html/
```

## 🌐 Acceso al Sitio

Una vez iniciado el contenedor, el sitio está disponible en:
- **URL Local:** http://localhost:8080
- **API WordPress:** https://acuarelaadmin.acuarela.app/wp-json/wp/v2/

## 📁 Estructura del Proyecto

```
acuarela-web-page/
├── Dockerfile              # Configuración de la imagen Docker
├── docker-compose.yml      # Orquestación del contenedor
├── apache-config.conf      # Configuración personalizada de Apache
├── .htaccess              # Reglas de Apache y URLs amigables
├── css/                   # Archivos de estilos
│   ├── styles.css         # Estilos principales
│   ├── fonts.css          # Fuentes
│   ├── normalize.css      # Reset CSS
│   └── glider.css         # Slider
├── js/                    # Scripts JavaScript
├── img/                   # Imágenes y recursos
├── includes/              # Archivos PHP incluidos
│   ├── config.php         # Configuración
│   ├── functions.php      # Funciones PHP
│   ├── head.php           # Head HTML
│   ├── header.php         # Header del sitio
│   └── footer.php         # Footer del sitio
├── get/                   # APIs GET
├── set/                   # APIs POST
└── index.php              # Página principal
```

## 🔍 Solución de Problemas

### El CSS no se carga correctamente

1. **Verifica que el archivo CSS sea el correcto:**
```bash
# Descargar CSS de producción
Invoke-WebRequest -Uri "https://acuarela.app/css/styles.css" -UseBasicParsing -OutFile "css/styles.css"
```

2. **Limpia el caché del navegador:**
   - Presiona `Ctrl + Shift + R` (hard reload)
   - O abre en modo incógnito: `Ctrl + Shift + N`

3. **Reinicia el contenedor:**
```bash
docker-compose restart acuarela-web
```

### Error: "Port 8080 already in use"

Cambia el puerto en `docker-compose.yml`:
```yaml
ports:
  - "8081:80"  # Cambia 8080 por otro puerto disponible
```

### Problemas de Permisos

Si hay errores de permisos en archivos:
```bash
docker-compose exec acuarela-web chown -R www-data:www-data /var/www/html
docker-compose exec acuarela-web chmod -R 755 /var/www/html
```

### Ver Errores de PHP

Los logs de PHP se encuentran en:
```bash
docker-compose exec acuarela-web tail -f /var/log/apache2/error.log
```

### El contenedor no inicia

1. Verifica que Docker Desktop esté corriendo
2. Revisa los logs:
```bash
docker-compose logs acuarela-web
```
3. Reconstruye la imagen:
```bash
docker-compose down
docker-compose up -d --build
```

## 🔄 Sincronización con Producción

El sitio obtiene contenido dinámico de WordPress en:
- **URL:** https://acuarelaadmin.acuarela.app
- **API:** /wp-json/wp/v2/

Para actualizar el CSS local desde producción:
```powershell
# PowerShell
Invoke-WebRequest -Uri "https://acuarela.app/css/styles.css" -UseBasicParsing -OutFile "css/styles.css"
docker-compose restart acuarela-web
```

```bash
# Linux/Mac
curl -o css/styles.css https://acuarela.app/css/styles.css
docker-compose restart acuarela-web
```

## 🛠️ Configuración de Apache

El archivo `apache-config.conf` incluye:
- Configuración de tipos MIME para CSS, JS y fuentes
- Headers de seguridad (X-Content-Type-Options)
- Cache control optimizado
- Soporte para URLs amigables vía `.htaccess`

## 📝 Desarrollo

### Modificar Archivos

Los archivos son montados como volumen, por lo que cualquier cambio en tu editor se refleja inmediatamente en el contenedor. Solo necesitas recargar el navegador.

### Deshabilitar Caché para Desarrollo

En `docker-compose.yml`, el volumen usa `:cached` para mejor rendimiento:
```yaml
volumes:
  - .:/var/www/html:cached
```

### Variables de Entorno

Puedes agregar variables de entorno en `docker-compose.yml`:
```yaml
environment:
  - PHP_MEMORY_LIMIT=256M
  - APACHE_LOG_LEVEL=debug
```

## 📞 Soporte

Para problemas o preguntas:
- Email: info@acuarela.app
- Sitio: https://acuarela.app

## 📄 Licencia

© Acuarela - Professional Child Care Training INC
