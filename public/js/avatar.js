function showPreview(event){
    if(event.target.files.length > 0){
        var src = URL.createObjectURL(event.target.files[0]);
        var preview = document.getElementById("img-avatar");
        preview.src = src;

    }
}

const  postFile = document.getElementById('profil_file');

if (postFile){
    postFile.addEventListener('change',showPreview)
    console.log('event')
}
