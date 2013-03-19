SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `requisitos` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `requisitos` ;

-- -----------------------------------------------------
-- Table `requisitos`.`usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `requisitos`.`usuario` ;

CREATE  TABLE IF NOT EXISTS `requisitos`.`usuario` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(80) NOT NULL ,
  `email` VARCHAR(80) NOT NULL ,
  `senha` VARCHAR(32) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `requisitos`.`cliente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `requisitos`.`cliente` ;

CREATE  TABLE IF NOT EXISTS `requisitos`.`cliente` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(200) NOT NULL ,
  `id_usuario` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cliente_usuario1_idx` (`id_usuario` ASC) ,
  CONSTRAINT `fk_cliente_usuario1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `requisitos`.`usuario` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `requisitos`.`projeto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `requisitos`.`projeto` ;

CREATE  TABLE IF NOT EXISTS `requisitos`.`projeto` (
  `id` INT NOT NULL ,
  `nome` VARCHAR(255) NOT NULL ,
  `descricao` TEXT NULL ,
  `data_inicio` DATE NOT NULL ,
  `data_final` DATE NULL ,
  `id_usuario` INT NOT NULL ,
  `id_cliente` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_projeto_usuario1_idx` (`id_usuario` ASC) ,
  INDEX `fk_projeto_cliente1_idx` (`id_cliente` ASC) ,
  CONSTRAINT `fk_projeto_usuario1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `requisitos`.`usuario` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_projeto_cliente1`
    FOREIGN KEY (`id_cliente` )
    REFERENCES `requisitos`.`cliente` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `requisitos`.`cliente_contato`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `requisitos`.`cliente_contato` ;

CREATE  TABLE IF NOT EXISTS `requisitos`.`cliente_contato` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(80) NOT NULL ,
  `email` VARCHAR(80) NULL ,
  `funcao` VARCHAR(80) NULL ,
  `observacao` TEXT NULL ,
  `id_cliente` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cliente_contato_cliente1_idx` (`id_cliente` ASC) ,
  CONSTRAINT `fk_cliente_contato_cliente1`
    FOREIGN KEY (`id_cliente` )
    REFERENCES `requisitos`.`cliente` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `requisitos`.`tipo_requisito`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `requisitos`.`tipo_requisito` ;

CREATE  TABLE IF NOT EXISTS `requisitos`.`tipo_requisito` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(255) NOT NULL ,
  `descricao` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `requisitos`.`requisito`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `requisitos`.`requisito` ;

CREATE  TABLE IF NOT EXISTS `requisitos`.`requisito` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `codigo` VARCHAR(50) NOT NULL ,
  `nome` VARCHAR(255) NOT NULL ,
  `prioridade` ENUM('baixa','media','alta') NOT NULL ,
  `estabilidade` ENUM('baixa','media','alta') NOT NULL ,
  `impacto_arquitetura` ENUM('baixa','media','alta') NOT NULL ,
  `descricao` TEXT NULL ,
  `data_inicio` DATE NOT NULL ,
  `data_final` DATE NULL ,
  `id_tipo_requisito` INT NOT NULL ,
  `id_projeto` INT NOT NULL ,
  `id_cliente_contato` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_requisito_projeto1_idx` (`id_projeto` ASC) ,
  INDEX `fk_requisito_cliente_contato1_idx` (`id_cliente_contato` ASC) ,
  INDEX `fk_requisito_tipo_requisito1_idx` (`id_tipo_requisito` ASC) ,
  CONSTRAINT `fk_requisito_projeto1`
    FOREIGN KEY (`id_projeto` )
    REFERENCES `requisitos`.`projeto` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_requisito_cliente_contato1`
    FOREIGN KEY (`id_cliente_contato` )
    REFERENCES `requisitos`.`cliente_contato` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_requisito_tipo_requisito1`
    FOREIGN KEY (`id_tipo_requisito` )
    REFERENCES `requisitos`.`tipo_requisito` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `requisitos`.`requisito_pai`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `requisitos`.`requisito_pai` ;

CREATE  TABLE IF NOT EXISTS `requisitos`.`requisito_pai` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `requisito_pai` INT NOT NULL ,
  `requisito_filho` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_requisito_pai_requisito1_idx` (`requisito_pai` ASC) ,
  INDEX `fk_requisito_pai_requisito2_idx` (`requisito_filho` ASC) ,
  CONSTRAINT `fk_requisito_pai_requisito1`
    FOREIGN KEY (`requisito_pai` )
    REFERENCES `requisitos`.`requisito` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_requisito_pai_requisito2`
    FOREIGN KEY (`requisito_filho` )
    REFERENCES `requisitos`.`requisito` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `requisitos` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
