window.onload = () => {
    let whole_event = document.getElementById("whole_event");
    let whole_event2 = document.getElementById("whole_event2");
    let from = document.getElementById("start_time1");
    let to = document.getElementById("end_time1");
    let timezone = document.getElementById("timezone");

    let update_checkboxes = () => {
        if (whole_event.checked) {
            from.disabled = true;
            to.disabled = true;
            timezone.disabled = true;
            from.value = null;
            to.value = null;
        } else {
            from.disabled = false;
            to.disabled = false;
            timezone.disabled = false;
        }
    };

    whole_event.onchange = update_checkboxes;
    whole_event2.onchange = update_checkboxes;
};