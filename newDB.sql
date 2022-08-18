-- date es considerado una funcion por eso se utiliza `` para que lo considere nombre columna
-- en los id es recomendable usar UNSIGNED para que solo se consideren positivos
-- UNIQUE KEY A VALOS QUE NO SE DEBEN REPETIR
-- (QUITE EL UNSIGNED PORQUE NO DEJA VINCULAR LAS FK)
-- BASE DE DATOS MULTICAJAS

create table categories (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
name varchar(60)  UNIQUE KEY NOT NULL
);

create table media (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
file_name varchar(255),
file_type varchar(100)
);

create table products (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
name varchar(255) UNIQUE KEY NOT NULL,
quantity varchar(50) NOT NULL,
buy_price decimal(25,2)NOT NULL,
sale_price decimal(25,2),
categorie_id int(11),
media_id int(11),
`date` datetime,
FOREIGN KEY (categorie_id) REFERENCES categories(id),
FOREIGN KEY (media_id) REFERENCES media(id)
);

create table user_groups (
id int(11)UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
group_name varchar(150),
group_level int(11) UNIQUE KEY,
group_status int(11)
);

create table users (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
name varchar(60),
username varchar(60) UNIQUE KEY,
password varchar(255),
user_level int(11),
image varchar(255),
status int(11),
last_login datetime,
FOREIGN KEY (user_level) REFERENCES user_groups(group_level)
);

create table sales (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
user_id int(11),
price_total decimal(25,2),
`date` datetime,
status int(10),
FOREIGN KEY (user_id) REFERENCES users(id)
);

create table sale_detail (
id int(11) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
sale_id int(11),
product_id int(11),
qty int(11),
price decimal(25,2),
FOREIGN KEY (sale_id) REFERENCES sales(id),
FOREIGN KEY (product_id) REFERENCES products(id)
);

-- en esta tabla tendremos los tipos de movimientos de efectivo 
-- ya sea traspaso entre cajas, ingreso o egreso de dinero.
create table cash_type (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
name varchar(60) UNIQUE KEY,
status int(11)
);

-- Aqui se registra cada moviemiento
-- ya sea ingreso, egreso de dinero
create table cash (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
total int(10),
type_id int(11),
`date` datetime,
FOREIGN KEY (type_id) REFERENCES cash_type(id)
);

-- tabla de estado de cada caja
-- abierta, cerrada, bloqueada etc..
create table box_status (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
name varchar(60) UNIQUE KEY
);

-- tabla de cajas registradas
-- caja01, caja02 ...
create table box (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
name varchar(60) UNIQUE KEY,
status_id int(11),
total_cash int(11),
FOREIGN KEY (status_id) REFERENCES box_status(id)
);

-- conteo de billetes y monedas por denominaciones
-- vinculada a cada caja registrada
create table detail_cash (
20mil int(10),
10mil int(10),
5mil int(10),
2mil int(10),
mil int(10),
quinientos int(10),
cien int(10),
cincuenta int(10),
diez int(10),
cinco int(10),
uno int(10),
box_id int(11),
FOREIGN KEY (box_id) REFERENCES `box`(id)
);

-- columna group_status es binario 0=Inactivo y 1=Activo 
INSERT INTO user_groups (id, group_name, group_level, group_status) 
	VALUES (1, 'Admin', 1, 1), (2, 'Special', 2, 0), (3, 'User', 3, 1);

-- columna user_level es descendente (el 1 tienes mas poder)
INSERT INTO users (id, name, username, password, user_level, image, status, last_login) 
VALUES 
(1, 'Admin Users', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'pzg9wa7o1.jpg', 1, '2022-06-16 07:11:11'),
(2, 'Special User', 'special', 'ba36b97a41e7faf742ab09bf88405ac04f99599a', 2, 'no_image.jpg', 1, '2022-06-16 07:11:26'),
(3, 'Default User', 'user', '12dea96fec20593566ab75692c9949596833adc9', 3, 'no_image.jpg', 1, '2022-06-16 07:11:03');

-- columna group_status es binario 0=Inactivo y 1=Activo 
INSERT INTO media (id, file_name, file_type) 
	VALUES (1, 'no_image.jpg', 'image/jpg');