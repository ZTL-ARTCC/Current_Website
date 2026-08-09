import {
  ClassicEditor,
  Essentials,
  Bold,
  Italic,
  BlockQuote,
  Heading,
  HorizontalLine,
  Image,
  ImageUpload,
  Indent,
  Link,
  MediaEmbed,
  Table,
  FileRepository,
  List,
} from "ckeditor5";

const ckeditorSelector = ".text-editor";
window.isImageUploading = false;

class UploadAdapter {
  constructor(loader) {
    this.loader = loader;
  }

  upload() {
    window.isCkeditorUploading = true;
    document
      .querySelectorAll('button[type="submit"], .save, .draft')
      .forEach((btn) => (btn.disabled = true));

    return this.loader.file.then(
      (file) =>
        new Promise((resolve, reject) => {
          this._initRequest();
          this._initListeners(resolve, reject, file);
          this._sendRequest(file);
        })
    );
  }

  abort() {
    if (this.xhr) {
      this.xhr.abort();
    }
  }

  _initRequest() {
    const xhr = (this.xhr = new XMLHttpRequest());
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");

    xhr.open("POST", "/dashboard/training/upload-image", true);
    xhr.responseType = "json";
    xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
  }

  _initListeners(resolve, reject, file) {
    const xhr = this.xhr;
    const loader = this.loader;
    const genericErrorText = `Couldn't upload file: ${file.name}.`;

    xhr.addEventListener("error", () => {
      window.isCkeditorUploading = false;
      document
        .querySelectorAll('button[type="submit"], .save, .draft')
        .forEach((btn) => (btn.disabled = false));

      reject(genericErrorText);
    });
    xhr.addEventListener("abort", () => {
      window.isCkeditorUploading = false;
      document
        .querySelectorAll('button[type="submit"], .save, .draft')
        .forEach((btn) => (btn.disabled = false));

      reject();
    });
    xhr.addEventListener("load", () => {
      const response = xhr.response;

      window.isCkeditorUploading = false;
      document
        .querySelectorAll('button[type="submit"], .save, .draft')
        .forEach((btn) => (btn.disabled = false));

      if (!response || response.error) {
        return reject(
          response && response.error ? response.error.message : genericErrorText
        );
      }

      resolve({
        default: response.url,
      });
    });

    if (xhr.upload) {
      xhr.upload.addEventListener("progress", (evt) => {
        if (evt.lengthComputable) {
          loader.uploadTotal = evt.total;
          loader.uploaded = evt.loaded;
        }
      });
    }
  }

  _sendRequest(file) {
    const data = new FormData();

    data.append("upload", file);

    this.xhr.send(data);
  }
}

function UploadAdapterPlugin(editor) {
  editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
    return new UploadAdapter(loader);
  };
}

const plugins = [
  Essentials,
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
  FileRepository,
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

const restrictedPlugins = [
  Essentials,
  Bold,
  Italic,
  BlockQuote,
  Heading,
  HorizontalLine,
  Indent,
  Link,
  List,
  MediaEmbed,
  Table,
];

const restrictedToolbar = [
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
  "blockQuote",
  "insertTable",
  "mediaEmbed",
  "undo",
  "redo",
];

var editors = {};
document.addEventListener("DOMContentLoaded", () => {
  const editorElements = document.querySelectorAll(ckeditorSelector);

  editorElements.forEach((editorElement) => {
    const isTrainerComments = editorElement.id === "trainer_comments";

    ClassicEditor.create(editorElement, {
      plugins: isTrainerComments ? restrictedPlugins : plugins,
      extraPlugins: isTrainerComments ? [] : [UploadAdapterPlugin],
      toolbar: isTrainerComments ? restrictedToolbar : toolbar,
      licenseKey: "GPL",
    }).then((editorInstance) => {
      editors[editorElement.id] = editorInstance;
    });
  });
});

window.editors = editors;
