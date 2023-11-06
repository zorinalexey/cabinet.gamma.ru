/* global user */
import clickOutside from "./modules/lk/clickOutside.js";
import {toggleSidebarPifs} from "./modules/lk/toggleSidebarPifs.js";
import {toggleDialog} from "./modules/lk/togglePopup.js";

(function () {
    const btnContent = document.querySelector(".content-footer");
    const content = document.querySelector(".content-bar");
    const contentClosest = ".content-bar";

    const btnFondList = document.querySelector(".fond-open");
    const fondList = document.querySelector(".fond-list");
    const fondClosest = ".fond-list";

    const btnSubCats = document.querySelectorAll(".fond-item__link");
    const closestCats = ".fond-item__link";

    // fund_bills select

    const paymentType = document?.getElementById("payment_type");

    const paymentTypeSelect = {
        visible: false,
        options: [
            {name: "Test6"},
            {name: "Test7"},
            {name: "Test8"},
            {name: "Test9"},
            {name: "Test10"},
        ],
        node: paymentType,
        styles: {
            reset_width: true,
        },
    };


    // fund btn

    const fundBtn = document.querySelector(".btn-sale"),
        fundBuyBtn = document.querySelector(".btn-buy");


    if (fundBuyBtn) {
        const buyDialog = document?.getElementById("buy_dialog_1"),
            buyBtn1 = document.querySelector("#buy_dialog_1 .buy__dialog-btn"),
            buyWrapper1 = document?.querySelector(
                "#buy_dialog_2 .buy__dialog-action-1"
            ),
            buyWrapper2 = document?.querySelector(
                "#buy_dialog_2 .buy__dialog-action-2"
            ),
            buyBtn2 = document?.querySelector("#buy_dialog_2 .send_code"),
            buyBtn3 = document?.querySelector("#buy_dialog_2 .check_code"),
            buyDialog2 = document?.getElementById("buy_dialog_2"),
            buyBtn4 = document?.querySelector("#buy_dialog_3 .sale__dialog-btn"),
            buyDialog3 = document?.getElementById("buy_dialog_3"),
            buyDialog4 = document?.getElementById("buy_dialog_4"),
            dialogs = document?.querySelectorAll(".buy__dialog");

        fundBuyBtn.addEventListener("click", () => {
            toggleDialog(buyDialog);

            dialogs.forEach((el) => {
                el.onclick = function (evt) {
                    if (
                        evt.target.classList.contains("buy__dialog") ||
                        evt.target.classList.contains("dialog__exit")
                    ) {
                        toggleDialog(this);
                    }
                };
            });
            buyBtn1.onclick = function () {
                toggleDialog(buyDialog);
                toggleDialog(buyDialog2);
            };
            buyBtn2.onclick = function () {
                buyWrapper1.classList.toggle("hidden");
                buyWrapper2.classList.toggle("hidden");
            };
            buyBtn3.onclick = function () {
                toggleDialog(buyDialog2);
                toggleDialog(buyDialog3);
            };
            buyBtn4.onclick = function () {
                toggleDialog(buyDialog3);
                toggleDialog(buyDialog4);
            };
        });
    }

    btnSubCats.forEach((el) => {
        el.addEventListener("click", function () {
            let parent = this.parentNode,
                list = this.parentNode.childNodes[3];
            toggleSidebarPifs(list, parent);
        });
    });

    function sidebarPifsToggle() {
    }

    function openMenu() {
        const btn = document.querySelector(".burger-btn");
        const sidebar = document.querySelector(".sidebar");
        const btnClose = document.querySelector(".btn-close");
        btn.addEventListener("click", (e) => {
            if (e.target.closest(".burger-btn")) {
                sidebar.classList.add("open");
                gsap.to(".content-bar", 0.4, {x: 500});
                btn.classList.add("hidden");
                sidebar.focus();
                sidebar.removeEventListener("focusout", clickOutsideEl, false);
                sidebar.addEventListener("focusout", clickOutsideEl, false);
            }
        });

        btnClose.addEventListener("click", () => {
            sidebar.classList.remove("open");
            gsap.to(".content-bar", 0.3, {x: 0});
            btn.classList.remove("hidden");
        });
    }

    function clickOutsideEl(e) {
        const btnClose = document.querySelector(".btn-close");
        btnClose.click();
    }

    function openClose(btn, item, closest) {
        if (btn) {
            btn.addEventListener("click", (e) => {
                if (e.target.closest(closest)) {
                    item.classList.toggle("open");
                    btn.classList.toggle("open");

                    if (item.classList.contains("notifications-block")) {
                        item.focus();
                        item.addEventListener("focusout", () => {
                            clickOutside.function(btn, item);
                        });
                    } else if (item.classList.contains("search-input")) {
                        item.focus();
                        item.addEventListener("focusout", () => {
                            clickOutside.function(btn, item);
                        });
                    } else {
                        item.parentNode.addEventListener("focusout", () => {
                            clickOutside.function(btn, item);
                        });
                    }
                }
            });
        }
    }

    openClose(btnFondList, fondList, fondClosest);
    openClose(btnContent, content, contentClosest);

    for (let i = 0; i < btnSubCats.length; i++) {
        const fondList = btnSubCats[i].parentNode;
        openClose(btnSubCats[i], fondList, closestCats);
    }
    openMenu();

    function Notifications() {
        const Notification1 = document.querySelector("#notification-1");
        if (Notification1) {
            const Notification2 = document.querySelector("#notification-2");
            const Notification3 = document.querySelector("#notification-3");
            const Notification4 = document.querySelector("#notification-4");
            const Notification5 = document.querySelector("#notification-5");
            const Notification6 = document.querySelector("#notification-6");
            const shadow1 = document.querySelector(".shadow-1");
            const shadow2 = Notification2.parentNode.querySelector(".shadow");
            const shadow3 = Notification3.parentNode.querySelector(".shadow");
            const shadow4 = Notification4.parentNode.querySelector(".shadow");
            const shadow5 = Notification5.parentNode.querySelector(".shadow");
            const shadow5Mobile = document.querySelector(".mobile-shadow");
            const shadow6 = Notification6.parentNode.querySelector(".shadow");
            const circle2 = Notification2.parentNode.querySelector(".circle");
            const circle3 = Notification3.parentNode.querySelector(".circle");
            const circle4 = Notification4.parentNode.querySelector(".circle");
            const circle5 = document.querySelector(".burger-circle");
            const circle6 = Notification6.parentNode.querySelector(".circle");
            const icon2 = Notification2.parentNode.querySelector(".svg-icon");
            const icon3 = Notification3.parentNode.querySelector(".svg-icon");
            const icon4 = Notification4.parentNode.querySelector(".svg-icon");
            const icon5 = circle5.parentNode.querySelector(".svg-icon");
            const icon6 = Notification6.parentNode.querySelector(".svg-icon");
            const btn1 = Notification1.querySelector(".next-step");
            const btn2 = Notification2.querySelector(".next-step");
            const btn3 = Notification3.querySelector(".next-step");
            const btn4 = Notification4.querySelector(".next-step");
            const btn5 = Notification5.querySelector(".next-step");
            const btn6 = Notification6.querySelector(".next-step");
            const sidebar = document.querySelector(".sidebar");
            const panels = document.querySelector(".row-panels");

            let counter = 0;
            Notification1.classList.add("visible");
            shadow1.classList.add("visible");

            let listener = function () {
                if (counter == 0) {
                    Notification1.classList.add("visible");
                    shadow1.classList.add("visible");
                    counter = counter + 1;
                } else {
                    if (counter == 1) {
                        Notification1.classList.remove("visible");
                        shadow1.classList.remove("visible");
                        Notification2.classList.add("visible");
                        shadow2.classList.add("visible");
                        circle2.classList.add("visible");
                        icon2.style.zIndex = 13;
                    }
                    if (counter == 2) {
                        Notification2.classList.remove("visible");
                        shadow2.classList.remove("visible");
                        circle2.classList.remove("visible");
                        Notification3.classList.add("visible");
                        shadow3.classList.add("visible");
                        circle3.classList.add("visible");
                        icon2.style.zIndex = 0;
                        icon3.style.zIndex = 13;
                    }
                    if (counter == 3) {
                        Notification3.classList.remove("visible");
                        shadow3.classList.remove("visible");
                        circle3.classList.remove("visible");
                        Notification4.classList.add("visible");
                        shadow4.classList.add("visible");
                        circle4.classList.add("visible");
                        icon3.style.zIndex = 0;
                        icon4.style.zIndex = 13;
                        panels.classList.add("no-scroll");
                    }
                    if (counter == 4) {
                        Notification4.classList.remove("visible");
                        shadow4.classList.remove("visible");
                        circle4.classList.remove("visible");
                        Notification5.classList.add("visible");
                        circle5.classList.add("visible");
                        shadow5.classList.add("visible");
                        shadow5.style.position = "absolute";
                        shadow5Mobile.classList.add("visible");
                        circle5.classList.add("visible");
                        icon4.style.zIndex = 0;
                        icon5.style.zIndex = 13;
                        panels.classList.remove("no-scroll");
                    }
                    if (counter == 5) {
                        Notification5.classList.remove("visible");
                        circle5.classList.remove("visible");
                        Notification6.classList.add("visible");
                        shadow6.classList.add("visible");
                        circle6.classList.add("visible");
                        sidebar.classList.add("open");
                        icon5.style.zIndex = 0;
                        icon6.style.zIndex = 13;
                    }
                    if (counter == 6) {
                        Notification6.classList.remove("visible");
                        shadow6.classList.remove("visible");
                        circle6.classList.remove("visible");
                        sidebar.classList.remove("open");
                        icon6.style.zIndex = 0;
                        shadow5.classList.remove("visible");
                        shadow5Mobile.classList.remove("visible");
                    }
                }
                counter = counter + 1;
            };

            window.addEventListener("click", listener, false);

            btn1.addEventListener("click", () => {
                Notification1.classList.remove("visible");
                shadow1.classList.remove("visible");
                Notification2.classList.add("visible");
                shadow2.classList.add("visible");
                circle2.classList.add("visible");
                icon2.style.zIndex = 13;
            });
            btn2.addEventListener("click", () => {
                Notification2.classList.remove("visible");
                shadow2.classList.remove("visible");
                circle2.classList.remove("visible");
                Notification3.classList.add("visible");
                shadow3.classList.add("visible");
                circle3.classList.add("visible");
                icon2.style.zIndex = 0;
                icon3.style.zIndex = 13;
            });
            btn3.addEventListener("click", () => {
                Notification3.classList.remove("visible");
                shadow3.classList.remove("visible");
                circle3.classList.remove("visible");
                Notification4.classList.add("visible");
                shadow4.classList.add("visible");
                circle4.classList.add("visible");
                icon3.style.zIndex = 0;
                icon4.style.zIndex = 13;
                panels.classList.add("no-scroll");
            });
            btn4.addEventListener("click", () => {
                Notification4.classList.remove("visible");
                shadow4.classList.remove("visible");
                circle4.classList.remove("visible");
                Notification5.classList.add("visible");
                circle5.classList.add("visible");
                shadow5.classList.add("visible");
                shadow5.style.position = "absolute";
                shadow5Mobile.classList.add("visible");
                circle5.classList.add("visible");
                icon4.style.zIndex = 0;
                icon5.style.zIndex = 13;
                panels.classList.remove("no-scroll");
            });
            btn5.addEventListener("click", () => {
                Notification5.classList.remove("visible");
                circle5.classList.remove("visible");
                Notification6.classList.add("visible");
                shadow6.classList.add("visible");
                circle6.classList.add("visible");
                sidebar.classList.add("open");
                icon5.style.zIndex = 0;
                icon6.style.zIndex = 13;
            });
            btn6.addEventListener("click", () => {
                Notification6.classList.remove("visible");
                shadow6.classList.remove("visible");
                circle6.classList.remove("visible");
                sidebar.classList.remove("open");
                icon6.style.zIndex = 0;
                shadow5.classList.remove("visible");
                shadow5Mobile.classList.remove("visible");
            });
        }
    }

    //  if (user.JsNotic) {
    //    Notifications();
    //  }

    const btnShowNotifications = document.querySelector(
        ".notifications.icon .svg-icon"
    );
    const notificationsBlock = document.querySelector(".notifications-block ");
    const closestClass = ".notifications.icon";

    openClose(btnShowNotifications, notificationsBlock, closestClass);

    const searchBtn = document.querySelector(".search.icon .svg-icon");
    const searchBlock = document.querySelector(".search-input");
    const searchClosest = ".search.icon svg";

    openClose(searchBtn, searchBlock, searchClosest);

    const openActionsBtn = document.querySelector(".open-actions");
    const openActionsBlock = document.querySelector(".actions-line");
    const closestActions = ".open-actions";

    openClose(openActionsBtn, openActionsBlock, closestActions);
})();
