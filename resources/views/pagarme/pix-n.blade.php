{{-- // Cartão de crédito pagarme --}}
<div class="option-box">
    <h4 onclick="selectPay(this)"><svg clip-rule="evenodd" width="15" fill-rule="evenodd" stroke="black"
            fill="{{ session()->get('pay') == 'credit_card_pagarme' ? 'rgb(51, 51, 51)' : 'none' }}"
            stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <circle cx="11.998" cy="11.998" fill-rule="nonzero" r="9.998" />
        </svg> Pix pagarme</h4>
    <div class="option" style="display: {{ session()->get('pay') == 'pix_pagarme' ? 'flex;' : 'none;' }}">
        <p>pix</p>

    </div>
</div>
