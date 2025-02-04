document.addEventListener("DOMContentLoaded", function () {
  const sidenavToggleButton = document.querySelector(".sidenav-toggle-button");
  const sidenavMenu = document.querySelector(".sidenav-menu");
  const buttonCloseFullsidebar = document.querySelector(
    ".button-close-fullsidebar"
  );

  if (sidenavToggleButton && sidenavMenu) {
    sidenavToggleButton.addEventListener("click", function () {
      sidenavMenu.classList.toggle("active");
    });
  }

  if (buttonCloseFullsidebar && sidenavMenu) {
    buttonCloseFullsidebar.addEventListener("click", function () {
      sidenavMenu.classList.remove("active");
    });
  }
});
