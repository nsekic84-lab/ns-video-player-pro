document.addEventListener("DOMContentLoaded", function(){

    const players = document.querySelectorAll('.ns-video-container video');

    players.forEach(video => {

        // Lazy load video
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if(entry.isIntersecting){
                    if(video.dataset.src){
                        video.src = video.dataset.src;
                        video.load();
                    }
                    observer.unobserve(video);
                }
            });
        }, { threshold: 0.25 });

        observer.observe(video);

        // Watermark fade-in/fade-out
        const container = video.parentElement;
        const watermark = container.querySelector('.ns-watermark');
        if(watermark){
            watermark.style.opacity = 0;
            video.addEventListener('play', () => { watermark.style.transition = 'opacity 0.5s'; watermark.style.opacity = 1; });
            video.addEventListener('pause', () => { watermark.style.opacity = 0; });
            video.addEventListener('ended', () => { watermark.style.opacity = 0; });
        }
    });
});
