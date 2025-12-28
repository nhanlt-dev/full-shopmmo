function isElementInViewport(el) {
  if (!el.length) return false;
  var rect = el[0].getBoundingClientRect();
  return rect.top < window.innerHeight && rect.bottom >= 0;
}

function updateOdometer(selector, value) {
  let element = document.querySelector(selector);
  if (element && element.innerHTML != value) {
    element.innerHTML = value;
  }
}

$(document).ready(function () {
  function startOdometerWhenVisible() {
    if (
      $(".odometer").length > 0 &&
      isElementInViewport($(".odometer.style-1"))
    ) {
      updateOdometer(".style-1-1", 26);
      updateOdometer(".style-1-2", 4130);
      updateOdometer(".style-1-3", 98);
      updateOdometer(".style-1-4", 1780);
    }
    if (
      $(".odometer.style-2").length > 0 &&
      isElementInViewport($(".odometer.style-2"))
    ) {
      updateOdometer(".style-2-1", 999);
      updateOdometer(".style-2-2", 512);
      updateOdometer(".style-2-3", 3);
    }
  }

  $(window).on("scroll", startOdometerWhenVisible);
  startOdometerWhenVisible();
});
