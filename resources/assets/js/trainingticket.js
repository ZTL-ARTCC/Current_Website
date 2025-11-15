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

[startInput, endInput].forEach((input) => {
  input.addEventListener("change", () => {
    setTimeout(() => {
      autoCalcDuration(
        startInput.value,
        endInput.value,
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
