<h5 class="change-password-title">Thay đổi mật khẩu</h5>
<div class="input-field-wrapper">
    <div class="single-input">
        <label class="label-field">Mật khẩu hiện tại <span style="color: red">*</span></label>
        <div class="input-field-password">
            <input
                type="password"
                name="current_password"
                class="input-field"
                required
            />
            <img src="{{ asset('assets/images/show-password.png') }}" alt="Show Password" class="show-password" />
        </div>
        @if ($errors->has('current_password'))
            <span class="error-message">{{ $errors->first('current_password') }}</span>
        @endif
    </div>
    <div class="single-input">
        <label class="label-field">Mật khẩu mới <span style="color: red">*</span></label>
        <div class="input-field-password">
            <input
                type="password"
                name="password_new"
                class="input-field"
                required
            />
            <img src="{{ asset('assets/images/show-password.png') }}" alt="Show Password" class="show-password" />
        </div>
        @if ($errors->has('password_new'))
            <span class="error-message">{{ $errors->first('password_new') }}</span>
        @endif
    </div>
    <div class="single-input">
        <label class="label-field">Nhập lại mật khẩu <span style="color: red">*</span></label>
        <div class="input-field-password">
            <input
                type="password"
                name="password_confirmation"
                class="input-field"
                required
            />
            <img src="{{ asset('assets/images/show-password.png') }}" alt="Show Password" class="show-password" />
        </div>
        @if ($errors->has('password_confirmation'))
            <span class="error-message">{{ $errors->first('password_confirmation') }}</span>
        @endif
    </div>
</div>
<button class="change-password-submit">Cập nhật mật khẩu</button>
