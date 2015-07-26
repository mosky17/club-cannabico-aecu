CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `secreto` varchar(300) NOT NULL,
  `permiso_pagos` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `name` varchar(50) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `entregas`
--

CREATE TABLE `entregas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_socio` int(11) NOT NULL,
  `gramos` decimal(10,0) NOT NULL,
  `notas` text NOT NULL,
  `fecha` date NOT NULL,
  `cancelado` tinyint(1) NOT NULL,
  `id_genetica` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Table structure for table `datos`
--

CREATE TABLE `datos` (
  `codigo` varchar(100) NOT NULL,
  `valor` varchar(200) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gastos`
--

CREATE TABLE `gastos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor` decimal(10,2) NOT NULL,
  `razon` varchar(500) NOT NULL,
  `fecha_pago` date NOT NULL,
  `notas` text NOT NULL,
  `cancelado` tinyint(1) NOT NULL,
  `rubro` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=169 ;

-- --------------------------------------------------------

--
-- Table structure for table `geneticas`
--

CREATE TABLE `geneticas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `origen` varchar(200) NOT NULL,
  `detalles` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `informes_cosecha`
--

CREATE TABLE `informes_cosecha` (
  `fecha` date NOT NULL,
  `id_genetica` int(11) NOT NULL,
  `cantidad_plantas` int(11) NOT NULL,
  `peso_total_fresco` decimal(10,0) NOT NULL,
  `peso_total_seco` decimal(10,0) NOT NULL,
  `lote` varchar(50) NOT NULL,
  `responsable_tecnico` varchar(100) NOT NULL,
  `responsable_produccion` varchar(100) NOT NULL,
  `borrado` tinyint(1) NOT NULL,
  `aclaraciones` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `tag` varchar(50) NOT NULL,
  `mensaje` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=66 ;

-- --------------------------------------------------------

--
-- Table structure for table `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_socio` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `razon` varchar(200) NOT NULL,
  `fecha_pago` date NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `notas` text NOT NULL,
  `cancelado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=359 ;

-- --------------------------------------------------------

--
-- Table structure for table `socios`
--

CREATE TABLE `socios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `documento` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `tags` varchar(500) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `observaciones` varchar(500) NOT NULL,
  `gramos_asignados` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `hash` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=104 ;

-- --------------------------------------------------------

--
-- Table structure for table `recordatorios_deuda`
--

CREATE TABLE `recordatorios_deuda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_socio` int(11) NOT NULL,
  `monto` decimal(10,0) NOT NULL,
  `razon` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

INSERT INTO `tags` (`id`, `nombre`, `color`) VALUES (NULL, 'Medicinal', '42E216'), (NULL, 'Pago Diferenciado', 'BF5213'), (NULL, 'Fundador', '4699d4');
