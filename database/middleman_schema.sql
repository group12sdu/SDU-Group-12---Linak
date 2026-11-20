CREATE TABLE anonymous_links (
    link_id SERIAL PRIMARY KEY,
    session_hash varchar(64) NOT NULL UNIQUE,
    desk_token varchar(32) NOT NULL UNIQUE,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    expires_at timestamp NOT NULL
);
