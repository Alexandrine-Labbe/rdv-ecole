create extension "uuid-ossp";

create table enseignant (
     id_enseignant uuid primary key default uuid_generate_v4(),
     first_name varchar not null,
     last_name varchar not null,
     email varchar not null,
     password varchar not null,
     school varchar not null
);

create table enfant (
    id_enfant uuid primary key default uuid_generate_v4(),
    name varchar not null,
    birthdate date not null,
    id_enseignant uuid references enseignant(id_enseignant)
);

create table parent (
    id_parent uuid primary key default uuid_generate_v4(),
    name_enfant varchar not null,
    name_parent varchar not null,
    email varchar not null
);

create table parent_enfant (
    id_parent uuid references parent(id_parent),
    id_enfant uuid references enfant(id_enfant),
    primary key (id_parent, id_enfant)
);

create table rdv (
     id_rdv uuid primary key default uuid_generate_v4(),
     date timestamp not null,
     id_enfant uuid references enfant(id_enfant),
     id_enseignant uuid references enseignant(id_enseignant)
);

