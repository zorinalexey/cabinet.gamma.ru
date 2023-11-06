import {renderOptions} from "./renderOptions.js";

const select2 = document.querySelector("#select2");
const select = document.querySelector("#select1");

const select1Settings = {
    visible: false,
    options: [{name: "Мужской"}, {name: "Женский"}],
    node: select,
    styles: {},
};

const select2Settings = {
    visible: false,
    options: [
        {name: "Заработная плата"},
        {
            name: "Доход от осуществления предпринимательской деятельности и (или) от участия в уставном (складочном) капитале коммерческой организации",
        },
        {name: "Получение наследства"},
        {name: "Получение активов по договору дарения"},
        {
            name: "Доход от операций с ценными бумагами и (или) иностранными финансовыми инструментами, неквалифицированными в соответствии с законодательством РФ в качестве ценных бумаг",
        },
        {name: "Доход от операций с производными финансовыми инструментами"},
        {
            name: "Доход от операций с иностранной валютой на организованных торгах и (или) на внебиржевом рынке (Forex)",
        },
    ],
    node: select2,
    styles: {
        reset_width: true,
    },
};

function toggleSelect() {
    let headVal = this.settings.node.childNodes[1].childNodes[1],
        body = this.settings.node.childNodes[3],
        icon = headVal.parentNode.childNodes[3];

    this.settings.node.focus();
    let tl = gsap.timeline();
    let tl2 = gsap.timeline();

    this.settings.node.addEventListener("focusout", () => {
        tl.to(body, 0.4, {height: "0px"});
        tl2.to(icon, 0.4, {rotate: "0deg"});
        return;
    });

    if (!body.childNodes.length) {
        let payload = {
            node: body,
            options: this.settings.options,
            headVal: headVal,
            styles: this.settings.styles,
        };
        renderOptions(payload);
        tl.to(body, 0.4, {height: "auto"});
        tl2.to(icon, 0.4, {rotate: "180deg"});
        return;
    }

    if (body.childNodes.length) {
        tl.to(body, 0.4, {height: "auto"});
        tl2.to(icon, 0.4, {rotate: "180deg"});
        return;
    }
}

select.addEventListener("click", {
    handleEvent: toggleSelect,
    settings: select1Settings,
});
select2.addEventListener("click", {
    handleEvent: toggleSelect,
    settings: select2Settings,
});
