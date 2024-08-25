var stars = $("#stars span");

// Needed for if you submit and get redirected back the stars will now still be highlighted
var rating = $("#stars input[name='score']").val();
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
    $("#stars input[name='score']").val($(this).data("rating"));
  });
});

$("#start,#end").on("change", function () {
  settimeout(function () {
    autocalcduration(
      document.getelementbyid("start").value,
      document.getelementbyid("end").value,
      document.getelementbyid("duration").value
    );
  }, 100);
});

function autocalcduration(time1, time2, target) {
  if (time1 != "" && time2 != "") {
    var start = time1.split(":");
    var end = time2.split(":");
    var startdeci = parseint(start[0]) + parseint(start[1]) / 60;
    var enddeci = parseint(end[0]) + parseint(end[1]) / 60;
    if (startdeci > enddeci) {
      enddeci += 24;
    }
    var duration = enddeci - startdeci;
    var duration_hours = parseint(duration);
    var duration_minutes = math.round((duration - duration_hours) * 60);
    if (duration_hours < 10) {
      duration_hours = "0" + duration_hours;
    }
    if (duration_minutes < 10) {
      duration_minutes = "0" + duration_minutes;
    }
    document.getelementbyid("duration").value =
      duration_hours + ":" + duration_minutes;
    $("#datetimepicker4").datetimepicker({
      format: "hh:mm",
    });
  }
}

window.autocalcduration = autocalcduration;
