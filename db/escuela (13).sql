-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 27-01-2025 a las 02:53:47
-- Versión del servidor: 10.11.6-MariaDB-0+deb12u1
-- Versión de PHP: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `escuela`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id_aula` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletines`
--

CREATE TABLE `boletines` (
  `id` int(11) NOT NULL,
  `id_evaluacion` int(11) NOT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `firmado` enum('si','no') DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id` int(11) NOT NULL,
  `nombre_cargo` enum('Docente','Admin','Usuario') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id` int(11) NOT NULL,
  `id_aula` int(11) NOT NULL,
  `id_grado` int(11) NOT NULL,
  `id_seccion` int(11) NOT NULL,
  `id_docente` int(11) NOT NULL,
  `capacidad` int(2) NOT NULL,
  `anho` varchar(9) NOT NULL,
  `tipo_curso` enum('nuevo','repitiente') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id` int(11) NOT NULL,
  `cedula` int(8) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `gdo_ins` enum('grado 1','grado 2','grado 3','grado 4','grado 5','grado 6','grado 7') NOT NULL,
  `habilitado` tinyint(1) NOT NULL,
  `id_usuariod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas_inscripcion`
--

CREATE TABLE `estadisticas_inscripcion` (
  `año` year(4) NOT NULL,
  `total_estudiantes` int(11) NOT NULL,
  `total_masculinos` int(11) NOT NULL,
  `total_femeninos` int(11) NOT NULL,
  `total_nuevos` int(11) NOT NULL,
  `total_regulares` int(11) NOT NULL,
  `total_repitientes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_reinscripcion`
--

CREATE TABLE `estado_reinscripcion` (
  `id_estado` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `anho_escolar` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_registro` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id_estudiante` int(11) NOT NULL,
  `cedula_e` int(8) NOT NULL,
  `apellidos_e` varchar(50) NOT NULL,
  `nombres_e` varchar(50) NOT NULL,
  `fecha_nac` date NOT NULL,
  `edad_e` int(11) NOT NULL,
  `sexo` enum('Masculino','Femenino') NOT NULL,
  `peso` int(3) NOT NULL,
  `talla` enum('SS','S','M','L') NOT NULL,
  `lugar_nac` varchar(50) NOT NULL,
  `municipio` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `parroquia` varchar(50) NOT NULL,
  `direccion_e` varchar(100) NOT NULL,
  `tel_e` varchar(12) NOT NULL,
  `posee_can` enum('Si','No') NOT NULL,
  `serial_can` varchar(50) DEFAULT NULL,
  `becado` enum('Si','No') NOT NULL,
  `copias_ci` enum('Si','No') NOT NULL,
  `partida_nac_co` enum('Si','No') NOT NULL,
  `historia_esc` enum('Si','No') NOT NULL,
  `cert_prom` enum('Si','No') NOT NULL,
  `tarj_vac` enum('Si','No') NOT NULL,
  `cons_conduc` enum('Si','No') NOT NULL,
  `cons_retiro` enum('Si','No') NOT NULL,
  `cert_notas` enum('Si','No') NOT NULL,
  `fotos` enum('si','no') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `id` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_lapso` int(11) NOT NULL,
  `calificacion` enum('A','B','C','D','E') NOT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familia`
--

CREATE TABLE `familia` (
  `id_f` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_representante` int(11) NOT NULL,
  `id_padre_madre` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `orden` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_sesiones`
--

CREATE TABLE `historial_sesiones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_hora_inicio` datetime NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `id` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_representante` int(11) NOT NULL,
  `id_tipo_ins` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lapsos`
--

CREATE TABLE `lapsos` (
  `id_lapso` int(11) NOT NULL,
  `nombre_lapso` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `padre_madre`
--

CREATE TABLE `padre_madre` (
  `id_padre_madre` int(11) NOT NULL,
  `cedula_p` int(8) NOT NULL,
  `apellidos_p` varchar(50) NOT NULL,
  `nombres_p` varchar(50) NOT NULL,
  `fecha_nac_p` date NOT NULL,
  `edad` int(11) NOT NULL,
  `ocupacion_p` enum('Estudiante','Empleado','Obrero','Desempleado','Otro') NOT NULL,
  `tel_p` bigint(11) NOT NULL,
  `direccion_p` varchar(100) NOT NULL,
  `parentesco` enum('Padre','Madre') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representante`
--

CREATE TABLE `representante` (
  `id_representante` int(11) NOT NULL,
  `cedula_r` int(8) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `edad` int(11) NOT NULL,
  `ocupacion` enum('Estudiante','Empleado','Obrero','Desempleado','Otro') NOT NULL,
  `tel_r` bigint(12) NOT NULL,
  `direccion_r` varchar(100) NOT NULL,
  `parentesco` enum('Padre','Madre','Tio','Hermano','Otro') DEFAULT NULL,
  `ingreso_mes` int(20) DEFAULT NULL,
  `carnet_patria` enum('Si','No') NOT NULL,
  `ser_car` int(24) DEFAULT NULL,
  `codigo_car` int(24) DEFAULT NULL,
  `entidad_ban` varchar(50) DEFAULT NULL,
  `tipo_cta` enum('Corriente','Ahorro','No posee') DEFAULT NULL,
  `num_cuenta` bigint(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(10) NOT NULL,
  `etapa` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_ins`
--

CREATE TABLE `tipo_ins` (
  `id` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nom_ape` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `gdo_ins` enum('Licenciado','T.S.U','Bachiller') NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `id_cargo` enum('1','2','3') NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `tiempo_inicio` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tiempo_cierre` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id_aula`);

--
-- Indices de la tabla `boletines`
--
ALTER TABLE `boletines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_evaluacion` (`id_evaluacion`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombre_cargo` (`nombre_cargo`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_aula` (`id_aula`),
  ADD KEY `id_grado` (`id_grado`),
  ADD KEY `id_seccion` (`id_seccion`),
  ADD KEY `id_docente` (`id_docente`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cedula` (`cedula`),
  ADD KEY `fk_docentes_usuarios` (`id_usuariod`);

--
-- Indices de la tabla `estadisticas_inscripcion`
--
ALTER TABLE `estadisticas_inscripcion`
  ADD PRIMARY KEY (`año`);

--
-- Indices de la tabla `estado_reinscripcion`
--
ALTER TABLE `estado_reinscripcion`
  ADD PRIMARY KEY (`id_estado`),
  ADD KEY `id_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `cedula_e` (`cedula_e`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_lapso_2` (`id_lapso`),
  ADD UNIQUE KEY `id_lapso_4` (`id_lapso`),
  ADD UNIQUE KEY `id_curso` (`id_curso`,`id_estudiante`,`id_lapso`),
  ADD KEY `id_lapso` (`id_lapso`),
  ADD KEY `id_lapso_3` (`id_lapso`),
  ADD KEY `id_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `familia`
--
ALTER TABLE `familia`
  ADD PRIMARY KEY (`id_f`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_representante` (`id_representante`),
  ADD KEY `id_padre_madre` (`id_padre_madre`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_sesiones`
--
ALTER TABLE `historial_sesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_representante` (`id_representante`),
  ADD KEY `id_tipo_ins` (`id_tipo_ins`);

--
-- Indices de la tabla `lapsos`
--
ALTER TABLE `lapsos`
  ADD PRIMARY KEY (`id_lapso`);

--
-- Indices de la tabla `padre_madre`
--
ALTER TABLE `padre_madre`
  ADD PRIMARY KEY (`id_padre_madre`),
  ADD UNIQUE KEY `cedula_p` (`cedula_p`);

--
-- Indices de la tabla `representante`
--
ALTER TABLE `representante`
  ADD PRIMARY KEY (`id_representante`),
  ADD UNIQUE KEY `cedula_r_2` (`cedula_r`),
  ADD KEY `cedula_r` (`cedula_r`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_ins`
--
ALTER TABLE `tipo_ins`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cargo` (`id_cargo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id_aula` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `boletines`
--
ALTER TABLE `boletines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_reinscripcion`
--
ALTER TABLE `estado_reinscripcion`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `familia`
--
ALTER TABLE `familia`
  MODIFY `id_f` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_sesiones`
--
ALTER TABLE `historial_sesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lapsos`
--
ALTER TABLE `lapsos`
  MODIFY `id_lapso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `padre_madre`
--
ALTER TABLE `padre_madre`
  MODIFY `id_padre_madre` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `representante`
--
ALTER TABLE `representante`
  MODIFY `id_representante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_ins`
--
ALTER TABLE `tipo_ins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `boletines`
--
ALTER TABLE `boletines`
  ADD CONSTRAINT `boletines_ibfk_1` FOREIGN KEY (`id_evaluacion`) REFERENCES `evaluaciones` (`id`);

--
-- Filtros para la tabla `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id_aula`),
  ADD CONSTRAINT `curso_ibfk_2` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id`),
  ADD CONSTRAINT `curso_ibfk_3` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id`),
  ADD CONSTRAINT `curso_ibfk_4` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id`);

--
-- Filtros para la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `fk_docentes_usuarios` FOREIGN KEY (`id_usuariod`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estado_reinscripcion`
--
ALTER TABLE `estado_reinscripcion`
  ADD CONSTRAINT `estado_reinscripcion_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`);

--
-- Filtros para la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD CONSTRAINT `evaluaciones_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id`),
  ADD CONSTRAINT `evaluaciones_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`),
  ADD CONSTRAINT `evaluaciones_ibfk_3` FOREIGN KEY (`id_lapso`) REFERENCES `lapsos` (`id_lapso`);

--
-- Filtros para la tabla `familia`
--
ALTER TABLE `familia`
  ADD CONSTRAINT `familia_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `familia_ibfk_2` FOREIGN KEY (`id_representante`) REFERENCES `representante` (`id_representante`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `familia_ibfk_3` FOREIGN KEY (`id_padre_madre`) REFERENCES `padre_madre` (`id_padre_madre`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_sesiones`
--
ALTER TABLE `historial_sesiones`
  ADD CONSTRAINT `historial_sesiones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id`),
  ADD CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`),
  ADD CONSTRAINT `inscripcion_ibfk_3` FOREIGN KEY (`id_representante`) REFERENCES `representante` (`id_representante`),
  ADD CONSTRAINT `inscripcion_ibfk_4` FOREIGN KEY (`id_tipo_ins`) REFERENCES `tipo_ins` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
