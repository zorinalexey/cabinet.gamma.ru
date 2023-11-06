async function renderOptions({node, options, headVal, styles}) {
    let promise = new Promise((resolve, reject) => {
        for (const option of options) {
            node.insertAdjacentHTML(
                "afterbegin",
                `
        <p class="g-select__option">
          ${option.name}
        </p>
        `
            );
        }
        resolve(node);
    }).then((node) => {
        let tl = gsap;
        node.childNodes.forEach((el) => {
            el.addEventListener("click", () => {
                tl.to(headVal, 0.3, {opacity: "0"})
                    .then(() => {
                        headVal.value = el.innerText;
                    })
                    .then(() => {
                        tl.to(headVal, 0.3, {opacity: "1"});
                    });
            });
        });
    });
}

export {renderOptions};
