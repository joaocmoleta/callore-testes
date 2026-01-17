<a class="option-pix" href="{{ route('pay.pix_pagarme', $db_order->id)}}"><svg clip-rule="evenodd" width="15" fill-rule="evenodd" stroke="black"
    fill="{{ session()->get('pay') == 'pix_pagarme' ? 'rgb(51, 51, 51)' : 'none' }}"
    stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <circle cx="11.998" cy="11.998" fill-rule="nonzero" r="9.998" />
</svg> Pagar com PIX</a>