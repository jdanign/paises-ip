/* Creación de la Base de Datos */
CREATE DATABASE IF NOT EXISTS AUREN;

/* Establece la base de datos en uso */
USE AUREN;




/* ------------------------- ENTIDADES ------------------------- */

CREATE TABLE `auren`.`direcciones_ip` (
  `di_ip` VARCHAR(15),
  `di_pais` VARCHAR(60) NULL,
  PRIMARY KEY (`di_ip`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;