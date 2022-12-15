export function copyToClipboard(eId) {
  let e = document.getElementById(eId);
  navigator.clipboard.writeText(e.innerHTML);
}
