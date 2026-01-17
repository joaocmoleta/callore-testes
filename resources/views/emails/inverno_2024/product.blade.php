{{-- <div class="product{{ $key % 2 == 0 ? ' esquerda' : ' direita' }}" style="" valign="top"> --}}
<td align="center" valign="top" width="50%" class="templateColumnContainer">
    <table style="font-size: 20px; line-height: 25px">
        <tr>
            <td style="width: 50%;" align="right"><img src="{{ asset($item['img']) }}" alt="{{ $item['name'] }}"
                    style="width: 165px"></td>
            <td valign="top">
                <table>
                    <tr>
                        <td><span
                                style="font-size: 18px; display: inline-block; margin-bottom: 10px"><strong>{{ $item['name'] }}</strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; line-height: 12px">De</td>
                    </tr>
                    <tr>
                        <td style="font-size: 23px; line-height: 30px; color: #000;"><strong>R$
                                {{ number_format($item['price'], 2, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; line-height: 12px">Com cupom por</td>
                    </tr>
                    <tr>
                        <td style="font-size: 27px; line-height: 35px; color: #000"><strong>6x de</td>
                    </tr>
                    <tr>
                        <td style="font-size: 27px; line-height: 35px; color: #000"><strong>R$
                                {{ number_format(($item['price'] * ((100 - $item['coupon']) / 100)) / 6, 2, ',', '.') }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="{{ $item['url'] }}" target="_blank"
                                style="font-size: 22px; display: inline-block; background: #d8171d; color: #fff; padding: 10px 20px; font-weight: bold; border-radius: 30px; text-decoration: none; margin-top: 10px;">Comprar</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</td>
{{-- </div> --}}
