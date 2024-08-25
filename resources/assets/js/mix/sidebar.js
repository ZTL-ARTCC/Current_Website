var coll = document.getElementsByClassName("collapsible");
for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function () {
    this.getElementsByClassName("caret")[0].classList.toggle("open");
    this.classList.toggle("active");
    let content = this.nextElementSibling;
    if (content.style.maxHeight) {
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    }
    for (j = 0; j < coll.length; j++) {
      let e = coll[j];
      if (
        e.getAttribute("name") !== this.getAttribute("name") &&
        e.classList.contains("active")
      ) {
        e.getElementsByClassName("caret")[0].classList.toggle("open");
        e.classList.toggle("active");
        e.nextElementSibling.style.maxHeight = null;
      }
    }
  });
}
