CREATE TABLE IF NOT EXISTS connection (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	name TEXT,
	host TEXT,
	username TEXT,
	password TEXT,
	port INTEGER,
	mdate TEXT,
	cdate TEXT,
	deleted INTEGER
);