function pifsAnimate(list, condition) {
  let tl = gsap.timeline();

  if (condition == "play")
    tl.to(list, 0.4, { height: "auto", ease: Power1.easeOut });
  if (condition == "reverse")
    tl.to(list, 0.4, { height: "0", ease: Power1.easeOut });
}

export { pifsAnimate };
