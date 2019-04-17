CREATE DATABASE `cubex_skeleton`;

CREATE TABLE `cubex_skeleton`.`content_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
