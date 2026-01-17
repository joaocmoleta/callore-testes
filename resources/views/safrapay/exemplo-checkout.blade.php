<!DOCTYPE html>
<html>

<head>
    <title>Checkout Transparente Safra</title>
</head>

<body>
    <script type="text/javascript" src="{{ env('SAFRA_SCRIPT_TOKENIZACAO') }}"></script>

    <script>
          var cnpj = "{{ env('CNPJ') }}"; // cnpj do lojista
          var merchantId = "{{ env('SAFRA_M_ID') }}"; // merchantId do lojista
        // var cnpj = "62225893000120"; // cnpj do lojista teste
        // var merchantId = "FC1123EE-3150-4768-ADDF-4D4C8D4264B0"; // merchantId do lojista teste

        /* Cartão de exemplo para gerar o hash */
        var card = {
            brand: "Visa",
            number: "5491670214095346",
            holderName: "FULANO SILVA",
            holderDocument: "65844359038",
            expirationMonth: "02",
            expirationYear: "2026",
            cvv: "223",
        };

        const run = () => {
            SafraPayTransparent.setCredentials({
                merchantCredential: cnpj,
                merchantId: merchantId,
            });

            SafraPayTransparent.getCardBrand({
                bin: card.number.substring(0, 6),
                success: (body) => {
                    alert(`Bandeira: ${body.brand}`);
                    console.log(body);
                },
                error: (body) => {
                    console.log(body);
                },
            });

            SafraPayTransparent.createTemporaryCard({
                card,
                success: (body) => {
                    alert(`Token do cartao: ${body.temporaryToken}`);
                    console.log(body);
                },
                error: (body) => {
                    console.log(body);
                },
            });
        };

        window.onload = () => {
            run();
        }
    </script>
</body>

</html>
