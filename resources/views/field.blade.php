<label>{{ $field_label }}</label>
<div class="input">
    <input type="{{ $field_type ?? 'text' }}" name="{{ $field_name }}" placeholder="{{ $field_placeholder ?? '' }}"
        value="{{ old($field_name) ?? ($field_value ?? '') }}">
    @error($field_name)
        <div class="msg-error">{{ $message }}</div>
    @enderror
</div>
