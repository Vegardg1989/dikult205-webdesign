# Django rewrite (2026)

Both DIKULT205 projects rebuilt in Python and Django, to run as live demos:

- **Retrobaser:** https://www.vegardgundersen.com/demo/retrobaser/
- **The Rhetorics:** https://www.vegardgundersen.com/demo/rhetorics/

The PHP originals from 2020 are in [`../assignment8-retrobaser`](../assignment8-retrobaser) and [`../assignment5-the-rhetorics`](../assignment5-the-rhetorics). This folder is a standalone Django project. The same two apps also run inside my portfolio site.

## What carries over and what changed

| | 2020 PHP | 2026 Django |
|---|---|---|
| Pages | One PHP file per page, with `include` for header and footer | Views and URL routes, with templates that extend a base template |
| Database | Hand-written SQL through `mysqli` | Django models and the ORM. No string-built SQL, so no SQL injection. |
| Passwords | `password_hash()` / `password_verify()` | Django's `make_password()` / `check_password()` (PBKDF2) |
| Forms | Mostly `mysqli_real_escape_string()` | CSRF tokens on every form, escaped template output, server-side validation |
| Roles | `$_SESSION['user_level']` 0 or 1 | `Member.is_admin`, checked by an `admin_required` decorator |
| Look | `style.css` / `style1.css` | The same stylesheets, fonts and images, plus a small `demo.css` |

## Retrobaser

Mapping from PHP files to Django views:

| PHP | Django view |
|---|---|
| `index.php` | `index` |
| `login.php`, `logout.php` | `login`, `logout` |
| `signup.php`, `activate.php` | `signup` (switched off in the demo) |
| `forgot_password.php`, `change_password.php` | `forgot_password`, `change_password` |
| `games.php` (sort and star rating) | `games` |
| `games/retrogames.php` (game page, rating, comments) | `game_detail` |
| `comments.php`, `editcomment.php` (Discuss/Suggest) | `board`, `edit_board_comment` |
| `viewusers.php`, `edituser.php`, `deleteuser.php` | `admin_users`, `edit_user`, `delete_user` |
| `viewgames.php`, `editgames.php`, `deletegames.php` | `admin_games`, `edit_game`, `delete_game` |

The star thresholds, sort options, page size of 5 in the user table and the yes/no delete confirmation all work the same as in the PHP version.

## The Rhetorics

Parts of the original code are lost: only my rough draft from April 2020 survived. The submitted version was restored in 2026 with help from Claude, Anthropic's AI assistant, from the screenshots in my exam portfolio. Each world page has a **See the 2020 original** link that opens those screenshots.

- **World 1, The Crime Lab Project:** home, research and crime reduction pages, laid out like the submitted version, with the RESEARCH / CRIME REDUCTION menu.
- **World 2, Chicago Rifle Association:**
  - the menu at the bottom of the page (CRA, News, Ranges, Signup, Login), opened on phones by the original `nv.js`
  - the same login system as Retrobaser: sign-up, login, forgot password, change password, admin and member levels
  - news that admins can write, edit and delete
  - gun ranges that members rate from one to five stars
- **Landing page:** explains the assignment and compares how each world uses the facts of the news story.

All organisations, ranges and posts are fictional, and the demo is not affiliated with any organisation. The 2020 student version used real organisations' logos (Urban Labs, the NRA and the Illinois State Rifle Association). The demo replaces them with fictional logos of the same size, so it can't be mistaken for those organisations. The Crime Lab Project logo and the CRA wordmark are my own designs.

## Demo rules

- **Logins:** the demo accounts are shown on the pages. Sign-up, e-mail and image uploads are switched off.
- **Reset:** if the last reset is more than 24 hours old, the next visit restores the seed data (see `seed.ensure_fresh()`), so there is no cron job.
- **Protected accounts:** the shared demo logins can't be deleted, and their e-mail, role and password can't be changed, so the next visitor can still log in.
- **Separate users:** members are their own model, separate from `django.contrib.auth`, so demo accounts can never reach a host site's admin.

## Run it locally

```bash
cd django
python -m venv .venv
.venv/Scripts/activate        # Windows; use source .venv/bin/activate on macOS/Linux
pip install -r requirements.txt
python manage.py migrate
python manage.py runserver
# http://127.0.0.1:8000/demo/retrobaser/ and http://127.0.0.1:8000/demo/rhetorics/
python manage.py test
```
