CREATE TABLE IF NOT EXISTS `TABLE_SPLITTEST_RANDOMMEMBERS` (
  `id` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `TABLE_SPLITTEST_MEMBERS` (
  `id` int(11) NOT NULL auto_increment,
  `Campaigns_id` int(11) NOT NULL,
  `Member_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `Member_id` (`Member_id`),
  KEY `Campaigns_id` (`Campaigns_id`)
);
