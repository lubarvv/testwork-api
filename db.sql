CREATE TABLE category (
  id int(1) NOT NULL AUTO_INCREMENT,
  name varchar(200) DEFAULT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE product (
  id int(1) NOT NULL AUTO_INCREMENT,
  category_id int(1) NOT NULL,
  name varchar(200) DEFAULT NULL,
  description text,
  cost int(1) DEFAULT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE token (
  id int(1) NOT NULL AUTO_INCREMENT,
  user_id int(1) DEFAULT NULL,
  token varchar(64) DEFAULT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE user (
  id int(1) NOT NULL AUTO_INCREMENT,
  email varchar(100) DEFAULT NULL,
  pass varchar(32) DEFAULT NULL,
  PRIMARY KEY (id)
);
