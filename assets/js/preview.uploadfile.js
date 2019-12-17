function handleFileSelect(evt) {
    var files = evt.target.files;
    for (var i = 0, f; f = files[i]; i++) {
        if (!f.type.match('image.*')) {
            continue;
        }

        var reader = new FileReader();
        reader.onload = (function () {
            return function (e) {
                var previews = document.getElementsByClassName('upload-avatar-preview');
                for (var j = 0; j < previews.length; j++) {
                    previews[j].src = e.target.result;
                }
            };
        })(f);
        reader.readAsDataURL(f);
    }
}

var cmpUploadAvatars = document.getElementsByClassName('upload-avatar');
for (var i = 0; i < cmpUploadAvatars.length; i++) {
    cmpUploadAvatars[i].addEventListener('change', handleFileSelect, false);
}
