const previews = {
  sessions: {
    image: "./assets/sessions.png",
    alt: "AI Coding Usage sessions in light and dark themes, with search, project filters, tokens, and estimated costs",
  },
  overview: {
    image: "./assets/overview.png",
    alt: "Usage overview showing estimated API-equivalent cost, recorded tokens, and daily usage by provider",
  },
  menubar: {
    image: "./assets/menubar.png",
    alt: "Menu bar popover showing today's estimated costs, token usage, and recent sessions",
  },
};

const switcher = document.querySelector(".view-switcher");
const previewImage = document.querySelector("#product-image");
const stage = document.querySelector(".screenshot-stage");

switcher.hidden = false;
switcher.addEventListener("click", (event) => {
  const button = event.target.closest("button[data-view]");
  if (!button) return;
  const view = previews[button.dataset.view];
  if (!view) return;
  switcher.querySelectorAll("button").forEach((item) => {
    item.setAttribute("aria-pressed", String(item === button));
  });
  previewImage.src = view.image;
  previewImage.alt = view.alt;
  stage.dataset.view = button.dataset.view;
});

// Open the installation details before navigating to their anchor.
function revealFirstLaunch() {
  if (window.location.hash === "#first-launch") {
    document.querySelector("#first-launch").open = true;
  }
}
window.addEventListener("hashchange", revealFirstLaunch);
revealFirstLaunch();
