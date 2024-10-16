var stars = $("#stars span");

// Needed for if you submit and get redirected back the stars will now still be highlighted
var rating = $("#stars input[name='service_level']").val();
if (rating) {
  for (var i = 0; i < rating; i++) {
    stars.eq(i).text("\u2605");
  }
}

stars.each(function () {
  $(this).hover(
    function () {
      $(this).prevAll().addBack().addClass("star-hover");
    },
    function () {
      $(this).prevAll().addBack().removeClass("star-hover");
    }
  );
  $(this).on("click", function () {
    // Different format from the blade beacause JS internally only supports UTF-16
    stars.text("\u2606");
    $(this).prevAll().addBack().text("\u2605");
    $("#stars input[name='service_level']").val($(this).data("rating"));
  });
});
