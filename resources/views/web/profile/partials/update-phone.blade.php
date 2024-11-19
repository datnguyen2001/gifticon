<h5 class="update-phone-title">Thay đổi số điện thoại</h5>
@include('web.partials.success-alert')
<form method="POST" action="{{route('profile.verifyOTP')}}" >
    @csrf
    <div class="input-field-wrapper">
        <div class="single-input">
            <label class="label-field">Số điện thoại mới</label>
            <div class="d-flex">
                <input
                    type="text"
                    name="phone"
                    id="phone-input"
                    class="input-field"
                    value="{{ old('phone') }}"
                    required
                />
                <button class="send-otp text-nowrap mx-2" id="send-otp-btn">Gửi mã xác thực</button>
            </div>
            <span class="error-message"></span>
        </div>
        <div class="single-input">
            <label class="label-field">Mã xác thực</label>
            <input
                type="text"
                name="otp"
                class="input-field"
                id="otp-input"
                value="{{ old('otp') }}"
                required
            />
            @if ($errors->has('otp'))
                <span class="error-message">{{ $errors->first('otp') }}</span>
            @endif
        </div>
        <button class="update-phone-submit-disable" id="update-phone-disable-btn" disabled>Cập nhật hồ sơ</button>
        <button class="update-phone-submit" id="update-phone-btn" style="display: none;">Cập nhật hồ sơ</button>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const sendOtpBtn = $("#send-otp-btn");
        const updatePhoneDisableBtn = $("#update-phone-disable-btn");
        const updatePhoneBtn = $("#update-phone-btn");
        const phoneInput = $("#phone-input");
        const otpInput = $("#otp-input");
        const errorMessage = $(".error-message");

        sendOtpBtn.on("click", function (e) {
            e.preventDefault();
            const phone = phoneInput.val();

            errorMessage.text("");

            if (!phone) {
                errorMessage.text("Vui lòng nhập số điện thoại mới.");
                return;
            }

            showLoading();

            $.ajax({
                url: "{{ route('profile.sendOTP') }}",
                type: "POST",
                data: {
                    phone: phone,
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    hideLoading();
                    if (response.success) {
                        $(".success-message").text("Mã xác thực đã được gửi!");
                        $(".success-alert").fadeIn();
                        $(".success-overlay").fadeIn();
                    } else {
                        errorMessage.text(response.message || "Gửi mã xác thực thất bại.");
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    if (xhr.status === 422) {
                        // Handle validation errors
                        const response = xhr.responseJSON;
                        if (response && response.message) {
                            errorMessage.text(response.message);
                        } else {
                            errorMessage.text("Đã xảy ra lỗi khi gửi OTP.");
                        }
                    } else {
                        errorMessage.text("Đã xảy ra lỗi, vui lòng thử lại.");
                    }
                },
            });
        });

        // Handle OTP input for showing the update button
        otpInput.on("input", function () {
            const otp = $(this).val();

            if (otp.length === 5 && /^[0-9]+$/.test(otp)) {
                updatePhoneDisableBtn.hide();
                updatePhoneBtn.show();
            } else {
                updatePhoneDisableBtn.show();
                updatePhoneBtn.hide();
            }
        });

        // Check OTP input on page load (for verifyOTPProfile failure case)
        const initialOtp = otpInput.val();
        if (initialOtp.length === 5 && /^[0-9]+$/.test(initialOtp)) {
            updatePhoneDisableBtn.hide();
            updatePhoneBtn.show();
        } else {
            updatePhoneDisableBtn.show();
            updatePhoneBtn.hide();
        }
    });
</script>
