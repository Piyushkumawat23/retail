// Get the model image and earring elements
const modelImage = document.getElementById('modelImage');
const leftEarring = document.getElementById('leftEarring');
const rightEarring = document.getElementById('rightEarring');

// Get the image gallery container
const imageGallery = document.getElementById('image-gallery');

// Populate the image gallery with sample images (replace with your own images)
const images = [
    ];

images.forEach((image) => {
    const img = document.createElement('img');
    img.src = image;
    img.alt = image;
    imageGallery.appendChild(img);
});

// Add click event listeners to gallery images
imageGallery.addEventListener('click', (event) => {
    if (event.target.tagName === 'IMG') {
        const selectedImageURL = event.target.src;
        updateEarringImage(selectedImageURL);
    }
});

// Function to update the earring image
function updateEarringImage(imageURL) {
    leftEarring.style.backgroundImage = `url(${imageURL})`;
    rightEarring.style.backgroundImage = `url(${imageURL})`;
}

const skinToneSlider = document.getElementById('skinToneSlider');

skinToneSlider.addEventListener('input', (e) => {
  const brightness = e.target.value / 100; // Map slider value to 0.5-2 range
  modelImage.style.filter = `brightness(${brightness})`;
});
