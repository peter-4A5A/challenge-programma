let quill = new Quill('#editor', {
  theme: 'snow'
});
let form = document.querySelector(".cms-form");
form.addEventListener('submit', () => {
  let pageContentInput = document.querySelector("input[name=page_content]");
  pageContentInput.value = document.querySelector(".ql-editor").innerHTML;
});
