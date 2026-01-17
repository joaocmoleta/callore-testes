<x-mail::message>
# O comprador solicitou o cancelamento do pedido {{ $order_id }} e estorno do valor pago.

Verifique o status da encomenda, se:

1. **Pago** mas produto não coletado:
  
    a. Cancelar pedido no painel administrativo¹

2. **Preparando seu pedido**:

    a. Cancelar pedido no painel administrativo¹

3. **Nota fiscal adicionada**:

    a. Cancelar solicitação de coleta no painel da Total Express;

    b. Em seguida cancelar pedido no painel administrativo¹

4. **Solicitado coleta**:

    a. Se já coletado:

        i.   Aguardar devolução do produto;

        ii.  Fazer a conferência;

        iii. Cancelar pedido no painel administrativo¹

    b. Não coletado:

        i.  **Cancelar coleta** no painel da Total express;

        ii. Em seguida, **se** a Total Express passar coletar:

            1. Informar sobre o cancelamento e **não** despachar a encomenda;
            2. Em seguida realizar o cancelamento no painel administrativo¹

5. Qualquer outro status de entrega, analisar o **passo 6**;

¹ - **Atenção ao realizar o cancelamento** no painel administrativo. Ao clicar, o sistema realiza o **estorno automático** de pagamento por PIX e cartão de crédito.

<x-mail::button :url="$url">
Pedido
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
