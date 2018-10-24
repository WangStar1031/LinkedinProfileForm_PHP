# Host: localhost  (Version 5.5.5-10.1.26-MariaDB)
# Date: 2018-10-23 18:28:08
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "education"
#

DROP TABLE IF EXISTS `education`;
CREATE TABLE `education` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProfileId` int(11) DEFAULT NULL,
  `SchoolName` varchar(255) DEFAULT NULL,
  `DegreeName` varchar(255) DEFAULT NULL,
  `AreaName` varchar(255) DEFAULT NULL,
  `StartYear` int(11) DEFAULT NULL,
  `EndYear` int(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# Structure for table "employment"
#

DROP TABLE IF EXISTS `employment`;
CREATE TABLE `employment` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProfileId` int(11) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `RoleTitle` varchar(255) DEFAULT NULL,
  `FromDate` date DEFAULT NULL,
  `ToDate` date DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# Structure for table "industry"
#

DROP TABLE IF EXISTS `industry`;
CREATE TABLE `industry` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `IndName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# Structure for table "profiles"
#

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Prefix` varchar(255) DEFAULT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `PhoneNumber` varchar(255) DEFAULT NULL,
  `IndustryId` int(11) DEFAULT NULL,
  `JobFunction` varchar(255) DEFAULT NULL,
  `ProfileUrl` varchar(255) DEFAULT NULL,
  `ImageUrl` varchar(255) DEFAULT NULL,
  `ProfileTitle` varchar(255) DEFAULT NULL,
  `Biography` text,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# Structure for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `SureName` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
