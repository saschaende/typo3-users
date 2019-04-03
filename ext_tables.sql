CREATE TABLE fe_users (

	users_lastlogin int(11) unsigned DEFAULT '0' NOT NULL,
	users_logincount int(11) unsigned DEFAULT '0' NOT NULL,
	users_forgothash varchar(255) DEFAULT '' NOT NULL,
	users_forgothash_valid int(11) unsigned DEFAULT '0' NOT NULL,
	users_registerhash varchar(255) DEFAULT '' NOT NULL,
	users_conditions char(1) DEFAULT '' NOT NULL,
	users_dataprotection char(1) DEFAULT '' NOT NULL,

);
