function copyToClipboard(eId) {
  let e = document.getElementById(eId);
  navigator.clipboard.writeText(e.innerHTML);
}

$(function () {
  $('[data-bs-toggle="tooltip"]').tooltip();
});

// Setup Datetime Picker
import { TempusDominus } from "@eonasdan/tempus-dominus";

window.onload = function () {
  const dtPickerDate = document.getElementsByClassName("dt_picker_date");
  for (const picker of dtPickerDate) {
    new TempusDominus(picker, {
      display: {
        viewMode: "calendar",
        keepOpen: false,
        components: {
          calendar: true,
          clock: false,
        },
      },
      localization: {
        format: "L",
      },
    });
  }
  const dtPickerTime = document.getElementsByClassName("dt_picker_time");
  for (const picker of dtPickerTime) {
    new TempusDominus(picker, {
      display: {
        viewMode: "clock",
        keepOpen: false,
        components: {
          calendar: false,
          clock: true,
        },
      },
      localization: {
        format: "HH:mm",
      },
    });
  }
  const dtPickerDateTime =
    document.getElementsByClassName("dt_picker_datetime");
  for (const picker of dtPickerDateTime) {
    new TempusDominus(picker, {
      display: {
        viewMode: "calendar",
        keepOpen: false,
        components: {
          calendar: true,
          clock: true,
        },
      },
      localization: {
        format: "L HH:mm",
      },
    });
  }
};

// Chart JS
import Chart from "chart.js/auto";
window.Chart = Chart;
