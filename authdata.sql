CREATE DATABASE IF NOT EXISTS `authdata`;
USE `authdata`;

CREATE TABLE IF NOT EXISTS `users` (
	`id` int AUTO_INCREMENT NOT NULL UNIQUE,
	`username` varchar(255) NOT NULL UNIQUE,
	`password` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `data` (
	`id` int AUTO_INCREMENT NOT NULL UNIQUE,
	`usernameId` int NOT NULL,
	`serviceName` varchar(255) NOT NULL,
	`serviceUsername` varchar(255) NOT NULL,
	`servicePassword` varchar(255) NOT NULL,
	`serviceInfo` text,
	PRIMARY KEY (`id`)
);


ALTER TABLE `data` ADD CONSTRAINT `data_fk1` FOREIGN KEY (`usernameId`) REFERENCES `users`(`id`);