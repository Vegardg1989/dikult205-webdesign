"""Screenshots from my DIKULT205 exam portfolio (June 2020), shown in the demo overlay."""

NOTE = (
    "Screenshot from my exam portfolio, June 2020, showing the site as I submitted it. The code behind it is lost, "
    "so the demo is a restoration based on these screenshots. "
    "The original student work used logos of real organisations (Urban Labs, the NRA and the "
    "Illinois State Rifle Association) as material for the rhetorical analysis. The live demo "
    "replaces them with fictional logos. Neither version is affiliated with any organisation."
)

BY_TEMPLATE = {
    "world1/home.html": [
        ("world1-home.jpg", "World 1, The Crime Lab Project: the landing page"),
        ("world1-home-content.jpg", "World 1: the landing page text"),
    ],
    "world1/research.html": [
        ("world1-research-chart.jpg", "World 1: the heat-and-shootings chart on the research page"),
    ],
    "world2/home.html": [
        ("world2-home.jpg", "World 2, Chicago Rifle Association: the landing page, with the menu at the bottom"),
    ],
}
BY_TEMPLATE["landing.html"] = BY_TEMPLATE["world1/home.html"] + BY_TEMPLATE["world2/home.html"]
