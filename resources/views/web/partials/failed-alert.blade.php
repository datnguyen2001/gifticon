<style>
    .failed-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9998;
    }
    .failed-alert {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        width: 25%;
        height: 300px;
    }

    .close-popup {
        position: absolute;
        top: 10px;
        right: 15px;
        cursor: pointer;
        font-size: 18px;
        color: #333;
    }

    .failed-image {
        width: 200px;
        margin-bottom: 15px;
    }

    .failed-alert p {
        font-size: 20px;
        color: #333;
        font-weight: 600;
        text-align: center;
        margin: 0;
    }
    @media screen and (max-width: 1200px) {
        .failed-alert {
            width: 50%;
        }
    }
    @media screen and (max-width: 768px) {
        .failed-alert {
            width: 75%;
        }
    }
    @media screen and (max-width: 420px) {
        .failed-alert {
            width: 100%;
        }
    }
</style>

<div class="failed-overlay" style="display: none"></div>
<div class="failed-alert" style="display: none">
    <div class="close-popup">
        <i class="fa fa-times" aria-hidden="true"></i>
    </div>
    <img src="{{ asset('assets/images/failed-alert.png') }}" alt="failed" class="failed-image" />
    <p class="failed-message"></p>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function hidePopup() {
            $(".failed-overlay").fadeOut();
            $(".failed-alert").fadeOut();
        }

        $(".close-popup").on("click", function () {
            hidePopup();
        });

        $(".failed-overlay").on("click", function () {
            hidePopup();
        });
    });

</script>
