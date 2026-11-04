# Assignment 8 – Retrobaser (The Code Project)

**DIKULT205 Web Design II · University of Bergen · spring 2020 · pair project (group 3)**

Retrobaser is a community site for retro computer and console games such as Pac-Man, Super Mario 3, Sonic, Lotus and Warcraft. Members can browse games, comment on them, and rate them from one to five stars. Admins manage users and the game catalogue.

The idea behind it: retro games are cultural artefacts worth keeping and talking about, just like old books and films.

## What I did

We were two students. Each file starts with a comment saying who wrote it ("Student 110" is me, "Student 108" is my partner). I was responsible for **user accounts and the admin side**:

| Area | Files |
|---|---|
| Sign-up with validation and email activation | `signup.php`, `templates/signup.inc.php`, `activate.php` |
| Login, logout and sessions with two user levels (regular / admin) | `login.php`, `logout.php`, `templates/logout.inc.php` |
| Change password and forgot password (emails a temporary password) | `change_password.php`, `forgot_password.php`, `templates/*_password.inc.php` |
| Admin: list users, edit a user, delete a user | `viewusers.php`, `edituser.php`, `deleteuser.php` |
| Admin: add, list, edit and delete games on one page | `viewgames.php`, `editgames.php`, `deletegames.php` |
| Header and navigation that change with login state and user level | `templates/header.php`, `templates/header_games.php`, `templates/footer.php` |
| The Retrobaser SVG logo and about half of the CSS | `icons/retrobaser.svg`, `css/style.css` |
| Star rating, part 2 (saving and updating a member's rating) | `games.php` |

My partner built the comments system (`comments.php`, `comments.inc.php`, `videogame_comments.php`, `editcomment.php`, `editgamecomment.php`) and the first part of the rating display. We built the game list, sorting and the database connection together.

## Technical notes

- **Stack:** PHP with `mysqli`, MySQL, HTML5, CSS3 and retro pixel web fonts (Press Start 2P, Retro Gaming, SF Atarian).
- **Passwords** are stored with `password_hash()` and checked with `password_verify()`.
- **Sign-up** uses prepared statements. Other queries use `mysqli_real_escape_string()`. Today I would use prepared statements everywhere.
- **Access control:** admin pages check `$_SESSION['user_level']` and send regular users away.
- **Tables used:** `users`, `videogame`, `videogame_comments`, `videogame_ratings`, `comments`, `images`. The database dump was not kept, so there is no schema file.

Larry Ullman's *PHP and MySQL for Dynamic Web Sites* (5th ed., Peachpit Press, 2018) was my main guide. I used its login and user-admin tutorials for the account system. Then I applied the same view/edit/delete pattern to games, so admins could manage the whole catalogue from one page.

## Design choices

- The visual style borrows from 8- and 16-bit games: pixel fonts, star icons and a joystick image, so the site feels like the games it is about.
- The navigation shows only what the current visitor can do. Guests see sign-up and login, members see their account options, and admins also see the admin tables. This keeps the interface simple for each role.

## Running it

1. Create a MySQL database with the tables listed above.
2. Copy `mysqli_connect.example.php` to `mysqli_connect.php` and fill in your database details.
3. Run `php -S localhost:8000` in this folder and open `http://localhost:8000/index.php`.
