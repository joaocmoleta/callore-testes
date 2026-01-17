<footer id="contato" role="contentinfo">
    <aside class="info-footer">
        <div class="info-footer-content">
            <div class="item">
                <ul>
                    <li>Weco S.A Indústria de Equipamento Termo Mecânico</li>
                    <li>CNPJ: 92.783.182/0001-32</li>
                    <li>
                        <p>Callore, uma marca do grupo:</p>
                    </li>
                    <li>
                        <a href="https://weco.ind.br/" target="_blank" class="site-weco">
                            <img src="{{ asset('/img/weco.png') }}" alt="Logo Weco" class="institucion-img">
                        </a>
                    </li>
                </ul>

                <p style="margin: 50px 0 20px">Certificação</p>
                <ul>
                    <li>
                        <p>Equipamento certificado pelo OCP 0070 conforme Portaria Inmetro – 148/2022.
                            Normas especificas: ABNT NBR NM 60.335-1/2010
                            IEC 60.335-2-43/2008.</p>
                    </li>
                </ul>



                {{-- <a href="/#sobre">Sobre a Callore</a> --}}

                {{-- <h3 style="margin-top: 20px">Regulamentos</h3>
                <nav>
                    <ul>
                        <li>
                            <a href="{{ route('regulamento-promocao-0608-23') }}">Regulamento promoção de inverno
                                0608/23</a>
                        </li>
                    </ul>
                </nav> --}}

                <nav style="margin: 50px 0 20px">
                    <ul>
                        <li>
                            <a href="{{ route('manual') }}" target="_blank">Manual de instruções Callore - revisão
                                05</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="item contact-informations">
                <p style="margin-bottom: 20px">Contato e informações</p>
                <ul>
                    <li>
                        <a href="tel:555133496200">
                            <img src="{{ asset('icons/phone.svg') }}" width="20" alt="+55 (51) 3349-6200">
                        </a>
                        <a href="tel:555133496200">+55 (51) 3349-6200</a>
                    </li>
                    <li>
                        <a href="https://web.whatsapp.com/send?phone=5551995944678" class="whats-pc" target="_blank">
                            <img width="20" src="{{ asset('/img/whatsapp-s.svg') }}" alt="Whatsapp">
                        </a>
                        <a href="https://web.whatsapp.com/send?phone=5551995944678" class="whats-pc" target="_blank">+55
                            (51) 9 9594-4678</a>
                        <a href="https://wa.me/5551995944678" class="whats-mob" target="_blank">
                            <img width="20" src="{{ asset('/img/whatsapp-s.svg') }}" alt="Whatsapp">
                        </a>
                        <a href="https://wa.me/5551995944678" class="whats-mob" target="_blank">+55 (51) 9 9594-4678</a>
                    </li>
                    <li>
                        <a href="mailto:vendas@aquecedordetoalhas.com.br">vendas@aquecedordetoalhas.com.br</a>
                    </li>
                    <li>
                        <a href="https://goo.gl/maps/FdHoBjudFodgaVgW8">Rua Joaquim Silveira, 1057 - São Sebastião -
                            Porto Alegre/RS - 91060-320</a>
                    </li>
                    <li>
                        <a href="{{ route('flip') }}">
                            <img src="{{ asset('icons/book-magazine-flip.svg') }}" alt="Catálogo" width="20"
                                height="20"></a>
                        <a href="{{ route('flip') }}">Acesse nosso catálogo</a>
                    </li>
                </ul>

                <p style="margin: 50px 0 20px">Segurança</p>
                <ul class="check-site-footer">
                    <li>
                        <a href="https://www.sslshopper.com/ssl-checker.html#hostname={{ env('APP_URL') }}/"
                            target="_blank" title="Verifque a segurança da Callore">
                            <img src="{{ asset('img/SSL.svg') }}" alt="Segurança SSL"></a>
                    </li>
                    <li>
                        <a href="https://transparencyreport.google.com/safe-browsing/search?url=https:%2F%2Faquecedordetoalhas.com.br%2F"
                            target="_blank" title="Verifque a segurança da Callore">
                            <img src="{{ asset('img/google-site-seguro-pt.svg') }}" alt="Transparência Google"></a>
                    </li>
                </ul>
            </div>
            <div class="item">
                <p style="margin-bottom: 20px">
                    <a href="{{ route('guaranteed') }}">Garantia</a>
                </p>
                <ul>
                    <li>
                        <a href="{{ route('return-term') }}">Política de devoluções</a>
                    </li>
                    <li>
                        <a href="{{ route('delivery-term') }}">Política de entrega</a>
                    </li>
                    <li>
                        <a href="{{ route('privacy') }}">Política de privacidade</a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}">Termos de uso</a>
                    </li>
                </ul>

                <p style="margin: 50px 0 20px">Redes Sociais</p>
                <nav class="social-menu">
                    <ul>
                        <li>
                            <a href="https://www.instagram.com/calloreaquecedores/" target="_blank"
                                class="instagram-link">
                                <img src="{{ asset('/img/instagram.webp') }}" alt="Instagram Callore" width="20">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com/calloreaquecedores" target="_blank" class="facebook-link">
                                <img src="{{ asset('/img/facebook.webp') }}" alt="Facebook Callore" width="20">
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </aside>
    {{-- <aside style="max-width: 1170px; margin: auto; line-height: 0">
        <img src="//assets.pagseguro.com.br/ps-integration-assets/banners/pagamento/todos_estatico_550_100.gif"
            alt="Logotipos de meios de pagamento do PagSeguro"
            title="Este site aceita pagamentos com as principais bandeiras e bancos, saldo em conta PagSeguro e boleto.">
    </aside> --}}
    <aside class="copy-right">
        <p>Desenvolvido por <a href="https://mol.dev.br" target="_blank">Mol Tecnologia e Inovação</a>
            &copy; Todos os direitos reservados <strong>{{ env('APP_NAME') }}</strong>
            {{ now()->year }}</p>
    </aside>
    {{-- <a href="https://wa.me/5551995944678" target="_blank" class="whatsapp-float-link-mob">
        <img src="{{ asset('icons/whatsapp-s.svg') }}" width="30" alt="WhatsApp 55 51 9 9594-4678">
        <div class="text">
            <div>Tire suas</div>
            <div>dúvidas</div>
        </div>
    </a> --}}
    {{-- <a href="https://web.whatsapp.com/send?phone=5551995944678" target="_blank" class="whatsapp-float-link">
        <img src="{{ asset('icons/whatsapp-s.svg') }}" width="30" alt="WhatsApp 55 51 9 9594-4678">
        <div class="text">
            <div>Tire suas</div>
            <div>dúvidas</div>
        </div>
    </a> --}}

    <a href="{{ route('carts.list') }}" class="float-cart">
        <img src="{{ asset('icons/cart-2.svg') }}" width="30" alt="Acesse seu carrinho!">
        <span class="qtd">{{ $cart->qtd ?? '0' }}</span>
    </a>

    <a href="https://wa.me/+5541991962351" target="_blank" class="whatsapp-float-link-mob">
        <img src="{{ asset('icons/whatsapp-s.svg') }}" width="30" alt="WhatsApp +55 (41) 99196-2351">
    </a>

    <link rel="stylesheet" href="{{ asset('/css/carousel-1.5.min.css') }}">
    <script src="{{ asset('js/carousel-1.5.min.js') }}"></script>
    <script>
        if (typeof configurations_carousel !== 'undefined') {
            carMolMultiInit(configurations_carousel)
        }
    </script>
    {{-- <script src="//code.jivosite.com/widget/cdbFmtoTSk" defer></script> --}}
</footer>
