document.addEventListener('DOMContentLoaded', (event) => {
    console.log('Hello')

    const marquees = document.querySelectorAll('.wp-block-acf-marquee__inner');
    marquees.forEach(marquee => {
        let originalContent = marquee.innerHTML;
        for (let i = 0; i < 9; i++) {
            marquee.innerHTML += originalContent;
        }

        let scrollPos = 0;
        setInterval(() => {
            scrollPos++;
            if (scrollPos >= marquee.offsetWidth) {
                scrollPos = 0;
            }
            marquee.style.transform = `translateX(-${scrollPos}px)`;
        }, 30);
    });
});