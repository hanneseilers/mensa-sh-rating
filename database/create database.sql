SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `mensa_sh_rating` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mensa_sh_rating` ;

-- -----------------------------------------------------
-- Table `mensa_sh_rating`.`locations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mensa_sh_rating`.`locations` ;

CREATE TABLE IF NOT EXISTS `mensa_sh_rating`.`locations` (
  `idlocations` INT NOT NULL AUTO_INCREMENT,
  `location` TEXT NULL,
  PRIMARY KEY (`idlocations`),
  UNIQUE INDEX `idlocations_UNIQUE` (`idlocations` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mensa_sh_rating`.`mensen`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mensa_sh_rating`.`mensen` ;

CREATE TABLE IF NOT EXISTS `mensa_sh_rating`.`mensen` (
  `idmensen` INT NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `locations_idlocations` INT NOT NULL,
  PRIMARY KEY (`idmensen`, `locations_idlocations`),
  UNIQUE INDEX `idmensen_UNIQUE` (`idmensen` ASC),
  INDEX `fk_mensen_locations_idx` (`locations_idlocations` ASC),
  CONSTRAINT `fk_mensen_locations`
    FOREIGN KEY (`locations_idlocations`)
    REFERENCES `mensa_sh_rating`.`locations` (`idlocations`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mensa_sh_rating`.`meals`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mensa_sh_rating`.`meals` ;

CREATE TABLE IF NOT EXISTS `mensa_sh_rating`.`meals` (
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
    REFERENCES `mensa_sh_rating`.`mensen` (`idmensen` , `locations_idlocations`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mensa_sh_rating`.`ratings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mensa_sh_rating`.`ratings` ;

CREATE TABLE IF NOT EXISTS `mensa_sh_rating`.`ratings` (
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
    REFERENCES `mensa_sh_rating`.`meals` (`idmeals`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
