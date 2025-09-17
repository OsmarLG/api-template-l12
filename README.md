# ✨ API Template – Arquitectura Laravel

Este proyecto implementa una **arquitectura modular y escalable en Laravel**, siguiendo principios de separación de responsabilidades, estandarización de respuestas y buenas prácticas de API REST.

---

## 📂 Estructura de la arquitectura

```
app/
 ├── Actions/            # Casos de uso (una acción = una responsabilidad)
 ├── Http/
 │   ├── Controllers/    # Llaman al Service, nunca contienen lógica de negocio
 │   │   └── Api/
 │   ├── Requests/       # FormRequests (validación)
 │   └── Resources/      # API Resources & Collections (serialización de respuestas)
 ├── Pipelines/          # Flujos complejos encadenando Actions
 ├── Repositories/       
 │   ├── Contracts/      # Interfaces (contratos de repositorios)
 │   ├── Eloquent/       # Implementaciones concretas (ej. EloquentUserRepository)
 │   └── BaseRepository  # Métodos comunes + caching
 ├── Services/           # Orquestan Actions y Repositories
 └── Providers/          # Bindings e inyección de dependencias
```

---

## 🔑 Principios

- **Controller delgado** → Solo recibe la request, la valida y llama al Service.  
- **Service orquestador** → Coordina Actions, Repositories y Pipelines.  
- **Action única** → Cada Action implementa una única tarea (`CreateUserAction`, `LoginAction`, etc.).  
- **Repository pattern** → Encapsula el acceso a datos (`UserRepository`), extiende de `BaseRepository`.  
- **Caching en Repositories** → Queries cacheadas con TTL configurable (`CACHE_TTL`).  
- **Pipelines** → Encadenan Actions para procesos complejos (ej. registro de órdenes).  
- **FormRequests** → Validación centralizada.  
- **Resources/Collections** → Respuestas consistentes y documentadas.  
- **ApiController base** → Manejo estandarizado de respuestas y errores.  
- **Versionamiento de rutas** → `routes/api/versions/v1/`, con separación entre públicas (`auth.php`) y seguras (`users.php`).  

---

## 🛠️ Features incluidos

### 🔐 Autenticación (Auth)
- Login (email o username)  
- Register (username, email y password)  
- Forgot Password (envío de enlace de reseteo)  
- Reset Password (usando token)  

### 👤 Gestión de Usuarios
- CRUD completo con **filters + paginación**  
- Filtros disponibles:
  - `name`
  - `email`
  - `username`
  - `created_from`
  - `created_to`

### 📦 Base Repository con Cache
- `find($id)` cacheado  
- `paginate($filters, $perPage)` cacheado  
- `create`, `update`, `delete` → invalidan cache automáticamente  

### 📡 Versionamiento de API
- `v1/auth/*` → público  
- `v1/users/*` → protegido con `auth:sanctum`  

### 🌱 Seeders iniciales
- `RolesAndPermissionsSeeder` (master, admin, user)  
- `MasterUserSeeder`  
- `AdminUserSeeder`  
- Otros usuarios de ejemplo  

---

## ⚙️ Configuración

### Variables de entorno
```env
APP_TIMEZONE=America/Mazatlan
CACHE_TTL=60
```

### Migraciones y seeders
```bash
php artisan migrate --seed
```

### Usuarios iniciales
- Master User  
- Admin User  
- Otros usuarios preconfigurados en seeders  

---

## 📚 Convenciones de commits

Se usan **Conventional Commits + Emojis (gitmoji)**:

| Emoji | Tipo        | Uso                                  |
|-------|-------------|---------------------------------------|
| ✨     | feat        | Nueva funcionalidad                  |
| 🐛     | fix         | Corrección de bug                    |
| ♻️     | refactor    | Refactor sin cambiar funcionalidad   |
| 📝     | docs        | Cambios en documentación             |
| 🎨     | style       | Cambios de formato/código            |
| ✅     | test        | Cambios en tests                     |
| 🔧     | chore       | Configuración o tareas menores       |

Ejemplo:

```
✨ feat(core): add complete API architecture with users, auth, repository caching and versioned routes
```

---

## 📖 Documentación de la API

La API está documentada automáticamente con **Scramble**, disponible en:

- UI: `/docs/api`  
- JSON: `/docs/openapi.json`  

---

## 🚀 Próximos pasos

- Agregar más recursos (`Product`, `Order`, etc.) siguiendo el mismo patrón.  
- Extender pipelines para procesos complejos (ej. órdenes con pagos).  
- Integrar pruebas automatizadas (Feature tests con PHPUnit).  
- Desplegar con Redis/Memcached para caching con tags.  
