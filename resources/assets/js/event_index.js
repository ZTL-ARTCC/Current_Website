document
  .getElementById("denylistEvent")
  .addEventListener("show.bs.modal", function (e) {
    const eventId = e.relatedTarget.dataset.id;
    const deleteLink = this.querySelector("#deleteLink");
    const denylistLink = this.querySelector("#denylistLink");

    deleteLink.setAttribute(
      "href",
      "/dashboard/admin/events/delete/" + eventId
    );
    denylistLink.setAttribute(
      "href",
      "/dashboard/admin/events/delete/" + eventId + "?denylist=true"
    );
  });
