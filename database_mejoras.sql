-- Ejecuta este archivo una vez en la base de datos la_pesquera.
CREATE TABLE IF NOT EXISTS categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion VARCHAR(255) NULL,
  estado TINYINT(1) NOT NULL DEFAULT 1,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT IGNORE INTO categorias (nombre, descripcion) VALUES
 ('Pescados y Carnes','Platos principales'), ('Sopas','Sopas'), ('Porciones','Acompañamientos'), ('Bebidas','Bebidas');
CREATE TABLE IF NOT EXISTS password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY, usuario_id INT NOT NULL, token_hash CHAR(64) NOT NULL,
  expira_en DATETIME NOT NULL, usado_en DATETIME NULL, creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(token_hash), FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
