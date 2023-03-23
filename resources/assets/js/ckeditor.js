import { ClassicEditor } from "@ckeditor/ckeditor5-editor-classic";
import { Essentials } from "@ckeditor/ckeditor5-essentials";
import { UploadAdapter } from "@ckeditor/ckeditor5-adapter-ckfinder";
import { Bold, Italic } from "@ckeditor/ckeditor5-basic-styles";
import { BlockQuote } from "@ckeditor/ckeditor5-block-quote";
import { Heading } from "@ckeditor/ckeditor5-heading";
import { HorizontalLine } from "@ckeditor/ckeditor5-horizontal-line";
import { Image, ImageUpload } from "@ckeditor/ckeditor5-image";
import { Indent } from "@ckeditor/ckeditor5-indent";
import { Link } from "@ckeditor/ckeditor5-link";
import { List } from "@ckeditor/ckeditor5-list";
import { MediaEmbed } from "@ckeditor/ckeditor5-media-embed";
import { Table } from "@ckeditor/ckeditor5-table";

const ckeditorSelector = ".text-editor";

const plugins = [
  Essentials,
  UploadAdapter,
  Bold,
  Italic,
  BlockQuote,
  Heading,
  HorizontalLine,
  Image,
  ImageUpload,
  Indent,
  Link,
  List,
  MediaEmbed,
  Table,
];

const toolbar = [
  "heading",
  "|",
  "bold",
  "italic",
  "link",
  "bulletedList",
  "numberedList",
  "|",
  "outdent",
  "indent",
  "horizontalLine",
  "|",
  "uploadImage",
  "blockQuote",
  "insertTable",
  "mediaEmbed",
  "undo",
  "redo",
];

$(ckeditorSelector).ready(() => {
  $(ckeditorSelector).each((_idx, editor) => {
    ClassicEditor.create(editor, {
      plugins,
      toolbar,
    });
  });
});
