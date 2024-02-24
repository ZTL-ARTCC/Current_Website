function copyToClipboard(eId) {
  let e = document.getElementById(eId);
  navigator.clipboard.writeText(e.innerHTML);
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

$(function () {
  $(".dt_picker_date").datetimepicker({
    format: "L",
  });
});
$(function () {
  $(".dt_picker_time").datetimepicker({
    format: "HH:mm",
  });
});
