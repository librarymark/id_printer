# AdminPro Class Database Setup
# Version: 1.3
# Author: Giorgos Tsiledakis
#
#Please check the configuration file to ensure that the variables are the same
#
# Structure of table `myuser`
#

CREATE TABLE `myuser` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `userName` char(50) binary default NULL,
  `userPass` char(50) binary default NULL,
  `isAdmin` tinyint(2) NOT NULL default '-1',
  `userGroup` int(10) unsigned default '1',
  `sessionID` char(50) default NULL,
  `lastLog` datetime default NULL,
  `userRemark` char(255) default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `userName` (`userName`)
) TYPE=MyISAM COMMENT='Created by the AdminPro Class MySQL Setup ' ;
    



#
# Data if md5 encryption is enabled
#

INSERT INTO `myuser` VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 1, '', '0000-00-00 00:00:00', 'Default Administrator');

#
# Data if md5 encryption is not enabled
#

#INSERT INTO `myuser` VALUES (1, 'admin', 'admin', 1, '', '0000-00-00 00:00:00', 'Default Administrator');
    


    

