
```
perfilglobal_v2
├─ app
│  ├─ Controllers
│  │  ├─ AdminController.php
│  │  ├─ AsistenciaController.php
│  │  ├─ AuthController.php
│  │  ├─ DashboardController.php
│  │  ├─ EventoController.php
│  │  ├─ MonitorController.php
│  │  ├─ PasswordController.php
│  │  └─ ReporteController.php
│  ├─ Helpers
│  │  ├─ ExcelHelper.php
│  │  └─ TimeHelper.php
│  ├─ Middleware
│  │  ├─ RoleMiddleware.php
│  │  └─ SessionMiddleware.php
│  ├─ Models
│  │  ├─ Asistencia.php
│  │  ├─ Evento.php
│  │  ├─ HistorialAcademico.php
│  │  ├─ Log.php
│  │  ├─ Periodo.php
│  │  ├─ Persona.php
│  │  ├─ Registro.php
│  │  ├─ Reporte.php
│  │  ├─ Reportes.php
│  │  ├─ Token.php
│  │  └─ Usuario.php
│  ├─ Services
│  │  ├─ BackupService.php
│  │  └─ SemesterService.php
│  └─ Storage
│     └─ Backups
│        └─ .htaccess
├─ composer.json
├─ composer.lock
├─ config
│  ├─ .env
│  ├─ .env.example
│  ├─ Database.php
│  └─ settings.php
├─ perfilglobal_v2.sql
├─ public
│  ├─ .htaccess
│  ├─ assets
│  │  ├─ css
│  │  ├─ img
│  │  └─ js
│  └─ index.php
└─ views
   ├─ admin
   │  ├─ carga_masiva.php
   │  └─ dashboard.php
   ├─ auth
   │  ├─ forgot-password.php
   │  ├─ login.php
   │  └─ reset-password.php
   ├─ dashboard
   │  ├─ admin.php
   │  ├─ dev.php
   │  └─ monitor.php
   ├─ dashboard.php
   ├─ layouts
   │  ├─ footer.php
   │  ├─ header.php
   │  ├─ layout.php
   │  └─ sidebar.php
   └─ monitor
      ├─ consulta_linea.php
      ├─ consulta_programa.php
      └─ dashboard.php

```