SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `feedlr` DEFAULT CHARACTER SET utf8 ;
USE `feedlr` ;

-- -----------------------------------------------------
-- Table `feedlr`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `feedlr`.`user` (
  `userId` INT NOT NULL AUTO_INCREMENT ,
  `login` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(64) NOT NULL ,
  `mail` VARCHAR(60) NOT NULL ,
  `created` DATETIME NULL ,
  `admin` TINYINT(1) NULL DEFAULT false ,
  PRIMARY KEY (`userId`) ,
  UNIQUE INDEX `userid_UNIQUE` (`userId` ASC) ,
  UNIQUE INDEX `login_UNIQUE` (`login` ASC) )
ENGINE = InnoDB
COMMENT = 'define a user';


-- -----------------------------------------------------
-- Table `feedlr`.`category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `feedlr`.`category` (
  `categoryId` INT NOT NULL AUTO_INCREMENT ,
  `userId` INT NOT NULL ,
  `caption` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`categoryId`) ,
  UNIQUE INDEX `categoryId_UNIQUE` (`categoryId` ASC) ,
  INDEX `fk_category_user_idx` (`userId` ASC) ,
  CONSTRAINT `fk_category_user`
    FOREIGN KEY (`userId` )
    REFERENCES `feedlr`.`user` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `feedlr`.`feed`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `feedlr`.`feed` (
  `feedId` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `subtitle` VARCHAR(512) NULL ,
  `url` VARCHAR(1024) NOT NULL ,
  `link` VARCHAR(1024) NOT NULL ,
  `updated` DATETIME NULL ,
  `type` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`feedId`) ,
  UNIQUE INDEX `feedId_UNIQUE` (`feedId` ASC) ,
  INDEX `feed_url_idx` (`url` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `feedlr`.`subscription`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `feedlr`.`subscription` (
  `subscriptionId` INT NOT NULL AUTO_INCREMENT ,
  `categoryId` INT NULL ,
  `userId` INT NOT NULL ,
  `feedId` INT NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `priority` TINYINT NOT NULL DEFAULT 10 ,
  PRIMARY KEY (`subscriptionId`) ,
  UNIQUE INDEX `subscriptionId_UNIQUE` (`subscriptionId` ASC) ,
  INDEX `fk_subscription_category1_idx` (`categoryId` ASC) ,
  INDEX `fk_subscription_user1_idx` (`userId` ASC) ,
  INDEX `fk_subscription_feed1_idx` (`feedId` ASC) ,
  CONSTRAINT `fk_subscription_category1`
    FOREIGN KEY (`categoryId` )
    REFERENCES `feedlr`.`category` (`categoryId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_subscription_user1`
    FOREIGN KEY (`userId` )
    REFERENCES `feedlr`.`user` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_subscription_feed1`
    FOREIGN KEY (`feedId` )
    REFERENCES `feedlr`.`feed` (`feedId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `feedlr`.`post`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `feedlr`.`post` (
  `postId` INT NOT NULL AUTO_INCREMENT ,
  `feedId` INT NOT NULL ,
  `externalId` VARCHAR(512) NULL ,
  `title` VARCHAR(512) NULL ,
  `link` VARCHAR(1024) NULL ,
  `mobileLink` VARCHAR(1024) NULL ,
  `updated` DATETIME NULL ,
  `summary` TEXT NULL ,
  `content` TEXT NOT NULL ,
  PRIMARY KEY (`postId`) ,
  UNIQUE INDEX `postId_UNIQUE` (`postId` ASC) ,
  INDEX `fk_post_feed1_idx` (`feedId` ASC) ,
  CONSTRAINT `fk_post_feed1`
    FOREIGN KEY (`feedId` )
    REFERENCES `feedlr`.`feed` (`feedId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `feedlr`.`userPost`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `feedlr`.`userPost` (
  `userPostId` INT NOT NULL AUTO_INCREMENT ,
  `userId` INT NOT NULL ,
  `postId` INT NOT NULL ,
  `subscriptionId` INT NOT NULL ,
  `readed` TINYINT(1) NULL ,
  `favourited` TINYINT(1) NULL ,
  PRIMARY KEY (`userPostId`) ,
  UNIQUE INDEX `userPostId_UNIQUE` (`userPostId` ASC) ,
  INDEX `fk_userPost_user1_idx` (`userId` ASC) ,
  INDEX `fk_userPost_post1_idx` (`postId` ASC) ,
  INDEX `fk_userPost_subscriptionId_idx` (`subscriptionId` ASC) ,
  CONSTRAINT `fk_userPost_user1`
    FOREIGN KEY (`userId` )
    REFERENCES `feedlr`.`user` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_userPost_post1`
    FOREIGN KEY (`postId` )
    REFERENCES `feedlr`.`post` (`postId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_userPost_userFeed1`
    FOREIGN KEY (`subscriptionId` )
    REFERENCES `feedlr`.`subscription` (`subscriptionId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `feedlr`.`favourite`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `feedlr`.`favourite` (
  `favouriteId` INT NOT NULL AUTO_INCREMENT ,
  `externalId` VARCHAR(512) NULL ,
  `title` VARCHAR(512) NULL ,
  `link` VARCHAR(1024) NULL ,
  `mobileLink` VARCHAR(1024) NULL ,
  `updated` DATETIME NULL ,
  `summary` TEXT NULL ,
  `content` TEXT NOT NULL ,
  PRIMARY KEY (`favouriteId`) ,
  UNIQUE INDEX `favouriteId_UNIQUE` (`favouriteId` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `feedlr`.`userFavourite`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `feedlr`.`userFavourite` (
  `userFavouriteId` INT NOT NULL AUTO_INCREMENT ,
  `favouriteId` INT NOT NULL ,
  `userId` INT NOT NULL ,
  PRIMARY KEY (`userFavouriteId`) ,
  UNIQUE INDEX `userFavouriteId_UNIQUE` (`userFavouriteId` ASC) ,
  INDEX `fk_userFavourite_favourite1_idx` (`favouriteId` ASC) ,
  INDEX `fk_userFavourite_user1_idx` (`userId` ASC) ,
  CONSTRAINT `fk_userFavourite_favourite1`
    FOREIGN KEY (`favouriteId` )
    REFERENCES `feedlr`.`favourite` (`favouriteId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_userFavourite_user1`
    FOREIGN KEY (`userId` )
    REFERENCES `feedlr`.`user` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `feedlr`.`log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `feedlr`.`log` (
  `when` DATETIME NOT NULL ,
  `who` VARCHAR(45) NULL ,
  `what` VARCHAR(1024) NOT NULL )
ENGINE = InnoDB;

USE `feedlr` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
