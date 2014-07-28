CREATE TABLE category (
  id int(1) NOT NULL AUTO_INCREMENT,
  name varchar(200) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE product (
  id int(1) NOT NULL AUTO_INCREMENT,
  category_id int(1) NOT NULL,
  name varchar(200) DEFAULT NULL,
  description text,
  cost int(1) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
