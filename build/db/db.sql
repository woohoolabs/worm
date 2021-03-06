-- MySQL Script generated by MySQL Workbench
-- Mon Jan 23 22:27:38 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema worm
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `locations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `locations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rooms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rooms` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    `location_id` INT UNSIGNED NOT NULL,
    `floor` TINYINT(3) UNSIGNED NOT NULL,
    `number` SMALLINT(3) UNSIGNED NOT NULL,
    `capacity` SMALLINT(3) UNSIGNED NOT NULL,
    `description` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_rooms_location_id_idx` (`location_id` ASC),
    CONSTRAINT `fk_rooms_location_id`
    FOREIGN KEY (`location_id`)
    REFERENCES `locations` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `courses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `courses` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    `description` TEXT NOT NULL,
    `credit` SMALLINT(1) UNSIGNED NOT NULL,
    `language` VARCHAR(4) NOT NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teachers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teachers` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(16) NOT NULL,
    `first_name` VARCHAR(64) NOT NULL,
    `last_name` VARCHAR(64) NOT NULL,
    `gender` ENUM('MALE', 'FEMALE', 'UNKNOWN') NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `classes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `classes` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` INT UNSIGNED NOT NULL,
    `room_id` INT UNSIGNED NOT NULL,
    `teacher_id` INT UNSIGNED NOT NULL,
    `datetime` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_classes_course_id_idx` (`course_id` ASC),
    INDEX `fk_classes_room_id_idx` (`room_id` ASC),
    INDEX `fk_classes_teacher_id_idx` (`teacher_id` ASC),
    CONSTRAINT `fk_classes_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `courses` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_classes_room_id`
    FOREIGN KEY (`room_id`)
    REFERENCES `rooms` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_classes_teacher_id`
    FOREIGN KEY (`teacher_id`)
    REFERENCES `teachers` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `students`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `students` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(64) NOT NULL,
    `last_name` VARCHAR(64) NOT NULL,
    `birthday` DATE NULL,
    `gender` ENUM('MALE', 'FEMALE', 'UNKNOWN') NOT NULL,
    `introduction` TEXT NOT NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `classes_students`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `classes_students` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `class_id` INT UNSIGNED NOT NULL,
    `student_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_classes_students_class_id_idx` (`class_id` ASC),
    INDEX `fk_classes_students_student_id_idx` (`student_id` ASC),
    CONSTRAINT `fk_classes_students_class_id`
    FOREIGN KEY (`class_id`)
    REFERENCES `classes` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT `fk_classes_students_student_id`
    FOREIGN KEY (`student_id`)
    REFERENCES `students` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE)
    ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `locations`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `locations` (`id`, `name`, `address`) VALUES (1, 'Campus 1', '1234 Budapest, Campus u. 123');
INSERT INTO `locations` (`id`, `name`, `address`) VALUES (2, 'Campus 2', '1234 Berlin, Campus strasse 456');
INSERT INTO `locations` (`id`, `name`, `address`) VALUES (3, 'Campus 3', '1234 Paris, rue de Campus 789');

COMMIT;


-- -----------------------------------------------------
-- Data for table `rooms`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `rooms` (`id`, `name`, `location_id`, `floor`, `number`, `capacity`, `description`) VALUES (1, 'Lab 1', 1, 1, 111, 25, 'Lab for 25 people');
INSERT INTO `rooms` (`id`, `name`, `location_id`, `floor`, `number`, `capacity`, `description`) VALUES (2, 'Lab 2', 1, 2, 121, 50, 'Lab for 50 people');
INSERT INTO `rooms` (`id`, `name`, `location_id`, `floor`, `number`, `capacity`, `description`) VALUES (3, 'Room 1', 2, 1, 211, 100, 'Room for lectures');
INSERT INTO `rooms` (`id`, `name`, `location_id`, `floor`, `number`, `capacity`, `description`) VALUES (4, 'Room 2', 2, 1, 212, 250, 'Room for lectures');
INSERT INTO `rooms` (`id`, `name`, `location_id`, `floor`, `number`, `capacity`, `description`) VALUES (5, 'Room 3', 2, 2, 221, 10, 'Lab for 10 people');
INSERT INTO `rooms` (`id`, `name`, `location_id`, `floor`, `number`, `capacity`, `description`) VALUES (6, 'Auditorium Maximum', 2, 3, 231, 500, 'Auditorium Maximum');
INSERT INTO `rooms` (`id`, `name`, `location_id`, `floor`, `number`, `capacity`, `description`) VALUES (7, 'Lab 1', 3, 1, 311, 25, 'Lab for 25 people');
INSERT INTO `rooms` (`id`, `name`, `location_id`, `floor`, `number`, `capacity`, `description`) VALUES (8, 'Room 1', 3, 1, 312, 100, 'Room for 100 people');

