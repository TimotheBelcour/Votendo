// Carrousel simple : une image, flèches pour changer, pas d'animation
let games = [];
let current = 0;
let timer = null;

function showGame(idx) {
  if (!games.length) return;
  const container = document.querySelector('.carousel__container');
  let slide = container.querySelector('.carousel__slide');
  if (!slide) {
    slide = document.createElement('div');
    slide.className = 'carousel__slide';
    slide.innerHTML = `<div class="carousel__overlay">
      <h2 class="carousel__title"></h2>
      <p class="carousel__subtitle"></p>
    </div>`;
    container.appendChild(slide);
  }
  slide.style.backgroundImage = `url('${games[idx].img}')`;
  slide.querySelector('.carousel__title').textContent = games[idx].title;
  slide.querySelector('.carousel__subtitle').textContent = games[idx].subtitle;
}

function nextGame() {
  if (!games.length) return;
  current = (current + 1) % games.length;
  showGame(current);
}

function prevGame() {
  if (!games.length) return;
  current = (current - 1 + games.length) % games.length;
  showGame(current);
}

function startCarousel() {
  showGame(current);
  timer = setInterval(nextGame, 4000);
}

function resetCarouselTimer() {
  clearInterval(timer);
  timer = setInterval(nextGame, 4000);
}

document.addEventListener('DOMContentLoaded', () => {
  fetch('../Votendo/jeux_carousel_data.php')
    .then(r => r.json())
    .then(data => {
      games = data;
      if (!games.length) return;
      showGame(current);
      document.querySelector('.carousel__next').addEventListener('click', () => {
        nextGame();
        resetCarouselTimer();
      });
      document.querySelector('.carousel__prev').addEventListener('click', () => {
        prevGame();
        resetCarouselTimer();
      });
      startCarousel();
    });
});

// Les fonctions nextGame et prevGame sont déjà définies plus haut avec renderSlides
// Suppression des doublons et appels à showGame
  timer = setInterval(nextGame, 4000);


function resetCarouselTimer() {
  clearInterval(timer);
  timer = setInterval(nextGame, 4000);
}

document.addEventListener('DOMContentLoaded', () => {
  fetch('../Votendo/jeux_carousel_data.php')
    .then(r => r.json())
    .then(data => {
      games = data;
      if (!games.length) return;
      showGame(current);
      document.querySelector('.carousel__next').addEventListener('click', () => {
        nextGame();
        resetCarouselTimer();
      });
      document.querySelector('.carousel__prev').addEventListener('click', () => {
        prevGame();
        resetCarouselTimer();
      });
      startCarousel();
    });
});
