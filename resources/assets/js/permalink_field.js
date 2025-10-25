document.addEventListener("DOMContentLoaded", function () {
  const existingLink = document.getElementById("existingPermalinks");
  const permaLink = document.getElementById("permalink");

  function toggleexistingLink() {
    if (permaLink.value !== "" && permaLink.value !== existingLink.value) {
      existingLink.disabled = true;
    } else {
      existingLink.disabled = false;
    }
  }

  function toggleReadonly() {
    if (existingLink.value !== "") {
      permaLink.setAttribute("readonly", "readonly");
      permaLink.value = existingLink.value;
    } else {
      permaLink.removeAttribute("readonly");
      permaLink.value = "";
    }
  }

  toggleexistingLink();
  toggleReadonly();

  existingLink.addEventListener("change", toggleReadonly);
  permaLink.addEventListener("input", toggleexistingLink);
});
