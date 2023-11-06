(function () {
    const newsLinks = document.querySelectorAll('.news-item__link');
    const newsBody = document.querySelectorAll('.news-item__content');
    newsLinks.forEach((e) => {
        e.addEventListener('click', (e) => {
            newsBody.forEach((e) => {
                e.classList.add('d-none');
            })
            const elemToShow = e.target.parentNode.querySelector('.news-item__content');
            elemToShow.classList.toggle('d-none');
        })
    })
})();