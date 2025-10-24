import "./copy_to_clipboard.js";

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
    case 8:
      dType = "marketing";
      break;
  }

  fetch(
    `/dashboard/admin/files/disp-order?id=${id}&pos=${pos}&act=${act}&typ=${typ}`
  )
    .then((response) => response.text())
    .then((data) => {
      if (data.length > 0) {
        const tbody = document
          .getElementById(dType)
          .getElementsByTagName("tbody")[0];
        tbody.innerHTML = data.replace(/\\/g, "");
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
}

window.itemReorder = itemReorder;

function linkToClipboard(e) {
  const path = getSiteRoot() + e.dataset.bsTitle;
  window.copyTextToClipboard(path);
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
