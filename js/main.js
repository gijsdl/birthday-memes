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
       const imgElement = document.createElement('img');
       imgElement.src = 'img/old-img/' + image.name;
       imgElement.classList.add('img-fluid');
       oldImgDiv.appendChild(imgElement);
    });
    loader.classList.add('hidden');
}