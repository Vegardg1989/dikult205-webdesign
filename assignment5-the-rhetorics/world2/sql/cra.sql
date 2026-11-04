-- Restored in 2026 (see README). Schema for World 2, Chicago Rifle Association.
-- The users table is the same design as Retrobaser: user_level 1 = admin, 0 = member,
-- active holds the activation code until the e-mail link is clicked (NULL = active).

CREATE TABLE users (
  user_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(40) NOT NULL UNIQUE,
  email VARCHAR(80) NOT NULL UNIQUE,
  pass VARCHAR(255) NOT NULL,
  user_level TINYINT(1) NOT NULL DEFAULT 0,
  active CHAR(32) NULL,
  registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE news (
  news_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  title VARCHAR(120) NOT NULL,
  body TEXT NOT NULL,
  image VARCHAR(60) NOT NULL DEFAULT '',
  created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE SET NULL
);

CREATE TABLE ranges (
  range_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  location VARCHAR(120) NOT NULL,
  description TEXT NOT NULL
);

CREATE TABLE range_ratings (
  user_id INT UNSIGNED NOT NULL,
  range_id INT UNSIGNED NOT NULL,
  stars TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (user_id, range_id),
  FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE,
  FOREIGN KEY (range_id) REFERENCES ranges (range_id) ON DELETE CASCADE
);

-- Fictional sample data.
INSERT INTO ranges (name, location, description) VALUES
 ('Riverbend Training Center', 'Southwest side, Chicago', 'Indoor lanes, beginner safety courses every Saturday.'),
 ('Prairie Ridge Outdoor Range', 'Will County', 'Outdoor rifle range up to 300 yards, open all year.'),
 ('Lakeview Sportsmen Club', 'North side, Chicago', 'Members-only club with a hunter education programme.');
