CREATE TABLE fe_users (

	users_lastlogin int(11) unsigned DEFAULT '0' NOT NULL,
	users_logincount int(11) unsigned DEFAULT '0' NOT NULL,
	users_newemail varchar(255) DEFAULT '' NOT NULL,
	users_newemailhash varchar(255) DEFAULT '' NOT NULL,
	users_forgothash varchar(255) DEFAULT '' NOT NULL,
	users_forgothash_valid int(11) unsigned DEFAULT '0' NOT NULL,
	users_registerhash varchar(255) DEFAULT '' NOT NULL,
	users_conditions char(1) DEFAULT '' NOT NULL,
	users_dataprotection char(1) DEFAULT '' NOT NULL,
    users_newsletter char(1) DEFAULT '' NOT NULL,
    users_website int(11) unsigned DEFAULT '0' NOT NULL,
    users_language int(11) unsigned DEFAULT '0' NOT NULL,
    users_deletehash varchar(255) DEFAULT '' NOT NULL,
    users_deletehash_valid int(11) unsigned DEFAULT '0' NOT NULL,
);


CREATE TABLE tx_users_domain_model_bannedhosts (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	host varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);
