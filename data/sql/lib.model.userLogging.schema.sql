
-----------------------------------------------------------------------------
-- smint_user
-----------------------------------------------------------------------------

DROP TABLE "smint_user" CASCADE;


CREATE TABLE "smint_user"
(
	"id" serial  NOT NULL,
	"sf_guard_user_id" INTEGER,
	"username" VARCHAR(255),
	"name" VARCHAR(255),
	"email" VARCHAR(255),
	"organization" VARCHAR(255),
	"role" VARCHAR(255),
	"industry" VARCHAR(255),
	"validate" VARCHAR(255),
	"created_at" TIMESTAMP,
	"updated_at" TIMESTAMP,
	PRIMARY KEY ("id")
);

COMMENT ON TABLE "smint_user" IS '';


SET search_path TO public;
CREATE INDEX "smint_user_I_1" ON "smint_user" ("email");

CREATE INDEX "smint_user_I_2" ON "smint_user" ("validate");

-----------------------------------------------------------------------------
-- smint_userlogins
-----------------------------------------------------------------------------

DROP TABLE "smint_userlogins" CASCADE;


CREATE TABLE "smint_userlogins"
(
	"id" serial  NOT NULL,
	"smint_user_id" INTEGER,
	"ip" VARCHAR(255),
	"created_at" TIMESTAMP,
	PRIMARY KEY ("id")
);

COMMENT ON TABLE "smint_userlogins" IS '';


SET search_path TO public;
-----------------------------------------------------------------------------
-- smint_comment
-----------------------------------------------------------------------------

DROP TABLE "smint_comment" CASCADE;


CREATE TABLE "smint_comment"
(
	"id" serial  NOT NULL,
	"smint_user_id" INTEGER,
	"content" TEXT,
	"created_at" TIMESTAMP,
	PRIMARY KEY ("id")
);

COMMENT ON TABLE "smint_comment" IS '';


SET search_path TO public;
-----------------------------------------------------------------------------
-- smint_querycomment
-----------------------------------------------------------------------------

DROP TABLE "smint_querycomment" CASCADE;


CREATE TABLE "smint_querycomment"
(
	"id" serial  NOT NULL,
	"smint_user_id" INTEGER,
	"querytrackid" INTEGER,
	"comment" TEXT,
	"rating" FLOAT,
	"featurevectortypeid" INTEGER,
	"distancetypeid" INTEGER,
	"created_at" TIMESTAMP,
	"updated_at" TIMESTAMP,
	PRIMARY KEY ("id")
);

COMMENT ON TABLE "smint_querycomment" IS '';


SET search_path TO public;
-----------------------------------------------------------------------------
-- smint_querycomment_track
-----------------------------------------------------------------------------

DROP TABLE "smint_querycomment_track" CASCADE;


CREATE TABLE "smint_querycomment_track"
(
	"id" serial  NOT NULL,
	"smint_querycomment_id" INTEGER,
	"resultposition" INTEGER,
	"resulttrackid" INTEGER,
	"comment" TEXT,
	"rating" FLOAT,
	"created_at" TIMESTAMP,
	"updated_at" TIMESTAMP,
	PRIMARY KEY ("id")
);

COMMENT ON TABLE "smint_querycomment_track" IS '';


SET search_path TO public;
ALTER TABLE "smint_userlogins" ADD CONSTRAINT "smint_userlogins_FK_1" FOREIGN KEY ("smint_user_id") REFERENCES "smint_user" ("id");

ALTER TABLE "smint_comment" ADD CONSTRAINT "smint_comment_FK_1" FOREIGN KEY ("smint_user_id") REFERENCES "smint_user" ("id");

ALTER TABLE "smint_querycomment" ADD CONSTRAINT "smint_querycomment_FK_1" FOREIGN KEY ("smint_user_id") REFERENCES "smint_user" ("id");

ALTER TABLE "smint_querycomment_track" ADD CONSTRAINT "smint_querycomment_track_FK_1" FOREIGN KEY ("smint_querycomment_id") REFERENCES "smint_querycomment" ("id");
