CREATE TABLE `user`
(
  id int AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  enable BIT NOT NULL
)ENGINE = INNODB;

CREATE TABLE setting
(
  user_id INTEGER,
  logo_path VARCHAR(100) NULL,
  parameters MEDIUMTEXT NULL,
  PRIMARY KEY(user_id),
  FOREIGN KEY (user_id) REFERENCES `user`(id)
)ENGINE = INNODB;
