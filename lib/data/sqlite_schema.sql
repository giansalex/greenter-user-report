create table user
(
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	email TEXT,
	password TEXT,
	enable INTEGER
);

create table setting
(
	user_id INT PRIMARY KEY,
	parameters TEXT,
	logo_path TEXT
);

