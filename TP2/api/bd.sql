-- Adminer 4.8.1 SQLite 3 3.28.0 dump

DROP TABLE IF EXISTS "Cast";
CREATE TABLE "Cast" (
                        "person" integer NOT NULL,
                        "movie" integer NOT NULL,
                        FOREIGN KEY ("person") REFERENCES "Person" ("id"),
                        FOREIGN KEY ("movie") REFERENCES "Movie" ("id")
);

INSERT INTO "Cast" ("person", "movie") VALUES (6,	1);
INSERT INTO "Cast" ("person", "movie") VALUES (7,	1);
INSERT INTO "Cast" ("person", "movie") VALUES (3,	2);
INSERT INTO "Cast" ("person", "movie") VALUES (4,	2);
INSERT INTO "Cast" ("person", "movie") VALUES (6,	2);
INSERT INTO "Cast" ("person", "movie") VALUES (5,	3);

DROP TABLE IF EXISTS "Movie";
CREATE TABLE "Movie" (
                         "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
                         "name" text NULL,
                         "year" integer NULL,
                         "director" integer NULL,
                         FOREIGN KEY (director) REFERENCES Person(id)
);

INSERT INTO "Movie" ("id", "name", "year", "director") VALUES (1,	'Whiplash',	2014,	1);
INSERT INTO "Movie" ("id", "name", "year", "director") VALUES (2,	'La La Land',	2016,	1);
INSERT INTO "Movie" ("id", "name", "year", "director") VALUES (3,	'The Wolf of Wall Street',	2013,	2);

DROP TABLE IF EXISTS "Person";
CREATE TABLE "Person" (
                          "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
                          "name" text NULL,
                          "surname" integer NULL
);

INSERT INTO "Person" ("id", "name", "surname") VALUES (1,	'Chazelle',	'Damien');
INSERT INTO "Person" ("id", "name", "surname") VALUES (2,	'Scorsese',	'Martin');
INSERT INTO "Person" ("id", "name", "surname") VALUES (3,	'Stone',	'Emma');
INSERT INTO "Person" ("id", "name", "surname") VALUES (4,	'Gosling',	'Ryan');
INSERT INTO "Person" ("id", "name", "surname") VALUES (5,	'DiCaprio',	'Leonardo');
INSERT INTO "Person" ("id", "name", "surname") VALUES (6,	'Simmons',	'J.K.');
INSERT INTO "Person" ("id", "name", "surname") VALUES (7,	'Teller',	'Miles');

--