// Initialize sidebar menu to open when a link is active
let collapsibles = document.getElementsByClassName("collapsible-sidebar");
for (j = 0; j < collapsibles.length; j++) {
  if (
    collapsibles[j].nextElementSibling.innerHTML.indexOf("nav-link active") !==
    -1
  ) {
    let button = collapsibles[j].getElementsByTagName("button")[0];
    button.click();
    break;
  }
}
