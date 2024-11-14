export const popupClose = (duration = 500) => {
    const root = document.getElementById("jlt-power-user-root-custom");

    setTimeout(() => {
        root.style.display = "none";
    }, duration);
};