COMMIT;


-- -----------------------------------------------------
-- Data for table `courses`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `courses` (`id`, `name`, `description`, `credit`, `language`) VALUES (1, 'Operating Systems Architecture', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 5, 'en');
INSERT INTO `courses` (`id`, `name`, `description`, `credit`, `language`) VALUES (2, 'Service-Oriented Architectures', 'Vivamus erat diam, viverra nec dui non, blandit finibus arcu.', 3, 'en');
INSERT INTO `courses` (`id`, `name`, `description`, `credit`, `language`) VALUES (3, 'Advanced Database Systems', 'Quisque vehicula mollis justo vitae porta. Suspendisse molestie condimentum ante, in gravida arcu dignissim ut. Vestibulum pulvinar justo eu euismod vestibulum. Fusce rhoncus risus nec risus aliquet ultricies. Maecenas dapibus leo sem, nec consequat eros bibendum vitae. Etiam ultrices ut sapien id rutrum. Fusce luctus libero eu diam fringilla, vel dapibus velit molestie. Donec fermentum leo hendrerit odio finibus rhoncus. Donec ut finibus arcu, eu molestie nulla. Nulla tristique tristique lacinia. Maecenas mattis purus dolor, vel elementum nisl pretium vitae. Praesent congue lacus vel justo luctus ullamcorper.', 2, 'en');
INSERT INTO `courses` (`id`, `name`, `description`, `credit`, `language`) VALUES (4, 'Machine Learning', 'Aenean malesuada ipsum in nunc condimentum, vitae malesuada erat dictum. Pellentesque eu velit vel lectus egestas mattis hendrerit et ipsum.', 2, 'en');
INSERT INTO `courses` (`id`, `name`, `description`, `credit`, `language`) VALUES (5, 'Haladó Adatbázisrendszerek', 'Lórum ipse természetesen ajogat bővít a nyere számára. „(3) a szonya alása a börvér száz süstője.” Gúnyosság sütétének (5) randulusa halásába a lens levég cseredik: Burka tetern gyülevet esetén a börvér száz süstője. „Márókára a fegyencés mortalós grologhoz be kell fengnie.', 2, 'hu');
INSERT INTO `courses` (`id`, `name`, `description`, `credit`, `language`) VALUES (6, 'Operációs Rendszerek Architektúrája', 'A nemző pros nakodás például a hegyező virkony csülékét ferődi. Holnap is nyitva váskodnak örpes zildalan ródások, mert a talan frukkó dendós mazonnak kuncorgat.', 5, 'hu');
INSERT INTO `courses` (`id`, `name`, `description`, `credit`, `language`) VALUES (7, 'Bases de Données Advancées', 'Sed et porttitor augue, consectetur hendrerit metus. Praesent ex lorem, tristique eu tortor sed, maximus hendrerit lectus. Vestibulum porta luctus nisl.', 2, 'fr');
INSERT INTO `courses` (`id`, `name`, `description`, `credit`, `language`) VALUES (8, 'Maschinelles lernen', 'Duis quis diam viverra, ultricies ante ut, luctus urna. Mauris id ultricies massa. Suspendisse posuere quis arcu a cursus. Proin ut metus finibus, tempor quam nec, semper turpis.', 4, 'de');
INSERT INTO `courses` (`id`, `name`, `description`, `credit`, `language`) VALUES (9, 'Erweiterte Datenbanksysteme', 'Duis quis diam viverra, ultricies ante ut, luctus urna. Mauris id ultricies massa. Suspendisse posuere quis arcu a cursus. Proin ut metus finibus, tempor quam nec, semper turpis.', 2, 'de');

COMMIT;


