use dev_perfect_todo;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_mail` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `user_created` datetime DEFAULT NULL,
  `user_modified` datetime DEFAULT NULL,
  `user_del_flg` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`user_name`),
  UNIQUE KEY `mail_UNIQUE` (`user_mail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_name` text NOT NULL,
  `project_text` text,
  `project_created` datetime DEFAULT NULL,
  `project_modified` datetime DEFAULT NULL,
  `project_del_flg` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`project_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `task_name` text NOT NULL,
  `task_text` text,
  `task_is_done` tinyint(1) NOT NULL DEFAULT '0',
  `task_limit` datetime DEFAULT NULL,
  `task_size` enum('middle','short') NOT NULL,
  `task_created` datetime DEFAULT NULL,
  `task_modified` datetime DEFAULT NULL,
  `task_del_flg` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`task_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`);
