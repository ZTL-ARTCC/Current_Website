const ckeditorSelector = ".text-editor";

$(ckeditorSelector).ready(() => {
  $(ckeditorSelector).each((_idx, editor) => {
    require("@ckeditor/ckeditor5-build-classic").create(editor);
  });
});
