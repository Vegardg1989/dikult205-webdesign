function navFunction() {
  var x = document.getElementById("craNavbar");
  if (x.className === "navigaton-bar") {
    x.className += " responsive";
  } else {
    x.className = "navigaton-bar";
  }
}
