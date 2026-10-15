CREATE TABLE users (
  user_id SERIAL PRIMARY KEY,
  LastName varchar(255) NOT NULL,
  FirstName varchar(255),
  email varchar(255) UNIQUE NOT NULL,
  password_hash varchar(255) NOT NULL
);

CREATE TABLE rooms (
  room_id SERIAL PRIMARY KEY,
  room_name varchar(255)
);

CREATE TABLE room_presets (
room_id int PRIMARY KEY REFERENCES rooms(room_id) ON DELETE CASCADE,
cleaning_up_cm int,
cleaning_down_cm int,
pretty_picture_cm int
);

CREATE TABLE desks (
desk_id SERIAL PRIMARY KEY,     
room_id int REFERENCES rooms(room_id) ON DELETE SET NULL,   
desk_name varchar(255),
pairing_code int,
user_token varchar(32)
);

CREATE TABLE user_presets (
preset_id SERIAL PRIMARY KEY,
user_id int REFERENCES users(user_id) ON DELETE CASCADE,   
sit_height_cm int,
stand_height_cm int
);