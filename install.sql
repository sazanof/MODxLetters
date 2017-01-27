CREATE TABLE IF NOT EXISTS `modx_letters_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `modx_letters_newsletter` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) NOT NULL DEFAULT '0',
  `sent` int(11) NOT NULL DEFAULT '0',
  `template` longtext,
  `subject` varchar(255) NOT NULL,
  `newsletter` longtext,
  `cat_id` varchar(255) NOT NULL,
  `log` longtext
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `modx_letters_subscribers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '1',
  `blocked` int(11) NOT NULL DEFAULT '0',
  `lastnewsletter` varchar(255) NOT NULL,
  `created` timestamp NOT NULL,
  `cat_id` varchar(255) NOT NULL COMMENT 'поле json в котором перечисляются категории писем, закрепленным за подписчиками'
) ENGINE=InnoDB AUTO_INCREMENT=1161 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `modx_letters_templates` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `code` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


INSERT INTO `modx_letters_templates` (`id`, `date`, `title`, `description`, `code`) VALUES
(1, '2017-01-17 11:07:00', 'Пустой шаблон по умолчанию', 'Описание', '{{ content|raw }}');

ALTER TABLE `modx_letters_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `modx_letters_newsletter`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `modx_letters_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `modx_letters_templates`
  ADD PRIMARY KEY (`id`);
