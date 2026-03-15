CREATE TABLE movies (
    id SERIAL PRIMARY KEY,
    movie_name VARCHAR(256),
    category VARCHAR(256),
    ratings VARCHAR(256),
    released_year TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted INTEGER DEFAULT 0
);