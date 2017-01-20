CREATE TABLE IF NOT EXISTS `contact` (
  `visitor_id` smallint(6) NOT NULL,
  `visitor_name` varchar(40) NOT NULL,
  `visitor_email` varchar(60) NOT NULL,
  `visitor_message` varchar(300) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `visitors` (
  `visitor_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `visitor_ip4` varchar(85) NOT NULL,
  `visitor_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `visitor_total` int(11) NOT NULL,
  PRIMARY KEY (`visitor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
