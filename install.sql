CREATE TABLE IF NOT EXISTS `modx_letters_categories` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
);


CREATE TABLE IF NOT EXISTS `modx_letters_newsletter` (
  `id` int(10) NOT NULL auto_increment,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) NOT NULL DEFAULT '0',
  `sent` int(11) NOT NULL DEFAULT '0',
  `template` longtext,
  `subject` varchar(255) NOT NULL,
  `newsletter` longtext,
  `cat_id` varchar(255) NOT NULL,
  `log` longtext,
  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `modx_letters_subscribers` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '1',
  `blocked` int(11) NOT NULL DEFAULT '0',
  `lastnewsletter` varchar(255) NOT NULL,
  `created` timestamp NOT NULL,
  `cat_id` varchar(255) NOT NULL COMMENT 'поле json в котором перечисляются категории писем, закрепленным за подписчиками',
  PRIMARY KEY  (`id`)
);


CREATE TABLE IF NOT EXISTS `modx_letters_templates` (
  `id` int(11) NOT NULL auto_increment,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY  (`id`)
);


INSERT INTO `modx_letters_templates` (`id`, `date`, `title`, `description`, `code`) VALUES
(NULL, '2017-01-17 11:07:00', 'Пустой шаблон по умолчанию', 'Описание', '{{ content|raw }}');

INSERT INTO `modx_letters_categories` (`id`, `title`, `description`) VALUES
(NULL, 'Моя первая категория', 'Первая категория по умолчанию для подписчиков');
