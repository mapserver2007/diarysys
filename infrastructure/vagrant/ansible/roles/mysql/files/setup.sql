-- http://d.hatena.ne.jp/a666666/20100920/1284992435

DROP TABLE IF EXISTS T_Entry;
DROP TABLE IF EXISTS M_Tag;
DROP TABLE IF EXISTS M_EntryTag;
DROP TABLE IF EXISTS T_Image;
DROP TABLE IF EXISTS M_Category;

CREATE TABLE `T_Entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `category_id` int(11) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IX_CREATED_AT` (`created_at`),
  FULLTEXT INDEX `IX_FULLTEXT_TITLE_DESCRIPTION` (title, description)
) ENGINE=mroonga DEFAULT CHARSET=utf8 COMMENT='ENGINE "InnoDB"';

CREATE TABLE `M_Tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IX_ID_NAME` (`id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `M_EntryTag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IX_ENTRY_ID` (`entry_id`),
  KEY `IX_TAG_ID` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE T_Image (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `path_large` VARCHAR(200) NOT NULL,
    `path_small` VARCHAR(200) NOT NULL,
    `created_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `M_Category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IX_ID_NAME` (`id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
