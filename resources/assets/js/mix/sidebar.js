var collapsibles = document.getElementsByClassName("collapsible-sidebar");
for (i = 0; i < collapsibles.length; i++) {
  // Setup click listener
  collapsibles[i].addEventListener("click", function () {
    // Onclick, toggle the item
    toggleAccordionItem(this.getAttribute("name"), true);
    // Collapse all other items for accordion behavior
    for (j = 0; j < collapsibles.length; j++) {
      if (collapsibles[j].getAttribute("name") !== this.getAttribute("name")) {
        toggleAccordionItem(collapsibles[j].getAttribute("name"));
      }
    }
  });
}
// Set default states on load
for (j = 0; j < collapsibles.length; j++) {
  if (collapsibles[j].nextElementSibling.innerHTML.indexOf("active") !== -1) {
    collapsibles[j].click();
    break;
  }
}

function toggleAccordionItem(itemName, openThisItem = false) {
  let itemClickable = document.getElementById(itemName + "Clickable");
  let itemCollapsible = document.getElementById(itemName + "Accordion");
  if (!itemClickable || !itemCollapsible) {
    return;
  }
  if (openThisItem) {
    itemClickable.getElementsByClassName("caret")[0].classList.add("open");
    itemCollapsible.classList.remove("collapse");
    itemCollapsible.classList.add("in");
    itemCollapsible.classList.add("active");
    return;
  }
  itemClickable.getElementsByClassName("caret")[0].classList.remove("open");
  itemCollapsible.classList.add("collapse");
  itemCollapsible.classList.remove("in");
  itemCollapsible.classList.remove("active");
}
