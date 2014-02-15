SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `35110m24661_2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `35110m24661_2` ;

-- -----------------------------------------------------
-- Table `35110m24661_2`.`locations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `35110m24661_2`.`locations` ;

CREATE TABLE IF NOT EXISTS `35110m24661_2`.`locations` (
  `idlocations` INT NOT NULL AUTO_INCREMENT,
  `location` TEXT NULL,
  PRIMARY KEY (`idlocations`),
  UNIQUE INDEX `idlocations_UNIQUE` (`idlocations` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `35110m24661_2`.`mensen`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `35110m24661_2`.`mensen` ;

CREATE TABLE IF NOT EXISTS `35110m24661_2`.`mensen` (
  `idmensen` INT NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `locations_idlocations` INT NOT NULL,
  PRIMARY KEY (`idmensen`, `locations_idlocations`),
  UNIQUE INDEX `idmensen_UNIQUE` (`idmensen` ASC),
  INDEX `fk_mensen_locations_idx` (`locations_idlocations` ASC),
  CONSTRAINT `fk_mensen_locations`
    FOREIGN KEY (`locations_idlocations`)
    REFERENCES `35110m24661_2`.`locations` (`idlocations`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `35110m24661_2`.`meals`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `35110m24661_2`.`meals` ;

CREATE TABLE IF NOT EXISTS `35110m24661_2`.`meals` (
  `idmeals` INT NOT NULL AUTO_INCREMENT,
  `name` LONGTEXT NOT NULL,
  `pig` TINYINT(1) NOT NULL,
  `cow` TINYINT(1) NOT NULL,
  `vegetarian` TINYINT(1) NOT NULL,
  `vegan` TINYINT(1) NOT NULL,
  `alc` TINYINT(1) NULL,
  `mensen_idmensen` INT NOT NULL,
  `mensen_locations_idlocations` INT NOT NULL,
  PRIMARY KEY (`idmeals`, `mensen_idmensen`, `mensen_locations_idlocations`),
  UNIQUE INDEX `idratings_UNIQUE` (`idmeals` ASC),
  INDEX `fk_meals_mensen1_idx` (`mensen_idmensen` ASC, `mensen_locations_idlocations` ASC),
  CONSTRAINT `fk_meals_mensen1`
    FOREIGN KEY (`mensen_idmensen` , `mensen_locations_idlocations`)
    REFERENCES `35110m24661_2`.`mensen` (`idmensen` , `locations_idlocations`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `35110m24661_2`.`ratings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `35110m24661_2`.`ratings` ;

CREATE TABLE IF NOT EXISTS `35110m24661_2`.`ratings` (
  `idratings` INT NOT NULL AUTO_INCREMENT,
  `rating` INT NOT NULL,
  `date` TIMESTAMP NOT NULL,
  `hash` LONGTEXT NOT NULL,
  `comment` LONGTEXT NULL,
  `meals_idmeals` INT NOT NULL,
  PRIMARY KEY (`idratings`, `meals_idmeals`),
  UNIQUE INDEX `idratings_UNIQUE` (`idratings` ASC),
  INDEX `fk_ratings_meals1_idx` (`meals_idmeals` ASC),
  CONSTRAINT `fk_ratings_meals1`
    FOREIGN KEY (`meals_idmeals`)
    REFERENCES `35110m24661_2`.`meals` (`idmeals`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
