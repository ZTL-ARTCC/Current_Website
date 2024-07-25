$("#denylistEvent").on("show.bs.modal", function (e) {
  var eventId = $(e.relatedTarget).data("id");
  var deleteLink = $(this).find("#deleteLink");
  var denylistLink = $(this).find("#denylistLink");

  deleteLink.attr("href", "/dashboard/admin/events/delete/" + eventId);
  denylistLink.attr(
    "href",
    "/dashboard/admin/events/delete/" + eventId + "?denylist=true"
  );
});
