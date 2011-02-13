CREATE TABLE IF NOT EXISTS connection (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	name TEXT,
	type TEXT,
	host TEXT,
	username TEXT,
	password TEXT,
	port INTEGER,
	default_database TEXT,
	active INTEGER DEFAULT 1,
	mdate TEXT,
	cdate TEXT,
	deleted INTEGER
);

CREATE TABLE IF NOT EXISTS user (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	username TEXT,
	password TEXT,
	first_name TEXT,
	last_name TEXT,
	active INTEGER DEFAULT 1,
	last_login TEXT,
	login_count INTEGER DEFAULT 0,
	mdate TEXT,
	cdate TEXT,
	deleted INTEGER
);

CREATE TABLE IF NOT EXISTS connection_user (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	connection_id TEXT,
	user_id TEXT,
	mdate TEXT,
	cdate TEXT,
	deleted INTEGER
);