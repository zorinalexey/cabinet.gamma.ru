import { renderOptions } from "./renderOptions.js";

function toggleSelect() {
  let body = this.settings.node.children[1];
  let headVal = "",
    icon = "";
  for (let item of this.settings.node.children[0].children) {
    if (item.tagName == "INPUT") {
      headVal = item;
    }
    if (item.classList.contains("g-select__toggle")) {
      icon = item;
    }
  }

  this.settings.node.focus();
  let tl = gsap.timeline();
  let tl2 = gsap.timeline();

  this.settings.node.addEventListener("focusout", () => {
    tl.to(body, 0.4, { height: "0px" });
    tl2.to(icon, 0.4, { rotate: "0deg" });
    return;
  });

  if (this.settings.visible) {
    this.settings.visible = false;
    tl.to(body, 0.4, { height: "0px" });
    tl2.to(icon, 0.4, { rotate: "0deg" });
    return;
  }

  if (!body.childNodes.length) {
    let payload = {
      node: body,
      options: this.settings.options,
      headVal: headVal,
      styles: this.settings.styles,
    };
    renderOptions(payload);
    tl.to(body, 0.4, { height: "auto" });
    tl2.to(icon, 0.4, { rotate: "180deg" });
    this.settings.visible = true;
    console.log("that");
    return;
  }

  if (body.childNodes.length && !this.settings.visible) {
    tl.to(body, 0.4, { height: "auto" });
    tl2.to(icon, 0.4, { rotate: "180deg" });
    this.settings.visible = true;
    return;
  }
}

export { toggleSelect };
