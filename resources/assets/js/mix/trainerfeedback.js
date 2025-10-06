const stars = document.querySelectorAll("#stars span");
const ratingInput = document.querySelector(
  "#stars input[name='service_level']"
);
const rating = ratingInput.value;

if (rating) {
  for (let i = 0; i < rating; i++) {
    stars[i].textContent = "★";
  }
}

stars.forEach((star, index) => {
  star.addEventListener("mouseenter", () => {
    for (let i = 0; i <= index; i++) {
      stars[i].classList.add("star-hover");
    }
  });

  star.addEventListener("mouseleave", () => {
    for (let i = 0; i <= index; i++) {
      stars[i].classList.remove("star-hover");
    }
  });

  star.addEventListener("click", () => {
    stars.forEach((s) => (s.textContent = "☆"));
    for (let i = 0; i <= index; i++) {
      stars[i].textContent = "★";
    }
    ratingInput.value = star.dataset.rating;
  });
});
