<h5 class="information-title">Cập nhật hồ sơ</h5>
<div class="information-avatar-wrapper">
    <img src="{{ $user->avatar ? asset($user->avatar) : asset('assets/images/user-icon.png') }}"
         alt="avatar" class="information-avatar"/>
    <img src="{{asset('assets/images/camera-icon.png')}}" alt="avatar" class="camera-icon"/>
    <input type="file" name="avatar" id="upload-avatar" class="upload-avatar" accept="image/*" style="display: none;"/>

</div>
@if ($errors->has('avatar'))
    <span class="error-message text-center">{{ $errors->first('avatar') }}</span>
@endif
<div class="input-field-wrapper">
    <div class="single-input">
        <label class="label-field">Họ và tên <span style="color: red">*</span></label>
        <input
            type="text"
            name="full_name"
            class="input-field"
            value="{{ old('full_name', $user->full_name ?? 'N/A') }}"
            required
        />
        @if ($errors->has('full_name'))
            <span class="error-message">{{ $errors->first('full_name') }}</span>
        @endif
    </div>
    <div class="double-input">
        <div class="double-input-item">
            <label class="label-field">Địa chỉ email <span style="color: red">*</span></label>
            <input
                type="text"
                name="email"
                class="input-field"
                value="{{ old('email', $user->email ?? 'N/A') }}"
                required
            />
            @if ($errors->has('email'))
                <span class="error-message">{{ $errors->first('email') }}</span>
            @endif
        </div>
        <div class="double-input-item">
            <label class="label-field">Điện thoại
                (<a href="javascript:void(0);" class="update-current-phone" onclick="setUpdatePhoneTab();">Thay Đổi SĐT</a>)
            </label>
            <input
                type="text"
                name="phone"
                class="input-field"
                value="{{ old('phone', $user->phone ?? 'N/A') }}"
                required
                readonly
            />
            @if ($errors->has('phone'))
                <span class="error-message">{{ $errors->first('phone') }}</span>
            @endif
        </div>
    </div>
</div>
<button class="information-submit">Cập nhật hồ sơ</button>

<script>
    function setUpdatePhoneTab() {
        localStorage.setItem('activeTab', 'update-phone');
        window.location.href = "{{ route('profile.index') }}";
    }
</script>
