CREATE TABLE IF NOT EXISTS "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);
CREATE TABLE IF NOT EXISTS "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "registered_at" datetime, "created_at" datetime, "updated_at" datetime);
CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"));
CREATE TABLE IF NOT EXISTS "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs" ("uuid");
CREATE TABLE IF NOT EXISTS "personal_access_tokens" ("id" integer primary key autoincrement not null, "tokenable_type" varchar not null, "tokenable_id" integer not null, "name" varchar not null, "token" varchar not null, "abilities" text, "last_used_at" datetime, "expires_at" datetime, "created_at" datetime, "updated_at" datetime);
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens" ("tokenable_type", "tokenable_id");
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens" ("token");
CREATE TABLE IF NOT EXISTS "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null);
CREATE INDEX "jobs_queue_index" on "jobs" ("queue");
CREATE TABLE IF NOT EXISTS "models" ("id" integer primary key autoincrement not null, "name" varchar not null, "date" datetime not null, "location" varchar not null, "lat" numeric not null, "long" numeric not null, "created_at" datetime, "updated_at" datetime, "user_id" integer not null);
CREATE UNIQUE INDEX "models_user_id_name_unique" on "models" ("user_id", "name");
CREATE TABLE IF NOT EXISTS "auth_events" ("id" integer primary key autoincrement not null, "user_id" integer, "action" varchar not null, "payload" varchar, "created_at" datetime not null default CURRENT_TIMESTAMP);
INSERT INTO migrations VALUES(1,'2014_10_12_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'2014_10_12_100000_create_password_reset_tokens_table',1);
INSERT INTO migrations VALUES(3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO migrations VALUES(4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO migrations VALUES(5,'2023_03_20_042700_create_jobs_table',1);
INSERT INTO migrations VALUES(6,'2023_03_21_214331_create_models_table',1);
INSERT INTO migrations VALUES(7,'2023_05_17_022219_create_auth_records',1);