/****************************** script pour avoir une preview de l'image lors de l'upload ***************************/
document.addEventListener("DOMContentLoaded", () => {
  function showPreview(event) {
    if (event.target.files.length > 0) {
      var src = URL.createObjectURL(event.target.files[0]);
      var preview = document.getElementById("file-preview");
      preview.src = src;
      preview.style.display = "block";
    }
  }

  const postFile = document.getElementById("post_file");

  if (postFile) {
    postFile.addEventListener("change", showPreview);
  }

 
});
