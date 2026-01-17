<x-mail::message>
# Sua conta foi confirmada

Nosso sistema identificou que você clicou em "Concordar e Confirmar" ou utilizou o link de confirmação enviado no e-mail anterior,
aceitando nossos <a href="{{ route('terms' ) }}">Termos de Uso</a> e nossa <a href="{{ route('privacy') }}">Política de Privacidade</a>,
 dando o consentimento sobre o uso dos dados, 
conforme a <a href="http://www.planalto.gov.br/ccivil_03/_Ato2015-2018/2018/Lei/L13709compilado.htm">Lei Geral de Proteção de Dados Pessoais, Lei nº 13.709/2018</a>.

{{-- Você aceitou receber informativos e publicidade. --}}

A exclusão dos dados podem ser solicitada após a conclusão da prestação de serviços.

{{-- Gerencie o recebimento de e-mails informativos e promocionais: gerenciar. --}}

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
