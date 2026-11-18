const radioBtns = document.querySelectorAll('input[name="category"]');
const oldImgDiv = document.querySelector('.old-images');
const loader = document.querySelector('.loader');
changePhotos('');

radioBtns.forEach((btn) =>{
   btn.addEventListener('change', ()=>{
       changePhotos(btn.value);
   }) ;
});

function changePhotos(value){
    loader.classList.remove('hidden');
    oldImgDiv.innerHTML = "";
    fetch(window.location.href + 'api/get-img.php?id=' + value)
        .then(data => data.json())
        .then(jsonData => createImage(jsonData));
}

function createImage(images){

    images.forEach(image => {
        let imgElement = null;
       if (image.name.includes('mp4')){
           imgElement = document.createElement('video');
           const srcElement = document.createElement('source');
           srcElement.src = 'img/old-img/' + image.name;
           imgElement.appendChild(srcElement);
           imgElement.controls = true;
       }else {
           imgElement = document.createElement('img');
           imgElement.src = 'img/old-img/' + image.name;
       }

        imgElement.classList.add('img-fluid');
        imgElement.classList.add('mb-2');
        oldImgDiv.appendChild(imgElement);
    });
    loader.classList.add('hidden');
}