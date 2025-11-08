CREATE DATABASE IF NOT EXISTS maniglio_registrazione;
use maniglio_registrazione;

create table utenti
(
    id                   int auto_increment
        primary key,
    username             varchar(255)                           not null,
    password             varchar(255)                           not null,
    nome                 varchar(255)                           not null,
    cognome              varchar(255)                           not null,
    email                varchar(100)                           not null,
    email_verified       tinyint(1) default 0                   not null,
    verification_token   varchar(255)                           null,
    verification_expires datetime                               null,
    created_at           datetime   default current_timestamp() not null,
    updated_at           datetime   default current_timestamp() not null on update current_timestamp(),
    constraint email_unique
        unique (email)
);

