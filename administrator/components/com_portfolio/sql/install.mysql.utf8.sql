
-- -----------------------------------------------------
-- Table `#__portfolio_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__portfolio_category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `image` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__portfolio_category_lang`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__portfolio_category_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `lang_id` INT UNSIGNED NOT NULL,
  `name` TEXT NOT NULL,
  `alias` TEXT NOT NULL,
  `meta_description` TEXT NULL,
  `meta_tags` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `clang_lang_id_idx` (`lang_id` ASC),
  INDEX `clang_category_id_idx` (`category_id` ASC),
  CONSTRAINT `clang_lang_id`
    FOREIGN KEY (`lang_id`)
    REFERENCES `#__languages` (`lang_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `clang_category_id`
    FOREIGN KEY (`category_id`)
    REFERENCES `#__portfolio_category` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__portfolio_work`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__portfolio_work` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__portfolio_work_lang`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__portfolio_work_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `work_id` INT NOT NULL,
  `lang_id` INT UNSIGNED NOT NULL,
  `name` TEXT NOT NULL,
  `alias` TEXT NOT NULL,
  `description` TEXT NULL,
  `specs` TEXT NULL,
  `meta_description` TEXT NULL,
  `meta_tags` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `plang_product_id_idx` (`work_id` ASC),
  INDEX `plang_lang_id_idx` (`lang_id` ASC),
  CONSTRAINT `plang_product_id`
    FOREIGN KEY (`work_id`)
    REFERENCES `#__portfolio_work` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `plang_lang_id`
    FOREIGN KEY (`lang_id`)
    REFERENCES `#__languages` (`lang_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__portfolio_work_images`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__portfolio_work_images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `work_id` INT NOT NULL,
  `image` TEXT NULL,
  `thumb` TEXT NULL,
  `main` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `pimg_product_id_idx` (`work_id` ASC),
  CONSTRAINT `pimg_product_id`
    FOREIGN KEY (`work_id`)
    REFERENCES `#__portfolio_work` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__portfolio_work_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__portfolio_work_category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `work_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `pimg_product_id_idx` (`work_id` ASC),
  INDEX `pcat_category_id_idx` (`category_id` ASC),
  CONSTRAINT `pcat_product_id`
    FOREIGN KEY (`work_id`)
    REFERENCES `#__portfolio_work` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `pcat_category_id`
    FOREIGN KEY (`category_id`)
    REFERENCES `#__portfolio_category` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;