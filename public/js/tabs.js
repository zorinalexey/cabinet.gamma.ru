(function () {
    const tabs = document.querySelector('.tabs');
    const tabsBtns = document.querySelectorAll('.tab-item');
    const tabsContent = document.querySelectorAll('.tab-content');
    tabs.addEventListener('click', (e) => {
        let target = e.target;
        if (target.classList.contains('tab-item')) {
            for (let i = 0; i < tabsBtns.length; i++) {
                tabsBtns[i].classList.remove('active');
            }
            for (let i = 0; i < tabsContent.length; i++) {
                tabsContent[i].classList.remove('active');
            }
            const id = '#tab-content' + target.getAttribute('id');
            const tab = document.querySelector(id);
            target.classList.add('active');
            tab.classList.add('active');
        }
    });
})();