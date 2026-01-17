@extends('templates.dashboard.main')
@section('content')
    <div class="home-dashboard">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('leads.index') }}">LEADs</a>
        </div>

        @include('flash-message')

        <h3 class="title">Gerenciar</h3>

        <div>
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Nome:</strong> {{ $user->name }}</p>
            <p><strong>E-mail:</strong> {{ $user->email }}</p>
            <p><strong>Telefone:</strong> +{{ substr($user->phone, 0, 2) }} ({{ substr($user->phone, 2, 2) }})
                {{ substr($user->phone, 4) }}</p>

            <div class="whats-link-pc"><strong>Link whats (Se destinatário possuir): </strong><a
                    href="https://web.whatsapp.com/send?phone={{ $user->phone }}">https://web.whatsapp.com/send?phone={{ $user->phone }}</a>
            </div>
            <div class="whats-link-mob"><strong>Link whats (Se destinatário possuir): </strong><a
                    href="https://wa.me/{{ $user->phone }}">https://wa.me/{{ $user->phone }}</a></div>
            <div>

                <p><strong>Endereço de entrega:</strong> {{ $user->street }} n⁰ {{ $user->number }} -
                    {{ $user->complement }} -
                    {{ $user->locality }} - {{ $user->city }}/{{ $user->region_code }}</p>
                <p><strong>Data de nascimento:</strong>
                    {{ $user->birth ? \Carbon\Carbon::parse($user->birth)->format('d/m/y') : 'Não preencheu' }}
                    ({{ \Carbon\Carbon::parse($user->birth)->age }} anos)</p>
                @if (isset($lead->created_at))
                    <p><strong>Cadastrou-se no Pop Up Lead em:
                        </strong>{{ \Carbon\Carbon::parse($lead->created_at)->format('d/m/y H:i') }}</p>
                @endif
            </div>

            <div class="historic-purchases-box">
                <h3>Histórico de compras:</h3>
                <div class="historic-purchases">
                    <div class="titles">
                        <div>ID</div>
                        <div>Produtos</div>
                        <div>Total</div>
                        <div>Status</div>
                        <div>Cupom</div>
                        <div>Criado em</div>
                        <div>Última atualização</div>
                    </div>
                    @foreach ($orders as $order)
                        <div class="line">
                            <div><span class="mobile"><strong>ID:</strong></span> {{ $order->id }}</div>
                            <div>
                                <span class="mobile"><strong>Produtos: </strong></span>
                                @foreach (json_decode($order->products) as $product)
                                    <strong>{{ $product->qtd }}</strong> {{ $product->name }} no valor de <strong>R$ {{ $product->value_uni }}</strong>
                                    totalizando em <strong>R$ {{ $product->subtotal }}</strong>
                                @endforeach
                            </div>
                            <div><span class="mobile"><strong>Total:</strong> </span>{{ $order->amount }}</div>
                            <div><span class="mobile"><strong>Status:</strong> </span>{{ $order->status }}</div>
                            <div><span class="mobile"><strong>Cupom:</strong> </span>{{ $order->coupon }}</div>
                            <div><span class="mobile"><strong>Criado em:</strong>
                                </span>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/y H:i') }}</div>
                            <div><span class="mobile"><strong>Última atualização:</strong>
                                </span>{{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/y H:i') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="carts-list-box">
                <h3>Carrinhos</h3>
                <div class="carts-list">
                    <div class="titles">
                        <div>IP</div>
                        <div>Cupom</div>
                        <div>Produtos</div>
                        <div>Total</div>
                        <div>Última atualização</div>
                    </div>
                    @foreach ($carts_complete as $cart)
                        <div class="line">
                            <div>{{ $cart['ip'] }}</div>
                            <div>{{ $cart['coupon'] ?? '-' }}</div>
                            <div>
                                @foreach ($cart['products'] as $product)
                                    <strong>{{ $product['qtd'] }}</strong> {{ $product['title'] }} custando <strong>R$ {{ number_format($product['value_uni'], 2, ',', '.') }}</strong>
                                @endforeach
                            </div>
                            <div>{{ number_format($cart['amount'], 2, ',', '.') }}</div>
                            <div>{{ \Carbon\Carbon::parse($cart['updated_at'])->format('d/m/y H:i') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="actions-box">
                <h3>Ações</h3>
                <div class="actions-list">
                    <div class="titles">
                        <div>Ação</div>
                        <div>Grupo</div>
                        <div>Pontuação</div>
                        <div>Ações</div>
                    </div>
                    @foreach ($actions as $action)
                        <div class="line">
                            <div>{{ $action->action }}</div>
                            <div>{{ $action->group_name }}</div>
                            <div>100</div>
                            <div class="actions">
                                @if ($action->status)
                                    <a href="{{ route('leads.action.pending', $action->user_actions_id) }}">
                                        <div class="check-button">
                                            <svg class="on" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24">
                                                <path
                                                    d="M20 12.194v9.806h-20v-20h18.272l-1.951 2h-14.321v16h16v-5.768l2-2.038zm.904-10.027l-9.404 9.639-4.405-4.176-3.095 3.097 7.5 7.273 12.5-12.737-3.096-3.096z" />
                                            </svg>
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ route('leads.action.done', $action->user_actions_id) }}">
                                        <div class="check-button">
                                            <svg class="off" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24">
                                                <path
                                                    d="M20 12.194v9.806h-20v-20h18.272l-1.951 2h-14.321v16h16v-5.768l2-2.038zm.904-10.027l-9.404 9.639-4.405-4.176-3.095 3.097 7.5 7.273 12.5-12.737-3.096-3.096z" />
                                            </svg>
                                        </div>
                                    </a>
                                @endif
                                <form action="{{ route('leads.action.remove', $action->user_actions_id) }}" method="post"
                                    onsubmit="confirmDel(event, 'Deseja remover a ação do LEAD?')">
                                    @method('DELETE')
                                    @csrf
                                    <button class="bt-primary-danger-small">Deletar</button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <form action="{{ route('leads.action.atribuir-adicionar') }}" method="POST" class="line"
                        autocomplete="off">
                        @csrf
                        <input type="hidden" name="user" value="{{ $user->id }}">
                        <div class="input">
                            <span class="mobile"><strong>Ação: </strong></span>
                            <input type="text" name="action" placeholder="Verificar como foi a compra"
                                value="{{ old('action') }}" onkeyup="autofill(this, 'Actions', 'action')"
                                autocomplete="off">
                            @error('action')
                                <div class="msg-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input">
                            <span class="mobile"><strong>Grupo: </strong></span>
                            <input type="text" name="group_name" placeholder="Conquistar cliente"
                                value="{{ old('group_name') }}" onkeyup="autofill(this, 'Groups', 'group_name')">
                            @error('group_name')
                                <div class="msg-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input">
                            <span class="mobile"><strong>Pontos: </strong></span>
                            <input type="text" name="points" placeholder="100" value="{{ old('points') }}">
                            @error('points')
                                <div class="msg-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input">
                            <button type="submit" class="bt-primary-one">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="notes-box">
                <h3>Notas</h3>
                <div class="notes">
                    <div class="titles">
                        <div>Nota</div>
                        <div>Última atualização</div>
                        <div>Ações</div>
                    </div>
                    @foreach ($notes as $note)
                        <div class="line">
                            <div>{{ $note->note }}</div>
                            <div>{{ \Carbon\Carbon::parse($note->updated_at)->format('d/m/y H:i') }}</div>
                            <div class="actions">
                                <form action="{{ route('notes.destroy', $note) }}" method="post"
                                    onsubmit="confirmDel(event, 'Deseja remover a nota do LEAD?')">
                                    @method('DELETE')
                                    @csrf
                                    <button class="bt-primary-danger-small">Deletar</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                    <form action="{{ route('leads.note.add') }}" method="post">
                        @csrf
                        <input type="hidden" name="user" value="{{ $user->id }}">
                        <div class="input">
                            <textarea name="note" placeholder="É possível adicionar dados extras sobre as negociações..."></textarea>
                            @error('note')
                                <div class="msg-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="submit">
                            <button class="bt-primary-one">Adicionar nota</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function confirmDel(ev, msg) {
                if (!confirm(msg)) {
                    ev.preventDefault()
                }
            }

            function sentNote(ev, e) {
                // ev.preventDefault()
                // console.log(ev.key, e)

                // if(ev.key == 'Enter') {
                //     if(ev.key == 'Shift') {
                //         console.log('Adiciona enter')
                //     }
                // }

                // console.log(ev.ctrlKey)
                // if(!ev.ctrlKey && ev.key == 'Enter') {
                //     e.parentElement.parentElement.submit()
                // } else {
                //     return true
                // }
            }

            /*
             * autofill: função principal
             * autofillSelect: função do submenu
             */
            function autofill(e, table, field) {
                if (e.length == 0) {
                    return
                }
                let list = document.createElement('div')
                list.classList.add('list-autofill')

                let url = '{{ route('autofill', [':table', ':valor']) }}'
                url = url.replace(":table", table)
                if (e.value == '') {
                    url = url.replace(":valor", '\'\'')
                } else {
                    url = url.replace(":valor", e.value)
                }

                fetch(url)
                    .then((response) => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            let list_autofill_exits = document.getElementsByClassName('list-autofill')
                            if (list_autofill_exits.length > 0) {
                                list_autofill_exits[0].remove()
                            }
                            data.forEach(element => {
                                let line = document.createElement('div')
                                line.classList.add('list-autofill-item')
                                line.innerText = element[field]
                                line.setAttribute('onclick', "autofillSelect(this)")
                                list.appendChild(line)
                            })
                            e.parentElement.appendChild(list)
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            }

            function autofillSelect(e) {
                e.parentElement.previousElementSibling.value = e.innerText
                e.parentElement.remove()
            }
        </script>
    @endsection
