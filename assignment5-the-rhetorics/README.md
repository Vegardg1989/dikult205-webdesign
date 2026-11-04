# Assignment 5 – The Rhetorics

**DIKULT205 Web Design II · University of Bergen · spring 2020 · individual assignment**

## The task

Pick one real news story. Build two websites ("worlds") that use the same facts to persuade two opposing audiences. Then write an analysis of how each site persuades through text, images, layout and interaction.

## The story

A Chicago Tribune article from January 2020: a one-year-old was critically wounded while his parents fought over a gun in their apartment. At the hospital, the father claimed a gunman had fired at him and hit the boy. The blood in the apartment showed otherwise.

## The two worlds

| | World 1 – The Crime Lab Project | World 2 – Chicago Rifle Association (CRA) |
|---|---|---|
| Who it speaks for | A University of Chicago research lab | A local gun-owners' organisation |
| Main argument | Science and data can reduce gun violence: youth job programmes and precision policing work. | The Second Amendment protects the right to bear arms. The answer is education and gun safety, not gun control. |
| How the facts are used | The shooting is one example of a wider urban problem the lab's data can help solve. The father's lie wastes police resources. | People commit crimes whether they own guns or not. Law-abiding owners should not lose their rights because of others' actions. |
| Main persuasive tools | Statistics and infographics (logos), links to the parent organisation Urban Labs and news coverage (ethos), endorsement from investors like Magic Johnson (social proof) | The eagle, the flag and the stars (pathos: national pride), the logic of constitutional rights, member ratings of gun ranges |
| Files | `world1/home.php`, `world1/research.php` (April 2020 draft) | `world2/` (restored in 2026, see below) |

## What I built

- Two PHP sites with shared `templates/header.php` and `templates/footer.php` includes, so each world keeps one consistent look across its pages.
- A separate stylesheet for each world (`css/style.css` and `css/style1.css`). Each has its own colours, typography and imagery, matched to its audience.
- **World 1** uses a calm academic look: my own Crime Lab Project logo (a lab flask plus a data network), a link to the University of Chicago's Urban Labs as the parent organisation, photos of community work and charts of shootings by police district and by temperature.
- **World 2** uses patriotic symbols: a CRA logo made as an SVG (eagle, stars and stripes) and photos from gun ranges. There's also a small JavaScript file (`nv.js`) for the mobile hamburger menu.
- **Parts of the original code are lost.** The final versions on the university server had more content and a MySQL back end for World 2: member login, admin and regular user roles, news posts that admins could add and edit, and a star rating system for gun ranges. That server code was never saved. All I had left was the rough draft from April 2020, including World 1's `home.php`, which later became `world1/index.php`.

## Restoration (2026)

In 2026 I restored World 2 with help from Claude, Anthropic's AI assistant, so the repository shows the site I actually submitted and not only the draft. Every restored file starts with a comment that says so.

- **Sources:** the screenshots in my June 2020 exam portfolio, the April 2020 draft, and the login system I wrote for Retrobaser ([assignment 8](../assignment8-retrobaser/)). World 2 used the same login code.
- **What was restored in `world2/`:**
  - the menu at the bottom of the page, opened on phones by the original `nv.js`
  - sign-up with e-mail activation, login, logout, forgot password and change password
  - admin and member levels (`user_level`)
  - news that admins write, edit and delete
  - range ratings for members
  - the database schema in `sql/cra.sql`
- **What I changed on purpose:**
  - All SQL now uses prepared statements, and all output is escaped with `htmlspecialchars`.
  - The real logos (the Illinois State Rifle Association emblem and the NRA partner badge) are replaced with fictional logos of the same size. The CRA wordmark is my own 2020 design.
- **Still the draft:** World 1 (`world1/`) is the April 2020 draft. The live demo shows the submitted version of World 1.
- **The untouched draft** is in the first commit, [f27e73a](https://github.com/Vegardg1989/dikult205-webdesign/tree/f27e73a/assignment5-the-rhetorics).
- **The original logos** are still in `icons/` as part of the 2020 archive, but the restored pages don't use them.

## The analysis (summary)

I wrote a 7-page analysis using classical rhetoric (Aristotle's logos, ethos and pathos, from Keith & Lundberg) and newer work on persuasion and new media (LaGrandeur, Aczel, Nahai, Nodder, Arola). My main points:

- **Both sites are deliberative rhetoric.** Each argues for a course of action, not a verdict on the past.
- **Images persuade too.** World 1's landing photo of people painting a house together suggests unity and action. World 2's eagle and flag call on national pride. Following LaGrandeur, images belong within Aristotle's "available means of persuasion".
- **Data builds credibility.** World 1 states statistics in plain words (youth offered jobs were 43% less likely to be arrested for violent crimes) and backs them with charts. It doesn't overclaim: the site notes that *why* shootings peak on hot days is still debated.
- **Social proof adds ethos.** Endorsements from well-known investors tell readers that serious people trust the lab's results (Nodder).
- **Interaction changes the rhetoric.** Following Aczel, hyperlinks can break a linear argument, and user ratings make the audience into speakers as well as readers. CRA lets members rate ranges but has no discussion space, so it offers no room for invitational rhetoric.
- **Templates limit expression.** Following Arola, a CMS-style news editor makes posting easy for non-coders but limits what they can say through design.

**The conclusion:** no interface is neutral. The same facts, framed through different visual and textual choices, can reinforce opposite opinions.

### Sources

- Aczel, Petra. 2013. *Netroric: An Approach to New Media Rhetoric.*
- Arola, Kristin L. 2010. "The Design of Web 2.0: The Rise of the Template, the Fall of Design." *Computers and Composition* 27: 4–14.
- Keith, William M., and Christian O. Lundberg. 2008. *The Essential Guide to Rhetoric.* Boston: Bedford/St. Martin's.
- LaGrandeur, Kevin. 2003. "Digital Images and Classical Persuasion."
- Nahai, Nathalie. 2017. *Webs of Influence: The Psychology of Online Persuasion.* 2nd ed. Pearson.
- Nodder, Chris. 2013. *Evil by Design: Interaction Design to Lead Us into Temptation.* Indianapolis: Wiley.

## Running it

```bash
cd assignment5-the-rhetorics
php -S localhost:8000
# open http://localhost:8000/world1/home.php and http://localhost:8000/world2/index.php
```

World 1 needs no database. For World 2, create a MySQL database, load `world2/sql/cra.sql`, then copy `world2/mysqli_connect.example.php` to `world2/mysqli_connect.php` and fill in your own values. The live demo of both worlds is the Django version: https://www.vegardgundersen.com/demo/rhetorics/