-- -----------------------------------------------------
-- Data for table `teachers`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `teachers` (`id`, `title`, `first_name`, `last_name`, `gender`) VALUES (1, 'prof', 'István', 'Kiss', 'MALE');
INSERT INTO `teachers` (`id`, `title`, `first_name`, `last_name`, `gender`) VALUES (2, 'dr', 'János', 'Nagy', 'MALE');
INSERT INTO `teachers` (`id`, `title`, `first_name`, `last_name`, `gender`) VALUES (3, 'dr', 'Ágnes', 'Kovács', 'FEMALE');
INSERT INTO `teachers` (`id`, `title`, `first_name`, `last_name`, `gender`) VALUES (4, 'habil', 'Franz', 'Ferdinand', 'UNKNOWN');
INSERT INTO `teachers` (`id`, `title`, `first_name`, `last_name`, `gender`) VALUES (5, ' ', 'Albert', 'Einstandt', 'MALE');
INSERT INTO `teachers` (`id`, `title`, `first_name`, `last_name`, `gender`) VALUES (6, 'prof', 'Edith', 'Dubois', 'FEMALE');
INSERT INTO `teachers` (`id`, `title`, `first_name`, `last_name`, `gender`) VALUES (7, 'phd', 'Luc', 'Henry', 'MALE');

COMMIT;


