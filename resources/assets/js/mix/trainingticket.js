var newForm = $("#newTrainingTicket");
var editForm = $("#editTrainingTicket");
var draft = $("#draft");

if (newForm.length || (editForm.length && draft.length)) {
  var form = newForm.length ? newForm : editForm;
  var ajaxUrl = form.attr("action");

  setInterval(function () {
    var formData = form.serializeArray();
    var editors = window.editors;
    // CKEDITOR does not update text area thus we need to get it manually
    formData.find((formItem) => formItem.name === "trainer_comments").value =
      editors["trainer_comments"].getData();

    formData.find((formItem) => formItem.name === "comments").value =
      editors["comments"].getData();

    formData.push(
      { name: "action", value: "draft" },
      { name: "automated", value: 1 },
      { name: "is_new", value: newForm.length }
    );

    $.ajax({
      type: "POST",
      url: ajaxUrl,
      data: $.param(formData),
      success: function (result) {
        if (newForm.length) {
          window.location.replace(result);
        }

        if (!result) {
          window.close();
        }

        // Using device time opposed to database
        $("#autosaveIndicator").text(
          "Last autosaved at: " + new Date().toLocaleTimeString()
        );
      },
    });
  }, 60000);
}

const stars = document.querySelectorAll("#stars span");

// Needed for if you submit and get redirected back the stars will now still be highlighted
var rating = document.querySelector("#stars input[name='score']").value;
if (rating) {
  for (var i = 0; i < rating; i++) {
    stars.eq(i).text("\u2605");
  }
}

document.querySelectorAll(".your-selector").forEach(function (el) {
  el.addEventListener("click", function () {
    // Reset all stars to hollow (☆)
    document.querySelectorAll("#stars .your-selector").forEach(function (star) {
      star.textContent = "\u2606";
    });

    // Fill this star and all previous siblings (★)
    let current = this;
    while (current) {
      current.textContent = "\u2605";
      current = current.previousElementSibling;
    }

    // Set the hidden input value
    const input = document.querySelector("#stars input[name='score']");
    if (input) {
      input.value = this.dataset.rating;
    }
  });
});

document.querySelectorAll("#start, #end").forEach(function (el) {
  el.addEventListener("change", function () {
    setTimeout(function () {
      autoCalcDuration(
        document.getElementById("start").value,
        document.getElementById("end").value,
        document.getElementById("duration").value
      );
    }, 100);
  });
});

window.autoCalcDuration = (time1, time2, target) => {
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
  }
};

window.addEventListener("load", function () {
  const showSuggestions = document.getElementById("showSuggestions");
  if (showSuggestions) {
    // Assuming you're using Bootstrap's modal component
    const modal = new bootstrap.Modal(showSuggestions);
    modal.show();
  }
});

window.fillSession = (session) => {
  const showSuggestions = document.getElementById("showSuggestions");
  if (showSuggestions) {
    const modal =
      bootstrap.Modal.getInstance(showSuggestions) ||
      new bootstrap.Modal(showSuggestions);
    modal.hide();
  }

  document.getElementById("scheddy_id").value = session.scheddy_id;
  document.getElementById("controller").value = session.student_cid;
  document.getElementById("position").value = session.lesson_type;
  document.getElementById("date").value = session.date;
  document.getElementById("start").value = session.start_time;
};
