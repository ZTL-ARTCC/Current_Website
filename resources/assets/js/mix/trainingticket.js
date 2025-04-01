var newForm = $("#newTrainingTicket");
var editForm = $("#editTrainingTicket");
var draft = $("#draft");

if (newForm.length || (editForm.length && draft.length)) {
  var form = newForm.length ? newForm : editForm;
  var ajaxUrl = form.attr("action");

  setInterval(function () {
    var formData = form.serializeArray();
    // CKEDITOR does not update text area thus we need to get it manually
    formData.find((formItem) => formItem.name === "trainer_comments").value =
      window.editor.getData();

    formData.push(
      { name: "action", value: "draft" },
      { name: "automated", value: true }
    );

    $.ajax({
      type: "POST",
      url: ajaxUrl,
      data: $.param(formData),
      success: function (result) {
        if (newForm.length) {
          window.location.replace(result);
        }
        // Using device time opposed to database
        $("#autosaveIndicator").text(
          "Last autosaved at: " + new Date().toLocaleTimeString()
        );
      },
    });
  }, 60000);
}

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

window.autoCalcDuration = autoCalcDuration;
