CREATE TABLE IF NOT EXISTS connection (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	name TEXT,
	type TEXT,
	host TEXT,
	username TEXT,
	password TEXT,
	port INTEGER,
	default_database TEXT,
	mdate TEXT,
	cdate TEXT,
	deleted INTEGER
);