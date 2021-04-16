const hamburger = document.querySelector(".hamburger");
const sideNav = document.querySelector(".side-nav");
const wrapper = document.querySelector(".wrapper");
const navbar = document.querySelector("#nav");
const logoDark = document.querySelector("#logo");
let currentUrl = window.location.href;

// loader
// $(window).load(function () {
//   $(".loader").fadeOut("slow");
// });
// hamburger menu
hamburger.addEventListener("click", () => {
  //   wrapper.classList.toggle("active");
  sideNav.classList.toggle("active");
  if (hamburger.innerHTML !== `<i class="fas fa-times"></i>`) {
    hamburger.innerHTML = `<i class="fas fa-times"></i>`;
  } else {
    hamburger.innerHTML = `<i class="fas fa-bars"></i>`;
  }
});

// back to top button
let btn = $("#button");

$(window).scroll(function () {
  if ($(window).scrollTop() > 300) {
    btn.addClass("show");
  } else {
    btn.removeClass("show");
  }
});

btn.on("click", function (e) {
  e.preventDefault();
  $("html, body").animate({ scrollTop: 0 }, "300");
});

// fixed navbar
if (navbar) {
  window.addEventListener("scroll", fixNav);
}

function fixNav() {
  if (window.scrollY > navbar.offsetHeight + 200) {
    navbar.classList.add("sticky");
  } else {
    navbar.classList.remove("sticky");
  }
}

// select only two in multiple select in select option

$(document).ready(function () {
  var last_valid_selection = null;

  $("#comaker_id").change(function (event) {
    if ($(this).val().length > 2) {
      $(this).val(last_valid_selection);
    } else {
      last_valid_selection = $(this).val();
    }
  });
});

$(".selecte").select2({
  maximumSelectionLength: 2,
});

$("form").on("submit", function () {
  var minimum = 2;

  if ($(".selecte").select2("data").length >= minimum) {
    return true;
  } else {
    alert("Please choose at least " + minimum + " comaker(s)");
    return false;
  }
});
