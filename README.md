# 🎥 **MyNetflix**  
*Activitat 9 - Disseny i Desenvolupament d'una Plataforma de Vídeo en Streaming*  

## 📌 **Objectiu**  
Crear un lloc web per a una plataforma de vídeo en streaming amb les següents funcionalitats:  
- 🌟 **Part pública**: Mostra continguts destacats (TOP 5) i un catàleg ordenat per popularitat. Permet registrar-se i loguejar-se.  
- 🔒 **Part privada**:  
  - 🎛️ Administradors: Gestionen el catàleg i els usuaris.  
  - 👤 Usuaris loguejats: Poden donar "likes" i utilitzar cercadors i filtres avançats.  

---

## ⏳ **Temporització**  
🕒 15 hores  

---

## 🏫 **Organització de l'espai**  
- **Metodologia**: Activitat pràctica  
- **Agrupació d'alumnes**: Treball en parelles  

---

## 📝 **Descripció de l'activitat**  

### 🌐 **1. Part pública**  
1. Mostrar un **TOP 5** en una única fila.  
2. Afegir un **grid** amb les pel·lícules disponibles, ordenades per popularitat segons el nombre de "likes".  
3. Incloure:  
   - 🖊️ **Login**: Accés a l'entorn privat.  
   - 🆕 **Registre**: Validació d'usuaris nous per part de l'administrador.  

### 🔐 **2. Part privada**  

#### 🛠️ **2.1. Usuari administrador**  
- **Gestió d'usuaris**:  
  - ✅ Validar nous registres.  
  - 🔄 Activar/desactivar usuaris.  

- **Gestió de pel·lícules**:  
  - 📋 Veure catàleg en una taula ordenable (per nom o "likes").  
  - ➕ Afegir o 🗑️ eliminar pel·lícules.  
  - ✏️ Modificar dades de pel·lícules.  
  - 🔍 Cercador ràpid amb filtres.  

#### 🎬 **2.2. Usuari client**  
- ❤️ Posar o treure "likes" a les pel·lícules.  
- 🔄 Filtrar pel·lícules per "likes" de l'usuari.  
- 🔎 Cercador avançat amb múltiples criteris.  

---

## ❗ **A tenir en compte**  
- 📱 **Responsive design**: Mobile first amb prototips per a diversos dispositius.  
- 🗂️ **BBDD adequada**: Gestió d'usuaris i pel·lícules.  
- 🔒 **Connexions segures**: PDO, Statements, BindParams, validacions.  
- 🔁 **Control de transaccions**: Gestió segura per eliminar pel·lícules i "likes".  
- ⚡ **AJAX**: Per a la creació d'usuaris, filtres i gestió de "likes".  
- 🖼️ **Posters**: Pujar arxius al servidor.  
- 💻 **GitHub**: Amb roadmap, issues, branques i un readme detallat.  

---

## 📚 **Recursos**  
- 📖 Materials d'estudi del Moodle (Mòdul 7).  

---

## 📝 **Avaluació de l'activitat**  

| **Aspecte**                          | **Punts** |  
|--------------------------------------|-----------|  
| 🎨 **Disseny i prototipus**           | 15%       |  
| 🗂️ **BBDD adequada**                  | 10%       |  
| 🔒 **Connexions segures**             | 10%       |  
| 🔁 **Control de transaccions**        | 10%       |  
| ⚡ **AJAX i interaccions**            | 30%       |  
| 🖼️ **Posters (arxius al servidor)**   | 15%       |  
| 💻 **GitHub i documentació**          | 10%       |  

---

## 👥 **Integrants de l'equip**  
- **Juan Carlos Prado Garcia**  
- **Sergi Masip Manchado**  

---

🚀 **Endavant amb el desenvolupament!** 🎉  
=======
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
>>>>>>> ca9114f3bbeb526f67fbf8a7e23efe69fe5f5482
