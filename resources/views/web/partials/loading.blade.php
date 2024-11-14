<div id="loading-animation" style="display: none;">
    <div id="lottie-loading"></div>
</div>

<style>
    #loading-animation {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(255, 255, 255, 0.9); /* Slight transparency */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    /* Centered box for the Lottie animation */
    #lottie-loading {
        display: flex;
        align-items: center;
        width: 100%;
        height: 300px;
        background-color: transparent;
        border-radius: 8px;
        margin: auto;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.4/lottie.min.js"></script>
<script>
    let loadingAnimation;

    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lottie !== 'undefined') {
            loadingAnimation = lottie.loadAnimation({
                container: document.getElementById('lottie-loading'),
                renderer: 'svg',
                loop: true,
                autoplay: false, // Controlled manually
                path: 'assets/json/loading.json'
            });
        } else {
            console.error("Lottie library failed to load.");
        }
    });

    function showLoading() {
        document.getElementById('loading-animation').style.display = 'flex';
        if (loadingAnimation) loadingAnimation.play();
    }

    function hideLoading() {
        document.getElementById('loading-animation').style.display = 'none';
        if (loadingAnimation) loadingAnimation.stop();
    }
</script>
