<x-mail::message>
# Novo inscrito no formulário do site

😃 **Nome:** {{ $name }}

☎️ **Telefone:** {{ $phone }}

[WhatsApp no computador](https://web.whatsapp.com/send?phone={{ $phone }} "Enviar mensagem se estiver acessando de um computador")

[WhatsApp no celular](https://wa.me/{{ $phone }} "Enviar mensagem se estiver acessando de um celular")

📧 **E-mail:** {{ $email }}

</x-mail::message>