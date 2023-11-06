(function () {
    const popupBtns = document.querySelectorAll('.popup-btn');
    const popup = document.querySelectorAll('.popup-wrapper');
    popupBtns.forEach((e) => {
        e.addEventListener('click', (e) => {
            if (e.target.closest('.popup-btn')) {
                const popup = document.querySelector(e.target.closest('.popup-btn').getAttribute('data-toggle'));
                popup.classList.add('active');
            }
        })
    })
    popup.forEach((e) => {
        e.addEventListener('click', (evt) => {
            if (evt.target.classList.contains('popup-wrapper')) {
                e.classList.remove('active')
            }
        })
    })
})();