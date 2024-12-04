<style>
    .warning-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9998;
    }
    .warning-alert {
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
        width: 30%;
        height: 400px;
    }

    .close-popup {
        position: absolute;
        top: 10px;
        right: 15px;
        cursor: pointer;
        font-size: 18px;
        color: #333;
    }

    .warning-image {
        width: 200px;
        margin-bottom: 15px;
    }

    .warning-alert p {
        font-size: 20px;
        color: #333;
        font-weight: 600;
        text-align: center;
        margin: 0;
        padding: 0 20px;
    }
    .button-to-login{
        padding: 8px 16px;
        background: #F1641E;
        color: white;
        border-radius: 8px;
    }
    .button-back{
        padding: 6px 16px;
        background: transparent;
        border: 1px solid #F1641E;
        color: #F1641E;
        border-radius: 8px;
        font-weight: 600;
    }
    @media screen and (max-width: 1200px) {
        .warning-alert {
            width: 50%;
        }
    }
    @media screen and (max-width: 768px) {
        .warning-alert {
            width: 75%;
        }
        .warning-alert p {
            font-size: 16px;
            padding: 0 10px
        }
        .button-to-login{
            padding: 4px 16px;
        }
        .button-back {
            padding: 2px 16px;
        }
    }
    @media screen and (max-width: 420px) {
        .warning-alert {
            width: 100%;
        }
    }
</style>

<div class="warning-overlay" style="display: none"></div>
<div class="warning-alert" style="display: none">
    <div class="close-popup">
        <i class="fa fa-times" aria-hidden="true"></i>
    </div>
    <img src="{{ asset('assets/images/warning-alert.png') }}" alt="warning" class="warning-image" />
    <p class="warning-message"></p>
    <div class="d-flex align-items-center justify-content-center mt-4" style="gap: 10px">
        <a href="{{route('login')}}" class="button-to-login">Đăng Nhập</a>
        <button class="button-back">Quay Lại</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function hidePopup() {
            $(".warning-overlay").fadeOut();
            $(".warning-alert").fadeOut();
        }

        $(".close-popup, .button-to-login, .button-back").on("click", function () {
            hidePopup();
        });

        $(".warning-overlay").on("click", function () {
            hidePopup();
        });
    });

</script>