-- -----------------------------------------------------
-- Data for table `classes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (1, 1, 1, 1, '2017-02-02 10:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (2, 1, 1, 1, '2017-02-09 10:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (3, 2, 2, 2, '2017-02-02 10:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (4, 2, 3, 3, '2017-02-02 10:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (5, 2, 2, 2, '2017-02-09 10:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (6, 2, 3, 3, '2017-02-09 10:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (7, 3, 6, 4, '2017-02-10 12:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (8, 3, 6, 4, '2017-02-17 12:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (9, 3, 6, 4, '2017-02-24 12:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (10, 4, 5, 5, '2017-02-02 14:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (11, 4, 6, 3, '2017-02-02 18:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (12, 4, 5, 7, '2017-02-04 08:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (13, 4, 5, 5, '2017-02-09 14:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (14, 4, 6, 3, '2017-02-09 18:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (15, 4, 5, 7, '2017-02-11 08:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (16, 5, 7, 6, '2017-02-06 10:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (17, 5, 7, 6, '2017-02-13 10:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (18, 5, 8, 6, '2017-02-21 10:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (19, 6, 4, 2, '2017-02-01 14:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (20, 6, 4, 3, '2017-02-01 14:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (21, 6, 4, 2, '2017-02-08 14:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (22, 6, 4, 3, '2017-02-08 14:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (23, 7, 2, 5, '2017-02-03 16:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (24, 7, 2, 5, '2017-02-10 16:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (25, 7, 2, 5, '2017-02-17 16:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (26, 7, 2, 4, '2017-02-24 16:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (27, 8, 1, 7, '2017-02-02 12:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (28, 8, 1, 7, '2017-02-09 12:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (29, 8, 1, 7, '2017-02-16 12:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (30, 9, 2, 5, '2017-02-07 08:00:00');
INSERT INTO `classes` (`id`, `course_id`, `room_id`, `teacher_id`, `datetime`) VALUES (31, 9, 2, 5, '2017-02-14 08:00:00');

COMMIT;


-- -----------------------------------------------------
-- Data for table `students`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `students` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `introduction`) VALUES (1, 'Krisztina', 'Kovács', NULL, 'FEMALE', 'Lórum ipse feltehetőleg nyúlós, de parás, hogy nem a süke rázár van beállítva. Ezért a handóság verpert, renségre metleni, a zselegástól a nyenyenyig gatos hoska büdörtömöt seblyézik ki. Ennek fortóságai között karkázta a hértes pnóság nyálátossá csipesét a jesedtek regonyára, a szintén igesztő pechán kafájós nyálátos pondékokat, illetve a börcs gyenő fejős dolárt, amelynél fokozottan hintálják pucizásba, hogy a lazások fegyes vonaka legyen a kelemletény radó térnök. Ditáson sajorválás, a pelen fátott és kromás dezenség (sametum) gardéja a hanyát kiselelte, azt társadalmilag rendkívül játszerűnek tudozta. „a facék olyan szerbetes, hogy minden szetés, amely kapácsot köveskezhetik, seke lehet” - dintett rá, hozzátéve, hogy a szidás kezőjében nem a hüven niség a riatos fortóság. A gardé szerint a bartszerű szőnyesekbe való kelemletényre, a vodásra kell vező volót kodnia. Lekedte a plusokon tényes cátros szatikály fehetét, a büdörtöm buggyos körcsögét 20-30 szalusra értetette. Sekének tudozta azt is, hogy a römnyi szövényök cincsre rejlítsenek.');
INSERT INTO `students` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `introduction`) VALUES (2, 'Tamás', 'Varga', '1995-06-20', 'MALE', '');
INSERT INTO `students` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `introduction`) VALUES (3, 'Olga', 'Szitás', '1996-11-08', 'FEMALE', 'Lórum ipse feltehetőleg nyúlós, de parás, hogy nem a süke rázár van beállítva. Ezért a handóság verpert, renségre metleni, a zselegástól a nyenyenyig gatos hoska büdörtömöt seblyézik ki.');
INSERT INTO `students` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `introduction`) VALUES (4, 'Luca', 'Asztalos', '1995-02-19', 'FEMALE', '');
INSERT INTO `students` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `introduction`) VALUES (5, 'Péter', 'Szabó', '1997-01-12', 'MALE', 'Amikor kulany gordolta, hogy fengősben van raca, akkor dagodta először szabolyot. Második szabélyuk krényével pejk szakodta roztosságai előtt tüstését, és így az ájuhár előtt is szabdakodt pejk mékéje. Pejk aztán mongodta szabolyát, kulanyot és egész keretsését, összesen szetlem mozást. ');
INSERT INTO `students` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `introduction`) VALUES (6, 'Stephen', 'Walking', '1994-04-02', 'UNKNOWN', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus erat diam, viverra nec dui non, blandit finibus arcu. Nullam quam augue, ultricies sed dui vel, pellentesque aliquam neque. Sed sem urna, fermentum eget mi eu, pretium tempor dolor. Praesent maximus vel arcu at pulvinar. Nam eget elit nibh. Etiam non mi et nulla varius tempor ut id augue.');
INSERT INTO `students` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `introduction`) VALUES (7, 'Robert', 'Dagobert', NULL, 'MALE', '');
INSERT INTO `students` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `introduction`) VALUES (8, 'Barack', 'Trump', '1999-06-06', 'MALE', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus erat diam, viverra nec dui non, blandit finibus arcu. Nullam quam augue, ultricies sed dui vel, pellentesque aliquam neque. Sed sem urna, fermentum eget mi eu, pretium tempor dolor. Praesent maximus vel arcu at pulvinar. Nam eget elit nibh. Etiam non mi et nulla varius tempor ut id augue. Aenean quis velit mollis, malesuada nisl et, varius mi. Morbi interdum dignissim euismod. Suspendisse interdum nunc massa, eu tincidunt orci vestibulum ac. In convallis felis sapien, vitae laoreet ante interdum quis. Maecenas et luctus ex, eu tincidunt nibh. Ut imperdiet faucibus sodales. Nunc eget sapien felis.\n\nQuisque vehicula mollis justo vitae porta. Suspendisse molestie condimentum ante, in gravida arcu dignissim ut. Vestibulum pulvinar justo eu euismod vestibulum. Fusce rhoncus risus nec risus aliquet ultricies. Maecenas dapibus leo sem, nec consequat eros bibendum vitae. Etiam ultrices ut sapien id rutrum. Fusce luctus libero eu diam fringilla, vel dapibus velit molestie. Donec fermentum leo hendrerit odio finibus rhoncus. Donec ut finibus arcu, eu molestie nulla. Nulla tristique tristique lacinia. Maecenas mattis purus dolor, vel elementum nisl pretium vitae. Praesent congue lacus vel justo luctus ullamcorper.');
INSERT INTO `students` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `introduction`) VALUES (9, 'Nino', 'Fillmer', '1994-10-28', 'MALE', '');
INSERT INTO `students` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `introduction`) VALUES (10, 'Sina', 'Beckenbauer', NULL, 'FEMALE', 'Aenean malesuada ipsum in nunc condimentum, vitae malesuada erat dictum. Pellentesque eu velit vel lectus egestas mattis hendrerit et ipsum. Aliquam porta maximus faucibus.');

COMMIT;


-- -----------------------------------------------------
-- Data for table `classes_students`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (1, 1, 1);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (2, 1, 2);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (3, 1, 3);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (4, 1, 4);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (5, 1, 5);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (6, 2, 1);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (7, 2, 2);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (8, 2, 3);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (9, 2, 4);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (10, 2, 5);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (11, 3, 6);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (12, 3, 7);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (13, 3, 8);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (14, 4, 9);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (15, 4, 10);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (16, 5, 6);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (17, 5, 7);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (18, 5, 8);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (19, 6, 9);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (20, 6, 10);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (21, 7, 1);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (22, 7, 3);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (23, 7, 5);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (24, 7, 7);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (25, 8, 1);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (26, 8, 3);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (27, 8, 5);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (28, 8, 7);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (29, 9, 1);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (30, 9, 3);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (31, 9, 5);
INSERT INTO `classes_students` (`id`, `class_id`, `student_id`) VALUES (32, 9, 7);

COMMIT;

