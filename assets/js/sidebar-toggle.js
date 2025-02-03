document.addEventListener("DOMContentLoaded", function () {
  let sidebar = document.querySelector(".sidenav-menu");
  let toggleButton = document.querySelector(".sidenav-toggle-button");
  let closeButton = document.querySelector(".sidenav-close");

  // Sidebar Toggle Button
  if (toggleButton) {
    toggleButton.addEventListener("click", function () {
      sidebar.classList.toggle("active");
    });
  }

  // Sidebar Close Button
  if (closeButton) {
    closeButton.addEventListener("click", function () {
      sidebar.classList.remove("active");
    });
  }

  // Handle dropdown toggles
  document
    .querySelectorAll(".side-nav-item a[data-bs-toggle='collapse']")
    .forEach((link) => {
      link.addEventListener("click", function () {
        let icon = this.querySelector(".fa-chevron-down");
        setTimeout(() => {
          icon.classList.toggle(
            "rotate",
            this.nextElementSibling.classList.contains("show")
          );
        }, 300);
      });
    });
});
