-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-07-2024 a las 09:15:03
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `boudin`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) UNSIGNED NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_vencimiento` datetime NOT NULL,
  `es_nueva` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `imagen`, `nombre`, `descripcion`, `fecha_creacion`, `fecha_vencimiento`, `es_nueva`) VALUES
(46, 'Actividad 1.png', 'Compartiendo Fragmentos', 'En esta actividad deben seleccionar un libro o un texto que le sea de su interés, luego deberán escoger dos fragmentos que más les haya impactado, una vez hecho eso construirán un avión de papel, en el cual deben escribirlo e intercambiarlo con sus compañeros y estos deben dar su opinión al respecto ya sea forma oral o escrita.', '2024-07-15 22:01:05', '2024-07-15 23:59:00', 0),
(48, 'actividad 4.jpeg', 'Una aventura tras las líneas ', 'En esta actividad leerán el “Soneto 22” de Pablo Neruda, para ello tendrán un tiempo estipulado en el que plasmarán sus aventuras durante la lectura, estas aventuras se desarrollarán por medio preguntas de nivel literal, inferencial y crítica. Posterior a ello, construirán un mapa de emociones que pudieron surgir antes y después de la lectura. Para esta deberán activar la creatividad y subir el enlace o link de la actividad final a la plataforma Boudin. ', '2024-07-13 21:07:13', '2024-07-16 23:05:00', 0),
(49, 'actividad 22.jpeg', 'Recetario de emociones', 'A partir de la lectura del libro La Ilíada de Homero y el libro recetas de lluvia y azúcar, el cual contiene 25 recetas para 25 emociones descritas de forma poética y metafórica, realizarán de forma individual un recetario donde describirán sus emociones y sentimientos, que les genero el libro leído “La Ilíada”. ', '2024-07-13 21:08:36', '2024-07-18 23:07:00', 0),
(50, 'actividad 3.jpeg', 'Collage emocional', 'En esta actividad leerán “Medea” de Eurípides, para esto, en la página web se abrirá un espacio donde plasmarán como se sienten antes y después haberlo leído, haciendo uso de colores, palabras o expresiones propias o tomadas de la lectura. así mismo, realizarán analogías de la realidad con la lectura, para finalizar se abrirá un foro donde responderán preguntas relacionadas con el texto leído y teniendo en cuenta los tres niveles de lectura.', '2024-07-13 21:09:36', '2024-07-20 23:09:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `megusta_respuestas`
--

CREATE TABLE `megusta_respuestas` (
  `id` int(11) NOT NULL,
  `id_respuesta` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_actividad` int(11) UNSIGNED NOT NULL,
  `respuesta_textual` longtext NOT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_foro`
--

CREATE TABLE `respuestas_foro` (
  `id` int(11) NOT NULL,
  `id_tema` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `respuesta_textual` text DEFAULT NULL,
  `fecha_respuesta` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `RolID` int(11) NOT NULL,
  `NombreRol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`RolID`, `NombreRol`) VALUES
(1, 'Estudiante'),
(2, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas`
--

CREATE TABLE `temas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `temas`
--

INSERT INTO `temas` (`id`, `titulo`, `descripcion`, `id_usuario`, `fecha_creacion`) VALUES
(19, 'Impacto de los fragmentos literarios en la percepción personal.', '¿Cómo seleccionaste los dos fragmentos del texto? ¿Qué criterios utilizaste?\r\n¿Cuál fue el impacto emocional que tuvieron esos fragmentos en ti y por qué?\r\n¿Cómo crees que la elección de un avión de papel para compartir los fragmentos afectó la experiencia de intercambio con tus compañeros?', 1, '2024-07-14 06:29:12'),
(20, 'Explorando emociones a través de la literatura: La Ilíada como fuente de inspiración.', 'Describe una emoción que La Ilíada evocó en ti y cómo la representarías en forma de \"receta emocional\".\r\n¿De qué manera crees que la poesía y la metáfora pueden capturar emociones complejas como las descritas en el libro Recetas de lluvia y azúcar?\r\n¿Qué diferencia encuentras entre expresar emociones a través de un recetario y otros medios tradicionales de comunicación emocional?', 1, '2024-07-14 06:29:31'),
(21, 'Mapa de emociones: Explorando el Soneto 22 de Pablo Neruda.', '¿Qué emociones predominaron durante tu lectura del Soneto 22 y cómo las representarías en un mapa emocional?\r\n¿Cómo influyeron las preguntas de nivel literal, inferencial y crítica en tu comprensión y apreciación del poema?\r\n¿Qué papel juega la creatividad en la representación visual de las emociones antes y después de la lectura del poema?', 1, '2024-07-14 06:29:51'),
(22, 'Reflexiones emocionales a partir de Medea de Eurípides.', '¿Cómo influyeron los colores, palabras o expresiones que elegiste para representar tus emociones antes y después de la lectura de Medea?\r\n¿Qué analogías encontraste entre la historia de Medea y aspectos de la realidad contemporánea?\r\n¿Cómo te ayudaron los tres niveles de lectura (literal, inferencial y crítica) a comprender mejor los temas y mensajes de la obra de Eurípides?', 1, '2024-07-14 06:30:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `UserID` int(11) NOT NULL,
  `num_dni` int(15) NOT NULL,
  `NombreUsuario` varchar(255) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `RolID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`UserID`, `num_dni`, `NombreUsuario`, `Contraseña`, `Email`, `RolID`) VALUES
(1, 1108758381, 'Profesor', '$2y$10$tx1m/mKcJMX3a6vQvqC8CO4mogIUxMjwHgf5rHMm4mA/onpdpnJgC', 'admin@admin.com', 2),
(31, 1108758381, 'Edgar Montes', '$2y$10$PlTirNCc9a6UT6eTsc6bneA6ybj7tEFODSeDNErvettywHjVEkoZG', 'estudiante@gmail.com', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `megusta_respuestas`
--
ALTER TABLE `megusta_respuestas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`id_respuesta`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_actividad` (`id_actividad`);

--
-- Indices de la tabla `respuestas_foro`
--
ALTER TABLE `respuestas_foro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tema` (`id_tema`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`RolID`);

--
-- Indices de la tabla `temas`
--
ALTER TABLE `temas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `RolID` (`RolID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `megusta_respuestas`
--
ALTER TABLE `megusta_respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `respuestas_foro`
--
ALTER TABLE `respuestas_foro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `temas`
--
ALTER TABLE `temas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `megusta_respuestas`
--
ALTER TABLE `megusta_respuestas`
  ADD CONSTRAINT `megusta_respuestas_ibfk_1` FOREIGN KEY (`id_respuesta`) REFERENCES `respuestas_foro` (`id`),
  ADD CONSTRAINT `megusta_respuestas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`UserID`);

--
-- Filtros para la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`UserID`),
  ADD CONSTRAINT `respuestas_ibfk_2` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id`);

--
-- Filtros para la tabla `respuestas_foro`
--
ALTER TABLE `respuestas_foro`
  ADD CONSTRAINT `respuestas_foro_ibfk_1` FOREIGN KEY (`id_tema`) REFERENCES `temas` (`id`),
  ADD CONSTRAINT `respuestas_foro_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`UserID`);

--
-- Filtros para la tabla `temas`
--
ALTER TABLE `temas`
  ADD CONSTRAINT `temas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`UserID`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`RolID`) REFERENCES `roles` (`RolID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;