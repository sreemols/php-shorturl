--
-- Database: `url_short`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shorturl`
--

DROP TABLE IF EXISTS `tbl_shorturl`;
CREATE TABLE IF NOT EXISTS `tbl_shorturl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` tinytext NOT NULL,
  `short_code` varchar(50) NOT NULL,
  `added_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
