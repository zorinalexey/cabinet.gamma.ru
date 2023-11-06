import { pifsAnimate } from "../animations/sidebarPifs.js";

function toggleSidebarPifs(list, parent) {
  if (
    list.classList.contains("reports-list") &&
    !parent.classList.contains("open")
  )
    pifsAnimate(list, "play");
  else pifsAnimate(list, "reverse");
}

export { toggleSidebarPifs };
