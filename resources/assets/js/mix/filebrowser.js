function itemReorder(id, pos, typ, act) {
  // Handles custom re-ordering of items in file browser
  var dType = "";
  switch (typ) {
    case 3:
      dType = "vatis";
      break;
    case 4:
      dType = "sop";
      break;
    case 5:
      dType = "loa";
      break;
    case 6:
      dType = "staff";
      break;
    case 7:
      dType = "training";
      break;
  }

  $.get(
    "/dashboard/admin/files/disp-order?id=" +
      id +
      "&pos=" +
      pos +
      "&act=" +
      act +
      "&typ=" +
      typ,
    function (data) {
      if (data.length > 0) {
        document
          .getElementById(dType)
          .getElementsByTagName("tbody")[0].innerHTML = data.replace(/\\/g, "");
      }
    }
  );
}

window.itemReorder = itemReorder;

function fallbackCopyTextToClipboard(text) {
  var textArea = document.createElement("textarea");
  textArea.value = text;

  // Avoid scrolling to bottom
  textArea.style.top = "0";
  textArea.style.left = "0";
  textArea.style.position = "fixed";

  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();

  try {
    document.execCommand("copy");
  } catch (err) {
    //Do nothing
  }
  document.body.removeChild(textArea);
}

function copyTextToClipboard(text) {
  if (!navigator.clipboard) {
    fallbackCopyTextToClipboard(text);
    return;
  }
  navigator.clipboard.writeText(text);
}

function linkToClipboard(e) {
  var path = getSiteRoot() + e.dataset.title;
  copyTextToClipboard(path);
}

window.linkToClipboard = linkToClipboard;

function getSiteRoot() {
  var rootPath = window.location.protocol + "//" + window.location.host + "/";
  if (window.location.hostname == "localhost") {
    var path = window.location.pathname;
    if (path.indexOf("/") == 0) {
      path = path.substring(1);
    }
    path = path.split("/", 1);
    if (path != "") {
      rootPath = rootPath + path + "/";
    }
  }
  return rootPath;
}

window.getSiteRoot = getSiteRoot;
