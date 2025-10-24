
-- Base de datos TechNova (MySQL)
CREATE DATABASE IF NOT EXISTS technova CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE technova;

CREATE TABLE IF NOT EXISTS usuarios(
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  correo VARCHAR(160) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol ENUM('cliente','admin') NOT NULL DEFAULT 'cliente'
);

CREATE TABLE IF NOT EXISTS productos(
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(140) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL DEFAULT 0,
  stock INT NOT NULL DEFAULT 0,
  imagen VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS cart_items(
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_producto INT NOT NULL,
  cantidad INT NOT NULL DEFAULT 1,
  FOREIGN KEY(id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY(id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ventas(
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  fecha DATETIME NOT NULL,
  FOREIGN KEY(id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS detalle_venta(
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_venta INT NOT NULL,
  id_producto INT NOT NULL,
  cantidad INT NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  FOREIGN KEY(id_venta) REFERENCES ventas(id) ON DELETE CASCADE,
  FOREIGN KEY(id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

-- Usuario administrador por defecto (password: admin123)
INSERT INTO usuarios(nombre,correo,password,rol) VALUES
('Administrador','admin@technova.com', '$2y$10$V7G0V0z9q1gH0m2N6XgO7e9e9l1m3i6Qn0lK5rV0xO0C6mN3oKxWa', 'admin');

-- Productos de ejemplo
INSERT INTO productos(nombre, descripcion, precio, stock) VALUES
('Laptop TechNova X1','Laptop Ryzen 5, 16GB RAM, 512GB SSD', 2699.00, 10),
('PC Gamer NovaG','Ryzen 7, RTX 4060, 32GB RAM, 1TB SSD', 5499.00, 5),
('Mouse Inalámbrico','Mouse ergonómico 2.4G', 59.90, 50);
