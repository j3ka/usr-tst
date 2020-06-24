CREATE TABLE `users` (
	`id` INT unsigned,
	`username` VARCHAR(255),
	`email` VARCHAR(255),
	`currency` VARCHAR(10),
	`total` DECIMAL unsigned,
	UNIQUE KEY `id` (`id`) USING BTREE,
KEY `email` (`email`) USING BTREE,
KEY `username` (`username`) USING BTREE,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;
