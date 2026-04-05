const newForm = document.getElementById("newTrainingTicket");
const editForm = document.getElementById("editTrainingTicket");
const draft = document.getElementById("draft");

if (newForm || (editForm && draft)) {
  const form = newForm || editForm;
  const ajaxUrl = form.getAttribute("action");

  setInterval(() => {
    const formData = new FormData(form);
    const editors = window.editors;

    // CKEditor fields need manual sync
    formData.set("trainer_comments", editors["trainer_comments"].getData());
    formData.set("comments", editors["comments"].getData());

    // Add extra fields
    formData.append("action", "draft");
    formData.append("automated", "1");
    formData.append("is_new", newForm ? "1" : "0");

    fetch(ajaxUrl, {
      method: "POST",
      body: new URLSearchParams(formData),
    })
      .then((response) => response.text())
      .then((result) => {
        if (newForm && result) {
          window.location.replace(result);
        } else if (!result) {
          window.close();
        }

        const indicator = document.getElementById("autosaveIndicator");
        if (indicator) {
          indicator.textContent =
            "Last autosaved at: " + new Date().toLocaleTimeString();
        }
      })
      .catch((error) => console.error("Autosave failed:", error));
  }, 60000);
}

const stars = document.querySelectorAll("#stars span");
const scoreInput = document.querySelector("#stars input[name='score']");
const rating = scoreInput.value;

// Needed so if you submit and get redirected back the stars will still be highlighted
if (rating) {
  for (let i = 0; i < rating; i++) {
    stars[i].textContent = "\u2605";
  }
}

stars.forEach((star, i) => {
  // Hover in
  star.addEventListener("mouseenter", () => {
    for (let j = 0; j <= i; j++) {
      stars[j].classList.add("star-hover");
    }
  });

  // Hover out
  star.addEventListener("mouseleave", () => {
    for (let j = 0; j <= i; j++) {
      stars[j].classList.remove("star-hover");
    }
  });

  // Click to set rating
  star.addEventListener("click", () => {
    stars.forEach((s) => (s.textContent = "\u2606")); // empty star
    for (let j = 0; j <= i; j++) {
      stars[j].textContent = "\u2605"; // filled star
    }
    scoreInput.value = star.dataset.rating;
  });
});

const startInput = document.getElementById("start");
const endInput = document.getElementById("end");
const durationInput = document.getElementById("duration");

[startInput, endInput].forEach((input) => {
  input.addEventListener("change", () => {
    setTimeout(() => {
      timeRectifyFormat([startInput, endInput]);
      autoCalcDuration(startInput.value, endInput.value, durationInput);
      timeRectifyFormat([durationInput]);
    }, 100);
  });
});

durationInput.addEventListener("change", () => {
  timeRectifyFormat([durationInput]);
});

window.timeRectifyFormat = (times) => {
  const errorValue = "00:00";
  for (let t = 0; t < times.length; t++) {
    let timeStrRegex = /^\d{1,2}:\d{0,2}$/;
    if (timeStrRegex.test(times[t].value)) {
      let hours = times[t].value.split(":")[0];
      let minutes = times[t].value.split(":")[1];
      times[t].value = hours.padStart(2, "0") + ":" + minutes.padStart(2, "0");
    } else if (isNaN(times[t].value)) {
      times[t].value = errorValue;
    } else {
      switch (times[t].value.length) {
        case 1:
          times[t].value = "0" + times[t].value + ":00";
          break;
        case 2:
          times[t].value = times[t].value + ":00";
          break;
        case 3:
          times[t].value =
            "0" +
            times[t].value.substring(0, 1) +
            ":" +
            times[t].value.substring(1, 3);
          break;
        case 4:
          times[t].value =
            times[t].value.substring(0, 2) +
            ":" +
            times[t].value.substring(2, 4);
          break;
        default:
          times[t].value = errorValue;
      }
    }
  }
};

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
    target.value = duration_hours + ":" + duration_minutes;
  }
};

window.addEventListener("load", () => {
  const showSuggestions = document.getElementById("showSuggestions");
  if (showSuggestions) {
    const modal = new bootstrap.Modal(showSuggestions);
    modal.show();
  }
});

window.fillSession = (session) => {
  const showSuggestions = document.getElementById("showSuggestions");
  if (showSuggestions) {
    const modal = bootstrap.Modal.getInstance(showSuggestions);
    modal?.hide();
  }

  document.getElementById("scheddy_id").value = session.scheddy_id;
  document.getElementById("controller").value = session.student_cid;
  document.getElementById("position").value = session.lesson_type;
  document.getElementById("date").value = session.date;
  document.getElementById("start").value = session.start_time;
};

const controllerSelect = document.getElementById("controller");
controllerSelect.addEventListener("change", () => {
  const xhr = new XMLHttpRequest();
  xhr.open(
    "POST",
    "/dashboard/training/tickets/get-rating/" + controllerSelect.value,
    true
  );
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.setRequestHeader(
    "X-CSRF-TOKEN",
    document.querySelector('meta[name="csrf-token"]').getAttribute("content")
  );
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      let el = document.getElementById("s1_rating_push");
      if (data.rating == 1) {
        el.classList.remove("d-none");
        el.classList.add("d-inline");
      } else {
        el.classList.remove("d-inline");
        el.classList.add("d-none");
      }
    }
  };
  xhr.send();
});
