document.addEventListener('DOMContentLoaded', function() {
    const books = document.querySelectorAll('.book');
    const modal = document.querySelector('.modal');
    const modalContent = document.querySelector('.modal-content');
    const closeModal = document.querySelector('.close-modal');

    books.forEach(book => {
        book.addEventListener('click', function() {
            const title = this.querySelector('h2').innerText;
            const description = this.querySelector('p').innerText;
            const imageSrc = this.querySelector('img').src;
            const year = this.querySelector('.publication-year').innerText;

            modalContent.querySelector('.modal-title').innerText = title;
            modalContent.querySelector('.modal-description').innerText = description;
            modalContent.querySelector('.modal-image').src = imageSrc;
            modalContent.querySelector('.modal-year').innerText = year;

            modal.style.display = 'block';
        });
    });

    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const text = document.querySelector('.typing-effect');
    const strText = text.textContent;
    const splitText = strText.split('');
    text.textContent = '';
    for (let i = 0; i < splitText.length; i++) {
        text.innerHTML += '<span>' + splitText[i] + '</span>';
    }

    let char = 0;
    let timer = setInterval(onTick, 50);

    function onTick() {
        const span = text.querySelectorAll('span')[char];
        span.classList.add('fade');
        char++;
        if (char === splitText.length) {
            complete();
            return;
        }
    }

    function complete() {
        clearInterval(timer);
        timer = null;
    }
});
