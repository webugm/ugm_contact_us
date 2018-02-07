CREATE TABLE ugm_cu_service (
  `service_sn` tinyint(3) unsigned NOT NULL  auto_increment,
  `service_name` varchar(255) NOT NULL default '' ,
  PRIMARY KEY  (`service_sn`)
) ENGINE=MyISAM;


CREATE TABLE ugm_contact_us (
  `cu_sn` smallint(5) unsigned NOT NULL  auto_increment,
  `cu_condition` tinyint(3) unsigned NOT NULL  ,
  `cu_name` varchar(255) NOT NULL default '' ,
  `cu_mail` varchar(255) NOT NULL default '' ,
  `cu_tel` varchar(255) NOT NULL default '' ,
  `cu_mobile` varchar(255) NOT NULL default '' ,
  `cu_time` varchar(255) NOT NULL default '' ,
  `cu_service` varchar(255) NOT NULL default '' ,
  `cu_content` text NOT NULL default '' ,
  `cu_completion_date` date NOT NULL default '0000-00-00' ,
  `cu_post_date` date NOT NULL default '0000-00-00' ,
  `cu_unit_sn` smallint(5) unsigned NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`cu_sn`)
) ENGINE=MyISAM;


CREATE TABLE ugm_cu_solution (
  `cu_sn` mediumint(8) unsigned NOT NULL  ,
  `solution_title` varchar(255) NOT NULL default '' ,
  `solution_date` date NOT NULL default '0000-00-00' ,
  KEY  (`cu_sn`)
) ENGINE=MyISAM;

CREATE TABLE ugm_contact_unit (
  `sn` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sn`)
) ENGINE=MyISAM;

