var stars = $("#stars span");

if (stars) {
  var rating = $("#stars").data("rating");
  if (rating) {
    for (var i = 0; i < rating; i++) {
      stars.eq(i).text("\u2605");
    }
  }
}
