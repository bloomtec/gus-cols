# Quitar llaves foraneas colecciones
ALTER TABLE `colecciones` DROP FOREIGN KEY `fk_colecciones_grupos`;
ALTER TABLE `colecciones` DROP FOREIGN KEY `fk_colecciones_usuarios`;
ALTER TABLE `colecciones` DROP FOREIGN KEY `fk_colecciones_colecciones`;
ALTER TABLE `colecciones` DROP FOREIGN KEY `fk_colecciones_campos`;

# Quitar llaves foraneas campos
ALTER TABLE `campos` DROP FOREIGN KEY `fk_campos_tipos_de_campos`;
ALTER TABLE `campos` DROP FOREIGN KEY `fk_campos_colecciones`;
ALTER TABLE `campos` DROP FOREIGN KEY `fk_campos_campos_elementos`;
ALTER TABLE `campos` DROP FOREIGN KEY `fk_campos_usuarios`;
ALTER TABLE `campos` DROP FOREIGN KEY `fk_campos_campos_padres`;

# Eliminar las tablas
DROP TABLE colecciones_grupos;
DROP TABLE grupos_usuarios;
DROP TABLE auditorias;
DROP TABLE logs;
DROP TABLE campos;
DROP TABLE tipos_de_campos;
DROP TABLE colecciones;
DROP TABLE grupos;
DROP TABLE usuarios;