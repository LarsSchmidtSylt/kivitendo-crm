-- @tag: Calendar02
-- @description: Table events, start + stop mit tsrange   

--Starttag

UPDATE events SET stop = start WHERE stop < start;
UPDATE events SET repeat = 'day' WHERE repeat IS NULL; 
UPDATE events SET repeat_factor = 0 WHERE repeat_factor IS NULL; 
UPDATE events SET repeat_quantity = 0 WHERE repeat_quantity IS NULL; 

DROP TABLE IF EXISTS events_tmp CASCADE;
CREATE TABLE events_tmp(
    id              SERIAL NOT NULL PRIMARY KEY,
    title           TEXT,
    duration        TSRANGE,
    repeat         CHAR(5), 
    repeat_factor   SMALLINT, 
    repeat_quantity SMALLINT, 
    repeat_end      TIMESTAMP WITHOUT TIME ZONE, 
    description 	TEXT,
    location        TEXT,
    uid             INT,
    prio            SMALLINT,
    category 		SMALLINT,
    visibility		SMALLINT,
    "allDay"        BOOLEAN,
    color 		    CHAR(7),
    job             BOOLEAN,
    done            BOOLEAN,
    job_planned_end TIMESTAMP WITHOUT TIME ZONE,
    cust_vend_pers  TEXT		
);
--ALTER TABLE events RENAME COLUMN allday TO "allDay";
INSERT INTO events_tmp ( title, duration, repeat, repeat_factor, repeat_quantity, repeat_end, description, location, uid, prio, category, visibility, "allDay", color, job, done, job_planned_end, cust_vend_pers ) SELECT title, ('('||(start)::text || ' , ' || (stop)::text || ']')::TSRANGE  AS duration, repeat, repeat_factor, repeat_quantity, repeat_end, description, location, uid, prio, category, visibility, allday, color, job, done, job_planned_end, cust_vend_pers  FROM events;
DROP TABLE IF EXISTS events CASCADE;
ALTER TABLE events_tmp RENAME TO events;
