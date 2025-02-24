-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS MyNetflix;
USE MyNetflix;

-- Tabla `usuarios`
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'cliente') DEFAULT 'cliente',
    estado ENUM('activo', 'inactivo') DEFAULT 'inactivo',  
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla `peliculas`
CREATE TABLE peliculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    año YEAR,
    duracion INT,
    caratula VARCHAR(255),
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla `actores`
CREATE TABLE actores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    nacionalidad VARCHAR(100)
);

-- Tabla `pelicula_actor`
CREATE TABLE pelicula_actor (
    pelicula_id INT,
    actor_id INT,
    PRIMARY KEY (pelicula_id, actor_id),
    FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE,
    FOREIGN KEY (actor_id) REFERENCES actores(id) ON DELETE CASCADE
);

-- Tabla `likes`
CREATE TABLE likes (
    usuario_id INT,
    pelicula_id INT,
    fecha_like TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (usuario_id, pelicula_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE,
    UNIQUE (usuario_id, pelicula_id) 
);

-- Tabla `categorias`
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla `pelicula_categoria`
CREATE TABLE pelicula_categoria (
    pelicula_id INT,
    categoria_id INT,
    PRIMARY KEY (pelicula_id, categoria_id),
    FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);

INSERT INTO usuarios (nombre, email, password, rol, estado) VALUES
('Juan Pérez', 'juan@example.com', 'password123', 'cliente', 'activo'),
('María López', 'maria@example.com', 'password456', 'cliente', 'activo'),
('Carlos García', 'carlos@example.com', 'adminpass', 'admin', 'activo');

INSERT INTO peliculas (titulo, descripcion, año, duracion, caratula) VALUES
('Hasta que la plata nos separe', 'Una comedia romántica sobre el amor y el dinero.', 2021, 120, './img/hasta_que_la_plata_nos_separe.jpg'),
('Rick and Morty', 'Las aventuras de un científico loco y su nieto.', 2013, 22, './img/rick_and_morty.jpg'),
('Daredevil', 'Un abogado ciego lucha contra el crimen.', 2015, 55, './img/daredevil.jpg'),
('Violet Evergarden', 'La historia de una joven que busca su propósito.', 2018, 24, './img/violet_evergarden.jpg'),
('Jurassic World', 'Un parque temático de dinosaurios que sale mal.', 2015, 124, './img/jurassic_world.jpg');

INSERT INTO actores (nombre, fecha_nacimiento, nacionalidad) VALUES
('Charlie Cox', '1982-12-15', 'Británico'),
('Diana Guerrero', '1986-04-21', 'Estadounidense'),
('Justin Roiland', '1980-02-21', 'Estadounidense'),
('Yui Ishikawa', '1991-05-30', 'Japonesa'),
('Chris Pratt', '1979-06-21', 'Estadounidense');

INSERT INTO pelicula_actor (pelicula_id, actor_id) VALUES
(1, 1), -- Hasta que la plata nos separe - Juan Pérez
(2, 3), -- Rick and Morty - Justin Roiland
(3, 1), -- Daredevil - Charlie Cox
(4, 4), -- Violet Evergarden - Yui Ishikawa
(5, 5); -- Jurassic World - Chris Pratt

INSERT INTO likes (usuario_id, pelicula_id, fecha_like) VALUES
(1, 1, NOW()),
(1, 2, NOW()),
(2, 3, NOW()),
(3, 4, NOW()),
(2, 5, NOW());

INSERT INTO categorias (nombre) VALUES
('Comedia'),
('Acción'),
('Drama'),
('Aventura'),
('Ciencia Ficción');

INSERT INTO pelicula_categoria (pelicula_id, categoria_id) VALUES
(1, 1), -- Hasta que la plata nos separe - Comedia
(2, 5), -- Rick and Morty - Ciencia Ficción
(3, 2), -- Daredevil - Acción
(4, 3), -- Violet Evergarden - Drama
(5, 4); -- Jurassic World - Aventura