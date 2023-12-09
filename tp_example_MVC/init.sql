CREATE TABLE musics (
    id serial PRIMARY KEY,
    created_at timestamp DEFAULT NOW(),
    url varchar(255) NOT NULL UNIQUE
);


