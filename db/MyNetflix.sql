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
    estado ENUM('activo', 'inactivo', 'pendiente') DEFAULT 'pendiente',  
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

-- Tabla `directores`
CREATE TABLE directores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    nacionalidad VARCHAR(100)
);

-- Tabla `pelicula_director`
CREATE TABLE pelicula_director (
    pelicula_id INT,
    director_id INT,
    PRIMARY KEY (pelicula_id, director_id),
    FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE,
    FOREIGN KEY (director_id) REFERENCES directores(id) ON DELETE CASCADE
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
('Juan Pérez', 'juan@example.com', '$2y$10$kjubxHT8qb79Drrhi7O9LulkKdwciWCHfRUnfkKxl8l/5oqDk9lBq', 'cliente', 'activo'),
('María López', 'maria@example.com', '$2y$10$kjubxHT8qb79Drrhi7O9LulkKdwciWCHfRUnfkKxl8l/5oqDk9lBq', 'cliente', 'activo'),
('Carlos García', 'carlos@example.com', '$2y$10$kjubxHT8qb79Drrhi7O9LulkKdwciWCHfRUnfkKxl8l/5oqDk9lBq', 'admin', 'activo');

INSERT INTO peliculas (titulo, descripcion, año, duracion, caratula) VALUES
('Hasta que la plata nos separe', 'Una comedia romántica sobre el amor y el dinero.', 2021, 120, 'hasta_que_la_plata_nos_separe.jpg'),
('Rick and Morty', 'Las aventuras de un científico loco y su nieto.', 2013, 22, 'rick_and_morty.jpg'),
('Daredevil', 'Un abogado ciego lucha contra el crimen.', 2015, 55, 'daredevil.jpg'),
('Violet Evergarden', 'La historia de una joven que busca su propósito.', 2018, 24, 'violet_evergarden.jpg'),
('Jurassic World', 'Un parque temático de dinosaurios que sale mal.', 2015, 124, 'jurassic_world.jpg'),
('Inception', 'Un ladrón que roba secretos a través de los sueños.', 2010, 148, 'inception.jpg'),
('The Matrix', 'Un hacker descubre la verdad sobre su realidad.', 1999, 136, 'the_matrix.jpg'),
('The Shawshank Redemption', 'La historia de un hombre encarcelado injustamente.', 1994, 142, 'shawshank_redemption.jpg'),
('Interstellar', 'Un grupo de exploradores viaja a través de un agujero de gusano.', 2014, 169, 'interstellar.jpg'),
('The Godfather', 'La historia de una familia mafiosa en Nueva York.', 1972, 175, 'the_godfather.jpg');

INSERT INTO directores (nombre, fecha_nacimiento, nacionalidad) VALUES
('Christopher Nolan', '1970-07-30', 'Británico'),
('Lana Wachowski', '1965-06-21', 'Estadounidense'),
('Lilly Wachowski', '1967-12-29', 'Estadounidense'),
('Frank Darabont', '1959-01-28', 'Francés'),
('Steven Spielberg', '1946-12-18', 'Estadounidense'),
('Hayao Miyazaki', '1941-01-05', 'Japonés'),
('Martin Scorsese', '1942-11-17', 'Estadounidense'),
('Quentin Tarantino', '1963-03-27', 'Estadounidense'),
('James Cameron', '1954-08-16', 'Canadiense'),
('Ridley Scott', '1937-11-30', 'Británico');

INSERT INTO pelicula_director (pelicula_id, director_id) VALUES
(1, 1), -- Hasta que la plata nos separe - Christopher Nolan
(2, 2), -- Rick and Morty - Lana Wachowski
(3, 3), -- Daredevil - Lilly Wachowski
(4, 4), -- Violet Evergarden - Frank Darabont
(5, 5), -- Jurassic World - Steven Spielberg
(6, 1), -- Inception - Christopher Nolan
(7, 2), -- The Matrix - Lana Wachowski
(8, 4), -- The Shawshank Redemption - Frank Darabont
(9, 1), -- Interstellar - Christopher Nolan
(10, 6); -- The Godfather - Hayao Miyazaki

INSERT INTO actores (nombre, fecha_nacimiento, nacionalidad) VALUES
('Charlie Cox', '1982-12-15', 'Británico'),
('Diana Guerrero', '1986-04-21', 'Estadounidense'),
('Justin Roiland', '1980-02-21', 'Estadounidense'),
('Yui Ishikawa', '1991-05-30', 'Japonesa'),
('Chris Pratt', '1979-06-21', 'Estadounidense'),
('Leonardo DiCaprio', '1974-11-11', 'Estadounidense'),
('Keanu Reeves', '1964-09-02', 'Canadiense'),
('Morgan Freeman', '1937-06-01', 'Estadounidense'),
('Matthew McConaughey', '1969-11-04', 'Estadounidense'),
('Al Pacino', '1940-04-25', 'Estadounidense');

INSERT INTO pelicula_actor (pelicula_id, actor_id) VALUES
(1, 1), -- Hasta que la plata nos separe - Juan Pérez
(2, 3), -- Rick and Morty - Justin Roiland
(3, 1), -- Daredevil - Charlie Cox
(4, 4), -- Violet Evergarden - Yui Ishikawa
(5, 5), -- Jurassic World - Chris Pratt
(6, 1), -- Inception - Leonardo DiCaprio
(7, 2), -- The Matrix - Keanu Reeves
(8, 3), -- The Shawshank Redemption - Morgan Freeman
(9, 4), -- Interstellar - Matthew McConaughey
(10, 5); -- The Godfather - Al Pacino

INSERT INTO likes (usuario_id, pelicula_id, fecha_like) VALUES
(1, 1, NOW()),
(1, 2, NOW()),
(2, 3, NOW()),
(3, 4, NOW()),
(2, 5, NOW()),
(1, 6, NOW()),
(2, 7, NOW()),
(1, 8, NOW()),
(3, 9, NOW()),
(2, 10, NOW());

INSERT INTO categorias (nombre) VALUES
('Comedia'),
('Acción'),
('Drama'),
('Aventura'),
('Ciencia Ficción'),
('Terror'),
('Romance'),
('Documental'),
('Animación'),
('Fantasía');

INSERT INTO pelicula_categoria (pelicula_id, categoria_id) VALUES
(1, 1), -- Hasta que la plata nos separe - Comedia
(2, 5), -- Rick and Morty - Ciencia Ficción
(3, 2), -- Daredevil - Acción
(4, 3), -- Violet Evergarden - Drama
(5, 4), -- Jurassic World - Aventura
(6, 5), -- Inception - Fantasía
(7, 5), -- The Matrix - Ciencia Ficción
(8, 3), -- The Shawshank Redemption - Drama
(9, 5), -- Interstellar - Ciencia Ficción
(10, 2); -- The Godfather - Drama