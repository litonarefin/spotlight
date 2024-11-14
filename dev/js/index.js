import App from "./App";
import { createRoot } from "@wordpress/element";

const newDiv = document.createElement("div");
newDiv.id = "jlt-power-user-root-custom";
newDiv.style.display = "none";

document.body.appendChild(newDiv);

document.addEventListener("keydown", function (e) {
    if (e.ctrlKey && e.key == "k") {
        newDiv.style.display = "block";
    }
});

const root = document.getElementById("jlt-power-user-root-custom");
createRoot(root).render(<App />);
