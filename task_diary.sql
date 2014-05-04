SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `task_diary` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `task_diary` ;

-- -----------------------------------------------------
-- Table `task_diary`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`users` (
  `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(255) NOT NULL,
  `user_mail` VARCHAR(255) NOT NULL,
  `user_password` VARCHAR(255) NOT NULL,
  `user_image` BLOB NULL,
  `user_authority` TINYINT NOT NULL COMMENT '0:管理者\n1:一般ユーザー\n2:未認証ユーザー',
  `user_created` DATETIME NOT NULL,
  `user_modified` DATETIME NOT NULL,
  `user_del_flg` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `user_name_UNIQUE` (`user_name` ASC),
  UNIQUE INDEX `user_mail_UNIQUE` (`user_mail` ASC),
  UNIQUE INDEX `user_password_UNIQUE` (`user_password` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_diary`.`activation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`activation` (
  `activation_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `activation_token` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`activation_id`),
  INDEX `fk_activation_user_idx` (`user_id` ASC),
  CONSTRAINT `fk_activation_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `task_diary`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_diary`.`autologin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`autologin` (
  `autologin_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `autologin_token` VARCHAR(255) NOT NULL,
  `expired` DATETIME NOT NULL,
  PRIMARY KEY (`autologin_id`),
  INDEX `fk_autologin_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_autologin_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `task_diary`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_diary`.`twitter_oauth`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`twitter_oauth` (
  `tw_oauth_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `twitter_id` VARCHAR(255) NOT NULL,
  `tw_access_token` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`tw_oauth_id`),
  INDEX `fk_twitter_oauth_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_twitter_oauth_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `task_diary`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_diary`.`projects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`projects` (
  `project_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_name` VARCHAR(255) NOT NULL,
  `project_text` TEXT NULL,
  `project_created` DATETIME NOT NULL,
  `project_modified` DATETIME NOT NULL,
  `project_del_flg` TINYINT NOT NULL DEFAULT 0 COMMENT 'o:有効\n1:削除',
  PRIMARY KEY (`project_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_diary`.`users_projects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`users_projects` (
  `users_projects_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `project_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`users_projects_id`),
  INDEX `fk_users_projects_users1_idx` (`user_id` ASC),
  INDEX `fk_users_projects_projects1_idx` (`project_id` ASC),
  CONSTRAINT `fk_users_projects_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `task_diary`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_projects_projects1`
    FOREIGN KEY (`project_id`)
    REFERENCES `task_diary`.`projects` (`project_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_diary`.`tasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`tasks` (
  `task_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `project_id` INT UNSIGNED NULL,
  `task_name` VARCHAR(255) NOT NULL,
  `task_id_done` TINYINT NULL,
  `task_created` DATETIME NOT NULL,
  `task_modified` DATETIME NOT NULL,
  `task_del_flg` TINYINT NOT NULL DEFAULT 0 COMMENT 'o:有効\n1:削除',
  PRIMARY KEY (`task_id`),
  INDEX `fk_tasks_projects1_idx` (`project_id` ASC),
  INDEX `fk_tasks_users1_idx` (`user_id` ASC),
  CONSTRAINT `fk_tasks_projects1`
    FOREIGN KEY (`project_id`)
    REFERENCES `task_diary`.`projects` (`project_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tasks_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `task_diary`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_diary`.`categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`categories` (
  `category_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `category_name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`category_id`),
  INDEX `fk_categories_users1_idx` (`user_id` ASC),
  CONSTRAINT `fk_categories_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `task_diary`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_diary`.`tasks_categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`tasks_categories` (
  `tasks_categories_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`tasks_categories_id`),
  INDEX `fk_tasks_categories_tasks1_idx` (`task_id` ASC),
  INDEX `fk_tasks_categories_categories1_idx` (`category_id` ASC),
  CONSTRAINT `fk_tasks_categories_tasks1`
    FOREIGN KEY (`task_id`)
    REFERENCES `task_diary`.`tasks` (`task_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tasks_categories_categories1`
    FOREIGN KEY (`category_id`)
    REFERENCES `task_diary`.`categories` (`category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_diary`.`comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`comments` (
  `commect_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `task_id` INT UNSIGNED NOT NULL,
  `comment_text` TEXT NOT NULL,
  `comment_created` DATETIME NOT NULL,
  `comment_modified` DATETIME NOT NULL,
  `comment_del_flg` TINYINT NOT NULL DEFAULT 0 COMMENT 'o:有効\n1:削除',
  PRIMARY KEY (`commect_id`),
  INDEX `fk_comments_users1_idx` (`user_id` ASC),
  INDEX `fk_comments_tasks1_idx` (`task_id` ASC),
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `task_diary`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_tasks1`
    FOREIGN KEY (`task_id`)
    REFERENCES `task_diary`.`tasks` (`task_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_diary`.`facebook_oauth`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_diary`.`facebook_oauth` (
  `tb_oauth_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `facebook_id` VARCHAR(255) NOT NULL,
  `fb_access_token` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`tb_oauth_id`),
  INDEX `fk_twitter_oauth_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_twitter_oauth_user10`
    FOREIGN KEY (`user_id`)
    REFERENCES `task_diary`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
