-- Adminer 4.8.1 SQLite 3 3.28.0 dump
DROP TABLE IF EXISTS "Preparation";
DROP TABLE IF EXISTS "Ingredient";
DROP TABLE IF EXISTS "Recipe";

CREATE TABLE "Ingredient" (
                              "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
                              "name" text NULL
);

DROP TABLE IF EXISTS "Recipe";
CREATE TABLE "Recipe" (
                          "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
                          "name" text NULL
);

CREATE TABLE "Preparation" (
                               "ingredient" integer NOT NULL,
                               "recipe" integer NOT NULL,
                               "quantity" integer NOT NULL,
                               FOREIGN KEY ("ingredient") REFERENCES "Ingredient" ("id"),
                               FOREIGN KEY ("recipe") REFERENCES "Recipe" ("id")
);

INSERT INTO "Ingredient" ("id", "name") VALUES (1, 'Egg');
INSERT INTO "Ingredient" ("id", "name") VALUES (2, 'Dose of salt');
INSERT INTO "Ingredient" ("id", "name") VALUES (3, 'Dose of pepper');
INSERT INTO "Ingredient" ("id", "name") VALUES (4, 'Sugar');
INSERT INTO "Ingredient" ("id", "name") VALUES (5, 'Flour');
INSERT INTO "Ingredient" ("id", "name") VALUES (6, 'Chocolate slab');

INSERT INTO "Recipe" ("id", "name") VALUES (1, 'Omelette');
INSERT INTO "Recipe" ("id", "name") VALUES (2, 'Chocolate cake');

INSERT INTO "Preparation" ("ingredient", "recipe", "quantity") VALUES (1, 1, 4);
INSERT INTO "Preparation" ("ingredient", "recipe", "quantity") VALUES (2, 1, 2);
INSERT INTO "Preparation" ("ingredient", "recipe", "quantity") VALUES (3, 1, 1);
INSERT INTO "Preparation" ("ingredient", "recipe", "quantity") VALUES (1, 2, 3);
INSERT INTO "Preparation" ("ingredient", "recipe", "quantity") VALUES (2, 2, 1);
INSERT INTO "Preparation" ("ingredient", "recipe", "quantity") VALUES (4, 2, 200);
INSERT INTO "Preparation" ("ingredient", "recipe", "quantity") VALUES (5, 2, 300);
INSERT INTO "Preparation" ("ingredient", "recipe", "quantity") VALUES (6, 2, 1);

--