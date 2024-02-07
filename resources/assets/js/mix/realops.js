$(function () {
  $("#datetimepicker").datetimepicker({
    format: "L",
  });
});

function realopsValidateAndSubmit() {
  let valid = true;
  let validateFields = [
    "realops_add_edit_dep_time",
    "realops_add_edit_arr_time",
  ];
  validateFields.forEach(function (field_id) {
    let dep_time = document.getElementById(field_id).value;
    if (!dep_time.match(/^\d{2}:\d{2}$/)) {
      if (!fixTime(field_id)) {
        document.getElementById(field_id).classList.add("is-invalid");
        valid = false;
      } else {
        document.getElementById(field_id).classList.remove("is-invalid");
      }
    }
  });
  if (valid) {
    document.getElementById("realops_add_edit_flight").submit();
  }
}

function realopsFilterValidateAndSubmit() {
  let time_valid = true;
  let input_time = document.getElementById("time_filter").value;
  if (!input_time.match(/^\d{2}:\d{2}$/) && input_time != "") {
    time_valid = fixTime("time_filter");
  }
  let date_valid = true;
  let input_date = document.getElementById("date_filter").value;
  if (!input_date.match(/^\d{4}-\d{2}-\d{2}$/) && input_date != "") {
    date_valid = fixDate("date_filter");
  }
  if (time_valid) {
    document.getElementById("time_filter").classList.remove("is-invalid");
  } else {
    document.getElementById("time_filter").classList.add("is-invalid");
  }
  if (date_valid) {
    document.getElementById("date_filter").classList.remove("is-invalid");
  } else {
    document.getElementById("date_filter").classList.add("is-invalid");
  }
  if (time_valid && date_valid) {
    document.getElementById("realops_filter").submit();
  }
}

function fixTime(el) {
  // Fixes times to HH:MM format
  let timeStr = document.getElementById(el).value;
  if (timeStr.match(/^\d{1,2}:\d{2}$/)) {
    // Format H:MM
    document.getElementById(el).value = "0" + timeStr;
    return true;
  } else if (timeStr.match(/^\d{1,2}:\d{2}\D*$/)) {
    // Format HH:MMz or HH:MM:SS
    document.getElementById(el).value = timeStr.substr(0, 5);
    return true;
  } else if (timeStr.match(/^\d{3}\D*$/)) {
    // Format HMM or HMM*
    document.getElementById(el).value =
      "0" + timeStr.substr(0, 1) + ":" + timeStr.substr(1, 2);
    return true;
  } else if (timeStr.match(/^\d{4}\D*$/)) {
    // Format HHMM or HHMM*
    document.getElementById(el).value =
      timeStr.substr(0, 2) + ":" + timeStr.substr(2, 2);
    return true;
  }
  return false;
}

function fixDate(el) {
  // Fixes dates to YYYY-MM-DD format
  let dateStr = document.getElementById(el).value;
  let currDate = new Date();
  let currYear = currDate.getUTCFullYear().toString();
  if (dateStr.match(/^(\d{4}|\d{2})-\d{1,2}-\d{1,2}$/)) {
    // Format YY-MM-DD, YYYY-M-D
    let dateArr = document.getElementById(el).value.split("-");
    if (dateArr[0].length == 2) {
      dateArr[0] = currYear.substr(0, 2) + dateArr[0];
    }
    document.getElementById(el).value =
      dateArr[0] +
      "-" +
      dateArr[1].padStart(2, "0") +
      "-" +
      dateArr[2].padStart(2, "0");
    return true;
  } else if (dateStr.match(/^\d{1,2}\/\d{1,2}\/(\d{4}|\d{2})$/)) {
    // MM/DD/YYYY, MM/DD/YY, M/D/YY
    let dateArr = document.getElementById(el).value.split("/");
    if (dateArr[2].length == 2) {
      dateArr[2] = currYear.substr(0, 2) + dateArr[2];
    }
    document.getElementById(el).value =
      dateArr[2] +
      "-" +
      dateArr[0].padStart(2, "0") +
      "-" +
      dateArr[1].padStart(2, "0");
    return true;
  }
  return false;
}
