# 🎬 MyNetflix - M12 Síntesis (Actividad 9)

## 📌 Objetivo
Crear una plataforma de **video por streaming** con:
- 🌍 **Parte pública**: Muestra los contenidos más destacados.
- 🔐 **Parte privada**:
  - 👨‍💼 **Administrador**: Gestiona el catálogo y consulta estadísticas.
  - 🎥 **Usuarios registrados**: Pueden dar "likes" y filtrar búsquedas.

---

## ⏳ Duración
🕒 **15 horas**

## 🏫 Organización del espacio
- **Metodología**: Actividad práctica.
- **Trabajo en equipo**: En parejas.
- **Integrantes del grupo**: Sergi Masip y Juan Carlos Prado.

---

## 📝 Descripción de la actividad
### 🌟 1. Página pública
- 🔝 **TOP 5** de películas destacadas (en una sola fila).
- 🎞️ **Grid** de películas ordenadas por popularidad (según número de "likes").
- 🔑 **Login** para acceder al entorno privado.
- 📝 **Registro de usuarios**, requiriendo validación del administrador.

### 🔒 2. Parte privada
#### 🛠️ 2.1. Administrador
- **Gestión de usuarios**:
  - ✅ Validar registros pendientes.
  - 🚫 Activar o desactivar usuarios.
- **Gestión de películas**:
  - 📋 Listar películas ordenadas por nombre y "likes".
  - ➕ Agregar, ✏️ modificar y 🗑️ eliminar películas.
  - 🔍 Buscador rápido de películas.

#### 🎬 2.2. Cliente
- 📌 Ver el catálogo de películas.
- ❤️ Dar o quitar "likes".
- 🎯 Filtrar películas con "likes" propios.
- 🔍 Buscar por múltiples criterios.

---

## 🚀 Requisitos técnicos
✅ **Diseño mobile-first** con layout adaptable.
✅ **Base de datos estructurada** para gestionar usuarios y películas.
✅ **Conexiones seguras con PDO** (Statements, BindParams, escape de información).
✅ **Control de transacciones** (para eliminar películas y "likes").
✅ **Uso de AJAX** para registros, filtros y "likes".
✅ **Subida de carátulas** al servidor.
✅ **Repositorio en GitHub** con documentación (README, roadmap, issues, ramas).

---

## 📚 Recursos
📖 Materiales de estudio de Moodle del **Módulo 7**.

## 👩‍🏫 Rol del equipo docente
🎓 Atender dudas y realizar la **evaluación** de la actividad.

---

## 🏆 Evaluación de la actividad
| **Criterio** | **Puntuación** |
|------------|--------------|
| 🎨 Diseño y prototipos (responsive, TOP 5 fijo) | 15% |
| 🗃️ Base de datos estructurada | 10% |
| 🔐 Conexiones seguras (PDO, validaciones) | 10% |
| 🔄 Control de transacciones (eliminar películas y likes) | 10% |
| ⚡ AJAX (usuarios, filtros, likes) | 30% |
| 📂 Carátulas de películas (subida de archivos) | 15% |
| 🛠️ GitHub (readme, roadmap, issues, ramas) | 10% |

---