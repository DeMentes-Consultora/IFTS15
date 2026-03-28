# GUÍA DE DESPLIEGUE A INFINITYFREE - IFTS15

## 📋 PREPARACIÓN PREVIA

### 1. Crear cuenta en InfinityFree
- Visita: https://infinityfree.net
- Crea tu cuenta gratuita
- Elige un subdominio o conecta tu dominio propio

### 2. Configurar Base de Datos
- En el panel de InfinityFree, ve a "MySQL Databases"
- Crea una nueva base de datos
- Anota los datos de conexión:
  - Host: sql300.infinityfreeapp.com (o similar)
  - Username: if0_XXXXXXXX
  - Password: [tu password]
  - Database name: if0_XXXXXXXX_ifts15

## 📂 QUÉ ARCHIVOS SUBIR

### OPCIÓN RECOMENDADA: Subir TODO EL CONTENIDO de la carpeta raíz

Sube TODOS estos archivos y carpetas a public_html/:

```
public_html/
├── index.php                 ✅ (página principal)
├── .htaccess                 ✅ (configuración Apache)
├── .env                      ✅ (renombra .env.production)
├── composer.json             ✅ (dependencias)
├── composer.lock             ✅ (versiones específicas)
├── error404.php              ✅ (página de error)
├── error500.php              ✅ (página de error)
├── src/                      ✅ (carpeta completa)
│   ├── Components/
│   ├── Controllers/
│   ├── Model/
│   ├── Views/
│   ├── Template/
│   ├── css/
│   ├── Public/
│   ├── ConectionBD/
│   └── config.php
└── vendor/                   ✅ (librerías PHP)
    ├── autoload.php
    ├── phpmailer/
    ├── vlucas/
    └── ...
```

### ❌ NO SUBIR estos archivos:
- `README.md`
- `RESPONSIVE_IMPROVEMENTS.md`
- `test_*.php`
- `verificar_*.php`
- Archivos de logs locales

## 🔧 PASOS DETALLADOS

### 1. Preparar archivos localmente

1. **Renombrar archivo de configuración:**
   ```bash
   # En tu carpeta local, renombra:
   .env.production → .env
   ```

2. **Editar el nuevo .env:**
   ```bash
   # Cambiar estas líneas con tus datos reales:
   BASE_URL=https://TU-DOMINIO.infinityfreeapp.com
   DB_HOST=sql300.infinityfreeapp.com
   DB_USERNAME=if0_XXXXXXXX
   DB_PASSWORD=tu_password_bd
   DB_NAME=if0_XXXXXXXX_ifts15
   DEBUG_MODE=false
   ```

### 2. Subir archivos via FTP

1. **Usar el File Manager de InfinityFree** (más fácil):
   - Ve a "Online File Manager" en tu panel
   - Navega a public_html/
   - Sube todos los archivos y carpetas

2. **O usar cliente FTP:**
   - Host: ftpupload.net
   - Username: tu_username_infinityfree
   - Password: tu_password_infinityfree
   - Puerto: 21

### 3. Configurar Base de Datos

1. **Subir archivo SQL:**
   - Ve a "MySQL Databases" → "phpMyAdmin"
   - Selecciona tu base de datos
   - Importa: `src/ConectionBD/lacanchitadelospibes.sql`

2. **O crear tablas manualmente** si prefieres.

### 4. Verificar permisos

Asegúrate que estas carpetas tengan permisos 755:
- `src/`
- `src/Public/`
- `vendor/`

## 🌐 CONFIGURACIÓN FINAL

### 1. Actualizar rutas en .env
```bash
BASE_URL=https://tu-dominio.infinityfreeapp.com
```

### 2. Probar la aplicación
- Visita: https://tu-dominio.infinityfreeapp.com
- Debería mostrar la página principal
- Prueba: https://tu-dominio.infinityfreeapp.com/src/Views/realizador-productor-tv.php

### 3. Configurar emails (opcional)
- InfinityFree permite SMTP externo
- Configura Gmail u otro proveedor en .env

## 🚨 PROBLEMAS COMUNES

### Error 500 - Internal Server Error
1. Verifica permisos de archivos (644 para archivos, 755 para carpetas)
2. Revisa el .htaccess
3. Verifica datos de BD en .env

### Error de conexión BD
1. Verifica datos de conexión en .env
2. Asegúrate que la BD existe en InfinityFree
3. Importa las tablas necesarias

### Archivos CSS/JS no cargan
1. Verifica rutas en templates
2. Asegúrate que `src/css/` se subió correctamente
3. Verifica permisos de la carpeta `src/Public/`

## ✅ CHECKLIST FINAL

- [ ] Cuenta InfinityFree creada
- [ ] Base de datos configurada
- [ ] Archivo .env.production renombrado a .env
- [ ] Variables de .env actualizadas
- [ ] Todos los archivos subidos a public_html/
- [ ] Permisos configurados correctamente
- [ ] Base de datos importada
- [ ] Sitio web accesible
- [ ] Página de carrera funcionando
- [ ] Formularios de contacto probados

## 🔗 URLS IMPORTANTES

- **Página principal:** https://tu-dominio.infinityfreeapp.com
- **Información carrera:** https://tu-dominio.infinityfreeapp.com/src/Views/realizador-productor-tv.php
- **Panel InfinityFree:** https://app.infinityfree.net

¡Tu sitio estará online y funcionando! 🎉