$(function () {
  $("#datetimepicker1").datetimepicker({
    format: "L",
  });
});

$(function () {
  $("#datetimepicker2").datetimepicker({
    format: "HH:mm",
  });
});

$(function () {
  $("#datetimepicker3").datetimepicker({
    format: "HH:mm",
  });
});

$(function () {
  $("#datetimepicker4").datetimepicker({
    format: "HH:mm",
  });
});
$(document).ready(function ($) {
  $("#timepicker").datetimepicker({
    format: "hh:mm a",
  });
});

$("#start,#end").on("change", function () {
  setTimeout(function () {
    autoCalcDuration(
      document.getElementById("start").value,
      document.getElementById("end").value,
      document.getElementById("duration").value
    );
  }, 100);
});

function autoCalcDuration(time1, time2, target) {
  if (time1 != "" && time2 != "") {
    var start = time1.split(":");
    var end = time2.split(":");
    var startDeci = parseInt(start[0]) + parseInt(start[1]) / 60;
    var endDeci = parseInt(end[0]) + parseInt(end[1]) / 60;
    if (startDeci > endDeci) {
      endDeci += 24;
    }
    var duration = endDeci - startDeci;
    var duration_hours = parseInt(duration);
    var duration_minutes = Math.round((duration - duration_hours) * 60);
    if (duration_hours < 10) {
      duration_hours = "0" + duration_hours;
    }
    if (duration_minutes < 10) {
      duration_minutes = "0" + duration_minutes;
    }
    document.getElementById("duration").value =
      duration_hours + ":" + duration_minutes;
    $("#datetimepicker4").datetimepicker({
      format: "HH:mm",
    });
  }
}
