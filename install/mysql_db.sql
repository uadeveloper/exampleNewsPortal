CREATE TABLE `news` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`short_content` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`content` MEDIUMTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`publication_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `publication_date` (`publication_date`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;
CREATE TABLE `users` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`login` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`password_hash` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `login` (`login`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;
CREATE TABLE `users_permissions` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`permission` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `name` (`permission`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;
CREATE TABLE `users_permissions_rel` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) NOT NULL,
	`permission_id` INT(10) NOT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `user_id_permission` (`user_id`, `permission_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;
INSERT INTO `newsportal`.`users` (`login`, `password_hash`) VALUES ('admin', '$2y$10$fLixEBb1XE60if.klgUxfevUE5cBblOgHBuG71x4VkLws5uDukMU2');
INSERT INTO `newsportal`.`users_permissions` (`permission`) VALUES ('admin');
INSERT INTO `newsportal`.`users_permissions` (`permission`) VALUES ('moderator');
INSERT INTO `newsportal`.`users_permissions` (`permission`) VALUES ('editor');
INSERT INTO `newsportal`.`users_permissions_rel` (`user_id`, `permission_id`) VALUES ('1', '1');