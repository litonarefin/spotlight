import App from "./App";
import { createRoot } from "@wordpress/element";

const newDiv = document.createElement("div");
newDiv.id = "jlt-power-user-root-custom";
newDiv.style.display = "none";

document.body.appendChild(newDiv);

document.addEventListener("keydown", function (e) {

    if (e.key == "s") {
        newDiv.style.display = "block";
    }

    if (e.key == "Escape") {
      newDiv.style.display = "none";
    }

});

waitForElm("#jlt-power-user-root-custom").then((elm) => {
    const root = document.getElementById("jlt-power-user-root-custom");
    createRoot(root).render(<App />);
});



function waitForElm(selector) {
  return new Promise((resolve) => {
    if (document.querySelector(selector)) {
      return resolve(document.querySelector(selector));
    }

    const observer = new MutationObserver((mutations) => {
      if (document.querySelector(selector)) {
        observer.disconnect();
        resolve(document.querySelector(selector));
      }
    });

    // If you get "parameter 1 is not of type 'Node'" error, see https://stackoverflow.com/a/77855838/492336
    observer.observe(document.body, {
      childList: true,
      subtree: true,
    });
  });
}
