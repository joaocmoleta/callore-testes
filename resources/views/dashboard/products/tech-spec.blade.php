<div class="field-size">
    @include('field', [
        'field_name' => 'type',
        'field_value' => old('type') ?? 'Toalheiro Térmico',
        'field_label' => 'Tipo',
        'field_type' => 'text',
        'field_placeholder' => 'Toalheiro Térmico',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'color',
        'field_value' => old('color'),
        'field_label' => 'Cor',
        'field_type' => 'text',
        'field_placeholder' => 'Branco',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'voltage',
        'field_value' => old('voltage'),
        'field_label' => 'Voltagem (v)',
        'field_type' => 'text',
        'field_placeholder' => '127',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'wattage',
        'field_value' => old('wattage'),
        'field_label' => 'Potência (W)',
        'field_type' => 'text',
        'field_placeholder' => '200',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'cable',
        'field_value' => old('cable') ?? '1,4m',
        'field_label' => 'Cabo',
        'field_type' => 'text',
        'field_placeholder' => '1,4m',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'material',
        'field_value' => old('material') ?? 'Tubo industrial SAE 1008 fina frio',
        'field_label' => 'Material',
        'field_type' => 'text',
        'field_placeholder' => 'Tubo industrial SAE 1008 fina frio',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'paint',
        'field_value' => old('paint') ?? 'Eletrostática',
        'field_label' => 'Pintura',
        'field_type' => 'text',
        'field_placeholder' => 'Eletrostática',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'suporte_paredes',
        'field_value' => old('suporte_paredes'),
        'field_label' => 'Suporte de parede',
        'field_type' => 'text',
        'field_placeholder' => 'Sim',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'suporte_chao',
        'field_value' => old('suporte_chao'),
        'field_label' => 'Suporte de chão',
        'field_type' => 'text',
        'field_placeholder' => 'Não',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'indicate',
        'field_value' => old('indicate') ?? 'Banheiro',
        'field_label' => 'Indicado',
        'field_type' => 'text',
        'field_placeholder' => 'Banheiro',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'sizes_h',
        'field_value' => old('sizes_h'),
        'field_label' => 'Altura (cm)',
        'field_type' => 'text',
        'field_placeholder' => '85',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'sizes_w',
        'field_value' => old('sizes_w'),
        'field_label' => 'Largura (cm)',
        'field_type' => 'text',
        'field_placeholder' => '55',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'sizes_l',
        'field_value' => old('sizes_l'),
        'field_label' => 'Comprimento (cm)',
        'field_type' => 'text',
        'field_placeholder' => '5',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'sizes_we',
        'field_value' => old('sizes_we'),
        'field_label' => 'Peso (kg)',
        'field_type' => 'text',
        'field_placeholder' => '1,8',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'pack_sizes_h',
        'field_value' => old('pack_sizes_h'),
        'field_label' => 'Altura da embalagem (cm)',
        'field_type' => 'text',
        'field_placeholder' => '85',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'pack_sizes_w',
        'field_value' => old('pack_sizes_w'),
        'field_label' => 'Largura da embalagem (cm)',
        'field_type' => 'text',
        'field_placeholder' => '55',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'pack_sizes_l',
        'field_value' => old('pack_sizes_l'),
        'field_label' => 'Comprimento da embalagem (cm)',
        'field_type' => 'text',
        'field_placeholder' => '5',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'pack_sizes_we',
        'field_value' => old('pack_sizes_we'),
        'field_label' => 'Peso da embalagem (kg)',
        'field_type' => 'text',
        'field_placeholder' => '1,8',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'line',
        'field_value' => old('line') ?? 'Callore',
        'field_label' => 'Marca',
        'field_type' => 'text',
        'field_placeholder' => 'Callore',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'manufacturer',
        'field_value' => old('manufacturer') ?? 'WECO S.A',
        'field_label' => 'Fabricante',
        'field_type' => 'text',
        'field_placeholder' => 'WECO S.A',
    ])
</div>

<div class="field-size">
    @include('field', [
        'field_name' => 'guarantee',
        'field_value' => old('guarantee') ?? 12,
        'field_label' => 'Garantia (meses)',
        'field_type' => 'number',
        'field_placeholder' => '12',
    ])
</div>
