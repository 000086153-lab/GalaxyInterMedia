CREATE DATABASE IF NOT EXISTS galaxyintermedia
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE galaxyintermedia;

CREATE TABLE IF NOT EXISTS registros (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  servicio VARCHAR(80) NOT NULL,
  mensaje TEXT NOT NULL,
  estado VARCHAR(30) NOT NULL DEFAULT 'Nuevo',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_registros_created_at (created_at),
  INDEX idx_registros_servicio (servicio),
  INDEX idx_registros_estado (estado),
  INDEX idx_registros_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

