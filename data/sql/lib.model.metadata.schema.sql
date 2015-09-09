
-----------------------------------------------------------------------------
-- filedesc
-----------------------------------------------------------------------------

DROP TABLE "filedesc" CASCADE;


CREATE TABLE "filedesc"
(
	"tracknr" VARCHAR(255)  NOT NULL,
	"performers" TEXT,
	"title" TEXT,
	"version" TEXT,
	"genre" TEXT,
	"subgenre" TEXT,
	"tempo" TEXT,
	"bpm" INTEGER,
	"leadvocalgender" TEXT,
	"instruments" TEXT,
	"moods" TEXT,
	"situations" TEXT,
	"genre_sm" TEXT,
	"genre_sm2" TEXT,
	PRIMARY KEY ("tracknr")
);

COMMENT ON TABLE "filedesc" IS '';


SET search_path TO public;