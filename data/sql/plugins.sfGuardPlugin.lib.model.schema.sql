
-----------------------------------------------------------------------------
-- sf_guard_group
-----------------------------------------------------------------------------

DROP TABLE "sf_guard_group" CASCADE;


CREATE TABLE "sf_guard_group"
(
	"id" serial  NOT NULL,
	"name" VARCHAR(255)  NOT NULL,
	"description" TEXT,
	PRIMARY KEY ("id"),
	CONSTRAINT "sf_guard_group_U_1" UNIQUE ("name")
);

COMMENT ON TABLE "sf_guard_group" IS '';


SET search_path TO public;
-----------------------------------------------------------------------------
-- sf_guard_permission
-----------------------------------------------------------------------------

DROP TABLE "sf_guard_permission" CASCADE;


CREATE TABLE "sf_guard_permission"
(
	"id" serial  NOT NULL,
	"name" VARCHAR(255)  NOT NULL,
	"description" TEXT,
	PRIMARY KEY ("id"),
	CONSTRAINT "sf_guard_permission_U_1" UNIQUE ("name")
);

COMMENT ON TABLE "sf_guard_permission" IS '';


SET search_path TO public;
-----------------------------------------------------------------------------
-- sf_guard_group_permission
-----------------------------------------------------------------------------

DROP TABLE "sf_guard_group_permission" CASCADE;


CREATE TABLE "sf_guard_group_permission"
(
	"group_id" INTEGER  NOT NULL,
	"permission_id" INTEGER  NOT NULL,
	PRIMARY KEY ("group_id","permission_id")
);

COMMENT ON TABLE "sf_guard_group_permission" IS '';


SET search_path TO public;
-----------------------------------------------------------------------------
-- sf_guard_user
-----------------------------------------------------------------------------

DROP TABLE "sf_guard_user" CASCADE;


CREATE TABLE "sf_guard_user"
(
	"id" serial  NOT NULL,
	"username" VARCHAR(128)  NOT NULL,
	"algorithm" VARCHAR(128) default 'sha1' NOT NULL,
	"salt" VARCHAR(128)  NOT NULL,
	"password" VARCHAR(128)  NOT NULL,
	"created_at" TIMESTAMP,
	"last_login" TIMESTAMP,
	"is_active" BOOLEAN default 't' NOT NULL,
	"is_super_admin" BOOLEAN default 'f' NOT NULL,
	PRIMARY KEY ("id"),
	CONSTRAINT "sf_guard_user_U_1" UNIQUE ("username")
);

COMMENT ON TABLE "sf_guard_user" IS '';


SET search_path TO public;
-----------------------------------------------------------------------------
-- sf_guard_user_permission
-----------------------------------------------------------------------------

DROP TABLE "sf_guard_user_permission" CASCADE;


CREATE TABLE "sf_guard_user_permission"
(
	"user_id" INTEGER  NOT NULL,
	"permission_id" INTEGER  NOT NULL,
	PRIMARY KEY ("user_id","permission_id")
);

COMMENT ON TABLE "sf_guard_user_permission" IS '';


SET search_path TO public;
-----------------------------------------------------------------------------
-- sf_guard_user_group
-----------------------------------------------------------------------------

DROP TABLE "sf_guard_user_group" CASCADE;


CREATE TABLE "sf_guard_user_group"
(
	"user_id" INTEGER  NOT NULL,
	"group_id" INTEGER  NOT NULL,
	PRIMARY KEY ("user_id","group_id")
);

COMMENT ON TABLE "sf_guard_user_group" IS '';


SET search_path TO public;
-----------------------------------------------------------------------------
-- sf_guard_remember_key
-----------------------------------------------------------------------------

DROP TABLE "sf_guard_remember_key" CASCADE;


CREATE TABLE "sf_guard_remember_key"
(
	"user_id" INTEGER  NOT NULL,
	"remember_key" VARCHAR(32),
	"ip_address" VARCHAR(50)  NOT NULL,
	"created_at" TIMESTAMP,
	PRIMARY KEY ("user_id","ip_address")
);

COMMENT ON TABLE "sf_guard_remember_key" IS '';


SET search_path TO public;
ALTER TABLE "sf_guard_group_permission" ADD CONSTRAINT "sf_guard_group_permission_FK_1" FOREIGN KEY ("group_id") REFERENCES "sf_guard_group" ("id") ON DELETE CASCADE;

ALTER TABLE "sf_guard_group_permission" ADD CONSTRAINT "sf_guard_group_permission_FK_2" FOREIGN KEY ("permission_id") REFERENCES "sf_guard_permission" ("id") ON DELETE CASCADE;

ALTER TABLE "sf_guard_user_permission" ADD CONSTRAINT "sf_guard_user_permission_FK_1" FOREIGN KEY ("user_id") REFERENCES "sf_guard_user" ("id") ON DELETE CASCADE;

ALTER TABLE "sf_guard_user_permission" ADD CONSTRAINT "sf_guard_user_permission_FK_2" FOREIGN KEY ("permission_id") REFERENCES "sf_guard_permission" ("id") ON DELETE CASCADE;

ALTER TABLE "sf_guard_user_group" ADD CONSTRAINT "sf_guard_user_group_FK_1" FOREIGN KEY ("user_id") REFERENCES "sf_guard_user" ("id") ON DELETE CASCADE;

ALTER TABLE "sf_guard_user_group" ADD CONSTRAINT "sf_guard_user_group_FK_2" FOREIGN KEY ("group_id") REFERENCES "sf_guard_group" ("id") ON DELETE CASCADE;

ALTER TABLE "sf_guard_remember_key" ADD CONSTRAINT "sf_guard_remember_key_FK_1" FOREIGN KEY ("user_id") REFERENCES "sf_guard_user" ("id") ON DELETE CASCADE;
