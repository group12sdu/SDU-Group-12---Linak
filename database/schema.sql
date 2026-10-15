CREATE TABLE users (
  user_id int PRIMARY KEY,
  LastName varchar(255) NOT NULL,
  FirstName varchar(255),
  email varchar(255)
);

CREATE TABLE rooms (
  room_id SERIAL PRIMARY KEY,
  room_name varchar(255)
);

CREATE TABLE room_presets (
room_id int REFERENCES rooms(room_id),    
cleaning_up_cm int,
cleaning_down_cm int,
pretty_picture_cm int
);

CREATE TABLE desks (
desk_id int PRIMARY KEY,     
room_id int REFERENCES rooms(room_id),   
desk_name varchar(255),
pairing_code int,
active_user_id int
);

CREATE TABLE user_presets (
preset_id int PRIMARY KEY,
user_id int REFERENCES users(user_id),   
sit_height_cm int,
stand_height_cm int
);