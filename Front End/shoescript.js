// Optional control
const model = document.querySelector("model-viewer");

// Press "S" to stop/start rotation
let rotating = true;

document.addEventListener("keydown", (e) => {
  if (e.key === "s") {
    rotating = !rotating;
    model.autoRotate = rotating;
  }
});