CREATE TABLE todos (
    id serial PRIMARY KEY,
    date_time timestamp,
    done boolean,
    description varchar(255)
);
