const stars = document.querySelectorAll("#stars span");
const ratingInput = document.querySelector(
  "#stars input[name='service_level']"
);
const rating = ratingInput.value;

// Needed so if you submit and get redirected back the stars will still be highlighted
if (rating) {
  for (let i = 0; i < rating; i++) {
    stars[i].textContent = "\u2605";
  }
}

stars.forEach((star, i) => {
  // Hover in
  star.addEventListener("mouseenter", () => {
    for (let j = 0; j <= i; j++) {
      stars[j].classList.add("star-hover");
    }
  });

  // Hover out
  star.addEventListener("mouseleave", () => {
    for (let j = 0; j <= i; j++) {
      stars[j].classList.remove("star-hover");
    }
  });

  // Click to set rating
  star.addEventListener("click", () => {
    stars.forEach((s) => (s.textContent = "\u2606")); // empty star
    for (let j = 0; j <= i; j++) {
      stars[j].textContent = "\u2605"; // filled star
    }
    ratingInput.value = star.dataset.rating;
  });
});
