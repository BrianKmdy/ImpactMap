# Impact Map

### Requirements

- MySQL database
    - **Username**: root (NEEDS TO BE CHANGED! /php/common/constants.inc.php)
    - **Password**: root (NEEDS TO BE CHANGED! /php/common/constants.inc.php)
    - **Database**: ImpactMap
    - **Tables** (Details listed farther below):
        - Projects
        - History
        - Centers
        - Contacts
        - Users
- Webserver with PHP

### Code Organization

#### /
- **/**: Root directory for the website, all webfiles served from here
- **/index.htm**: The web page for the map
- **/admin.php**: The main page where admins will make changes to the map
- **/CreateTables.SQL**: SQL script to initialize the database. Should not be included when this project is actually launched.
- **/LoadTestData.SQL**: SQL script to load some data to play around with. Should not be included when this project is actually launched.

#### /php
- **/php**: All the php files for the site
- **/php/admin**: The server-side php files that allow admins to make changes to the map
- **/php/admin/projects**: The php files to add/edit/remove projects
- **/php/admin/history**: Files to restore projects from the history
- **/php/admin/users**: Files to allow the root admin to add/edit/remove other admins
- **/php/common**: Files to connect with and interact with the databse
- **/php/map**: Files used to fetch data for the map

#### /js
- **/js**: Javascript files to control client side interaction with map and request information from the server by using AJAX to ask for PHP files
- **/js/admin.js**: Controls admin interaction with the system, facilitates loading of the different admin subutilities such as the project table, center table, user table, etc...
- **/js/map.js**: Controls user interaction with the map. Loading pins, filtering pins, information popups, sending search data to server, etc...

#### /lib
- **/lib**: Third party javascript libraries are stored here
- **/lib/datetimepicker**: A javascript utility used to select a date and a time for the project history table
- **/lib/snowball_stemmer**: A stemming library to reduce words to their roots (i.e. running -> run). This is used on search requests to get better results.
- **/lib/typeahead**: A javascript library to show suggestions when typing in the search bar

#### /css
- **/css**: The CSS files that control web page layout, formatting, coloring, etc...

#### /img
- **/img**: Folder for images. Note, project images should be stored on third party server, not here.

#### /json
- **/json**: Contains any json data needed. Currently used to store an index of data for search suggestions.

### Database Tables
The SQL definitions for the tables are as follows:
```SQL
CREATE TABLE Projects (
	pid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	cid INT UNSIGNED,
	title VARCHAR(100),
	status INT,
	startDate DATE,
	endDate DATE,
	buildingName VARCHAR(100),
	address VARCHAR(100),
	zip VARCHAR(6),
	type int,
	summary TEXT,
	results TEXT,
	link VARCHAR(200),
	pic VARCHAR(200),
	conid INT UNSIGNED,
	fundedBy VARCHAR(100),
	keywords VARCHAR(100),
	stemmedSearchText TEXT,
	visible BOOLEAN NOT NULL DEFAULT FALSE,
	lat REAL,
	lng REAL,
	FULLTEXT(stemmedSearchText)
) ENGINE=MyISAM;

CREATE TABLE History (
	hid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	time DATETIME NOT NULL DEFAULT '2016-06-01 00:00:00',
	deleted DATETIME NOT NULL DEFAULT '3000-01-01 00:00:00',
	editedBy INT UNSIGNED,
	pid INT UNSIGNED,
	cid INT UNSIGNED,
	title VARCHAR(100),
	status INT,
	startDate DATE,
	endDate DATE,
	buildingName VARCHAR(100),
	address VARCHAR(100),
	zip VARCHAR(6),
	type int,
	summary TEXT,
	results TEXT,
	link VARCHAR(200),
	pic VARCHAR(200),
	conid INT UNSIGNED,
	fundedBy VARCHAR(100),
	keywords VARCHAR(200),
	stemmedSearchText TEXT,
	visible BOOLEAN NOT NULL DEFAULT FALSE,
	lat REAL,
	lng REAL
);

CREATE TABLE Centers (
	cid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100),
	acronym VARCHAR(8),
	color CHAR(7)
);

CREATE TABLE Contacts (
	conid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100),
	email VARCHAR(100),
	phone VARCHAR(16)
);

CREATE TABLE Users (
	uid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	email VARCHAR(100),
	firstName VARCHAR(100),
	lastName VARCHAR(100),
	phone VARCHAR(16),
	password VARCHAR(64),
	authenticated BOOLEAN NOT NULL DEFAULT FALSE,
	root BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TRIGGER `defaultTime` BEFORE INSERT ON  `History` 
FOR EACH ROW 
SET NEW.time = NOW();
```
### Third-party libraries used
- Jquery
- Bootstrap
- Google Maps
- Bloodhound
- Typeahead
- Snowball
- Datetimepicker