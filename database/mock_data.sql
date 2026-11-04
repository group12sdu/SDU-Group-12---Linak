TRUNCATE TABLE desks, user_presets, users, room_presets, rooms RESTART IDENTITY CASCADE;

INSERT INTO rooms (room_id, room_name) VALUES
(101, 'Open Space North - Floor 1'),
(102, 'Dev Team Room J1.04'),
(103, 'Meeting Room A'),
(201, 'Executive Suite - Floor 2');

INSERT INTO room_presets (room_id, cleaning_up_cm, cleaning_down_cm, pretty_picture_cm) VALUES
(101, 120, 68, 95),
(102, 125, 65, 100),
(103, 115, 70, 90),
(201, 120, 68, 95);

INSERT INTO users (user_id, lastname, firstname, email, password_hash) VALUES
(1, 'Falat', 'Daniel', 'dafal24@student.sdu.dk', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855'),
(2, 'Cunderlik', 'Filip', 'ficun24@student.sdu.dk', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3'),
(3, 'Bezo', 'Dominik', 'dobez24@student.sdu.dk', 'b5a2c9b149d9e6f954366082470da7156951479e1285f3acc2e466d531188473'),
(4, 'Seliavko', 'Pavel', 'pasel24@student.sdu.dk', 'd4735e3a265e16eee03f59718b9b5d03019c07d8b6c51f90da3a666eec13ab35'),
(5, 'Schiwy', 'Zoe Elea', 'zosch24@student.sdu.dk', '4e07408562bedb8b60ce05c1decfe3ad16b72230967de01f640b7e4729b49fce'),
(6, 'Petrescu', 'David Nicolae', 'davpe24@student.sdu.dk', '4b227777d4dd1fc61c6f884f48641d02b4d121d3fd328cb08b5531fcacdabf8a');

INSERT INTO user_presets (preset_id, user_id, sit_height_cm, stand_height_cm) VALUES
(1, 1, 72, 108),
(2, 2, 75, 112),
(3, 3, 70, 105),
(4, 4, 74, 110),
(5, 5, 68, 102),
(6, 6, 76, 114);

INSERT INTO desks (desk_id, room_id, pairing_code, user_token) VALUES
(1, 102, 4821, 'dsk_a9f1c84b2e7041a9bc38d15a99'),
(2, 102, 9152, 'dsk_7e4b91ac0d554a3fae8912c47b'),
(3, 102, 3340, 'dsk_50c9b81f23aa4d7ea1198c0b2d'),
(4, 101, 7721, 'dsk_38fa0e99b41c48719acb184f67'),
(5, 101, 5509, NULL),
(6, 103, 1198, NULL);