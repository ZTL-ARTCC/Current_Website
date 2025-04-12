var coll = document.getElementsByClassName("collapsible-sidebarx");
for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function () {
    return;
    this.getElementsByClassName("caret")[0].classList.toggle("open");
    this.classList.toggle("active");
    let collapsible = this.nextElementSibling;
    let content = collapsible.firstElementChild;
    $(collapsible).collapse("toggle");

    for (j = 0; j < coll.length; j++) {
      let e = coll[j];
      if (
        e.getAttribute("name") !== this.getAttribute("name") &&
        e.classList.contains("active")
      ) {
        e.getElementsByClassName("caret")[0].classList.toggle("open");
        e.classList.toggle("active");
      }
    }
  });
}
for (j = 0; j < coll.length; j++) {
  if (coll[j].nextElementSibling.innerHTML.indexOf("active") !== -1) {
    coll[j].click();
    break;
  }
}
