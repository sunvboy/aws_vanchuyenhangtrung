/*js home slider banner*/

$("#slider-home").owlCarousel({
  loop: true,
  margin: 30,
  dots: false,
  nav: false,
  autoplay: true,
  autoplayTimeout: 5000,
  autoplaySpeed: 1500,
  items: 1,
});
$("#slider-client").owlCarousel({
  loop: true,
  margin: 30,
  dots: false,
  nav: false,
  autoplay: true,
  autoplayTimeout: 5000,
  autoplaySpeed: 1500,
  responsive: {
    0: {
      items: 1,
    },
    600: {
      items: 2,
    },
    1000: {
      items: 2,
    },
  },
});
$("#slider-news").owlCarousel({
  loop: true,
  margin: 30,
  dots: false,
  nav: false,
  autoplay: true,
  autoplayTimeout: 5000,
  autoplaySpeed: 1500,
  responsive: {
    0: {
      items: 1,
    },
    600: {
      items: 2,
    },
    1000: {
      items: 3,
    },
  },
});
$(document).on("click", "#dropdownAvatarNameButton", function (e) {
  $("#dropdownAvatarName").toggleClass("hidden");
});
