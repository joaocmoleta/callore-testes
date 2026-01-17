<!DOCTYPE html>
<html>
  <head>
    <title>Checkout Transparente Safra</title>
  </head>

  <body>
    <script
      type="text/javascript"
      src="https://safrastatic-a.akamaihd.net/safrapay/checkout/dev/safrapay-transparent-v1.0.0.js"
    ></script>

    <script>
      var cnpj = "92783182000132"; // cnpj do lojista
      var merchantId = "4019781a-fe84-41dd-9514-e8b18a03aadf"; // merchantId do lojista

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