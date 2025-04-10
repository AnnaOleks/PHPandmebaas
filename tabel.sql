CREATE TABLE lehed(
                      id int not null AUTO_INCREMENT PRIMARY KEY,
                      pealkiri varchar(50),
                      sisu text);

INSERT INTO lehed(pealkiri, sisu)
VALUES('Ilm on kulm', 'Aprill on kaes');

ALTER TABLE lehed
    Add kuupaev date;


