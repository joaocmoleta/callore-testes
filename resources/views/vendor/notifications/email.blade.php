<x-mail::message>
Ao clicar no botão abaixo você aceita os <a href="{{ route('terms' ) }}">Termos de Uso</a>
 e nossa <a href="{{ route('privacy') }}">Política de Privacidade</a>. Também concorda com o armazenamento dos dados no sistema para
  fins de prestação do serviço contratado, conforme a
<a href="http://www.planalto.gov.br/ccivil_03/_Ato2015-2018/2018/Lei/L13709compilado.htm">Lei Geral de Proteção de Dados Pessoais, Lei nº 13.709/2018</a>.

{{-- Conforme assinalado ao final do formulário, concorda com o envio de e-mails informativos e promocionais. --}}

<x-mail::button :url="$actionUrl">
    {{ $actionText }}
</x-mail::button>

A exclusão dos dados podem ser solicitada após a conclusão da prestação de serviços.

{{-- Caso tenha assinalado o recebimento dos e-mails informativos e promocionais, você pode configurar o recebimento clicando aqui. --}}

Obrigado,<br>
{{ config('app.name') }}

<x-slot:subcopy>
    Se você tiver algum problema ao clicar no botão, copie e cole a URL a seguir no navegador para confirmar e concordar: {{ $actionUrl }}
</x-slot:subcopy>
</x-mail::message>
