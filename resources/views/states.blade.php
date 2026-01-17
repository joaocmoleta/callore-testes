<label>{{ $field_label }}</label>
<div class="input">
    <select name="{{ $field_name }}">
        <option value="AC" {{ old($field_name) == '' ? 'selected' : ($field_value == '' ? 'selected' : '') }}>Selecione</option>
        <option value="AC" {{ old($field_name) == 'AC' ? 'selected' : ($field_value == 'AC' ? 'selected' : '') }}>Acre</option>
        <option value="AL" {{ old($field_name) == 'AL' ? 'selected' : ($field_value == 'AL' ? 'selected' : '') }}>Alagoas</option>
        <option value="AP" {{ old($field_name) == 'AP' ? 'selected' : ($field_value == 'AP' ? 'selected' : '') }}>Amapá</option>
        <option value="AM" {{ old($field_name) == 'AM' ? 'selected' : ($field_value == 'AM' ? 'selected' : '') }}>Amazonas</option>
        <option value="BA" {{ old($field_name) == 'BA' ? 'selected' : ($field_value == 'BA' ? 'selected' : '') }}>Bahia</option>
        <option value="CE" {{ old($field_name) == 'CE' ? 'selected' : ($field_value == 'CE' ? 'selected' : '') }}>Ceará</option>
        <option value="DF" {{ old($field_name) == 'DF' ? 'selected' : ($field_value == 'DF' ? 'selected' : '') }}>Distrito Federal</option>
        <option value="ES" {{ old($field_name) == 'ES' ? 'selected' : ($field_value == 'ES' ? 'selected' : '') }}>Espírito Santo</option>
        <option value="GO" {{ old($field_name) == 'GO' ? 'selected' : ($field_value == 'GO' ? 'selected' : '') }}>Goiás</option>
        <option value="MA" {{ old($field_name) == 'MA' ? 'selected' : ($field_value == 'MA' ? 'selected' : '') }}>Maranhão</option>
        <option value="MT" {{ old($field_name) == 'MT' ? 'selected' : ($field_value == 'MT' ? 'selected' : '') }}>Mato Grosso</option>
        <option value="MS" {{ old($field_name) == 'MS' ? 'selected' : ($field_value == 'MS' ? 'selected' : '') }}>Mato Grosso do Sul</option>
        <option value="MG" {{ old($field_name) == 'MG' ? 'selected' : ($field_value == 'MG' ? 'selected' : '') }}>Minas Gerais</option>
        <option value="PA" {{ old($field_name) == 'PA' ? 'selected' : ($field_value == 'PA' ? 'selected' : '') }}>Pará</option>
        <option value="PB" {{ old($field_name) == 'PB' ? 'selected' : ($field_value == 'PB' ? 'selected' : '') }}>Paraíba</option>
        <option value="PR" {{ old($field_name) == 'PR' ? 'selected' : ($field_value == 'PR' ? 'selected' : '') }}>Paraná</option>
        <option value="PE" {{ old($field_name) == 'PE' ? 'selected' : ($field_value == 'PE' ? 'selected' : '') }}>Pernambuco</option>
        <option value="PI" {{ old($field_name) == 'PI' ? 'selected' : ($field_value == 'PI' ? 'selected' : '') }}>Piauí</option>
        <option value="RJ" {{ old($field_name) == 'RJ' ? 'selected' : ($field_value == 'RJ' ? 'selected' : '') }}>Rio de Janeiro</option>
        <option value="RN" {{ old($field_name) == 'RN' ? 'selected' : ($field_value == 'RN' ? 'selected' : '') }}>Rio Grande do Norte</option>
        <option value="RS" {{ old($field_name) == 'RS' ? 'selected' : ($field_value == 'RS' ? 'selected' : '') }}>Rio Grande do Sul</option>
        <option value="RO" {{ old($field_name) == 'RO' ? 'selected' : ($field_value == 'RO' ? 'selected' : '') }}>Rondônia</option>
        <option value="RR" {{ old($field_name) == 'RR' ? 'selected' : ($field_value == 'RR' ? 'selected' : '') }}>Roraima</option>
        <option value="SC" {{ old($field_name) == 'SC' ? 'selected' : ($field_value == 'SC' ? 'selected' : '') }}>Santa Catarina</option>
        <option value="SP" {{ old($field_name) == 'SP' ? 'selected' : ($field_value == 'SP' ? 'selected' : '') }}>São Paulo</option>
        <option value="SE" {{ old($field_name) == 'SE' ? 'selected' : ($field_value == 'SE' ? 'selected' : '') }}>Sergipe</option>
        <option value="TO" {{ old($field_name) == 'TO' ? 'selected' : ($field_value == 'TO' ? 'selected' : '') }}>Tocantins</option>
        <option value="EX" {{ old($field_name) == 'EX' ? 'selected' : ($field_value == 'EX' ? 'selected' : '') }}>Estrangeiro</option>
    </select>
    @error('state')
        <div class="msg-error">{{ $message }}</div>
    @enderror
</div>
