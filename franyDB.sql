CREATE DATABASE IF NOT EXISTS frany_bkack;

USE frany_bkack;

CREATE TABLE sport(
    id int(255) auto_increment not null,
    nombre varchar(255),
    descripcion text,
    Imagen  varchar(255),
    CONSTRAINT pk_sport PRIMARY KEY(id)
);

CREATE TABLE fashion(
    id int(255) auto_increment not null,
    nombre varchar(255),
    descripcion text,
    Imagen  varchar(255),
    CONSTRAINT pk_fashion PRIMARY KEY(id)
);

CREATE TABLE lingerie(
    id int(255) auto_increment not null,
    nombre varchar(255),
    descripcion text,
    Imagen  varchar(255),
    CONSTRAINT pk_lingerie PRIMARY KEY(id)
);

CREATE TABLE nu_artistique(
    id int(255) auto_increment not null,
    nombre varchar(255),
    descripcion text,
    Imagen  varchar(255),
    CONSTRAINT pk_nu_artistique PRIMARY KEY(id)
);