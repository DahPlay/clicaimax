<div class="modal-content cp-modal">
    <style>
        .cp-modal .modal-header {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            color: #fff;
            border-bottom: 0;
            padding: 22px 24px;
            text-align: center;
            display: block;
        }
        .cp-modal .modal-header .close { color: #fff; opacity: .9; position: absolute; right: 18px; top: 18px; text-shadow: none; }
        .cp-modal .modal-header h3 { font-weight: 800; margin: 0; font-size: 20px; }
        .cp-modal .modal-header .cp-subtitle { display: block; opacity: .9; font-size: 13px; margin-top: 4px; }
        .cp-modal .modal-body { background: #f4f6f9; padding: 24px; }

        .cp-cycle-pills {
            display: flex; justify-content: center; gap: 10px;
            flex-wrap: wrap; margin-bottom: 22px;
        }
        .cp-cycle-pill {
            background: transparent; border: 1.5px solid #1565c0;
            color: #1565c0; padding: 7px 18px; border-radius: 999px;
            font-weight: 700; font-size: 13px; cursor: pointer;
            text-transform: uppercase; letter-spacing: .3px;
        }
        .cp-cycle-pill.active {
            background: #1565c0; color: #fff;
        }

        .cp-plans-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }
        .cp-plan {
            background: #fff; border: 1.5px solid #e1e7ee; border-radius: 14px;
            padding: 22px 18px 18px; text-align: center; position: relative;
        }
        .cp-plan.is-current { border-color: #c4c4c4; background: #f8f9fa; }
        .cp-plan.is-best { border-color: #1565c0; }
        .cp-plan .cp-badge {
            position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
            padding: 4px 14px; border-radius: 999px;
            font-size: 11px; font-weight: 700; letter-spacing: .4px;
            background: #1565c0; color: #fff;
        }
        .cp-plan.is-current .cp-badge { background: #6c757d; }
        .cp-plan .cp-name { font-size: 18px; font-weight: 800; color: #0d2b4a; margin-bottom: 4px; }
        .cp-plan .cp-desc { font-size: 12px; color: #6c757d; margin-bottom: 14px; min-height: 32px; }
        .cp-plan .cp-price { font-size: 30px; font-weight: 800; color: #0d2b4a; line-height: 1; margin-bottom: 4px; }
        .cp-plan .cp-price small { display: block; font-size: 12px; font-weight: 500; color: #6c757d; margin-top: 4px; }
        .cp-plan .cp-benefits { list-style: none; padding: 0; margin: 14px 0; font-size: 13px; text-align: left; }
        .cp-plan .cp-benefits li { padding: 3px 0; display: flex; align-items: flex-start; gap: 6px; }
        .cp-plan .cp-benefits li i { color: #1565c0; margin-top: 4px; font-size: 10px; }
        .cp-plan .cp-start {
            display: inline-block; width: 100%; border: 0; padding: 10px 14px; border-radius: 8px;
            background: linear-gradient(135deg, #1565c0, #0d47a1); color: #fff;
            font-weight: 700; font-size: 13.5px; letter-spacing: .3px; cursor: pointer;
        }
        .cp-plan .cp-start:hover { filter: brightness(1.08); color: #fff; }
        .cp-plan .cp-current-btn {
            display: inline-block; width: 100%; padding: 10px 14px; border-radius: 8px;
            background: #e9ecef; color: #6c757d; font-weight: 700; font-size: 13.5px;
            cursor: not-allowed; text-align: center;
        }

        /* === Tela 2 — Confirme o pagamento === */
        .cp-confirm {
            background: #fff; border-radius: 14px; padding: 24px;
            max-width: 480px; margin: 0 auto;
            box-shadow: 0 4px 14px rgba(0,0,0,.06);
        }
        .cp-back-link {
            color: #1565c0; font-weight: 600; font-size: 13px;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none; cursor: pointer; margin-bottom: 14px;
        }
        .cp-back-link:hover { color: #0d47a1; }
        .cp-confirm h4 { font-weight: 800; color: #0d2b4a; font-size: 18px; margin-bottom: 6px; }
        .cp-confirm .cp-confirm-meta { color: #6c757d; font-size: 13px; margin-bottom: 18px; }

        .cp-pay-pills {
            display: flex; gap: 8px; margin-bottom: 18px;
        }
        .cp-pay-pill {
            flex: 1; background: #fff; border: 1.5px solid #e1e7ee;
            color: #6c757d; padding: 12px 8px; border-radius: 8px;
            cursor: pointer; font-weight: 700; font-size: 13px;
            text-align: center;
        }
        .cp-pay-pill.active { background: linear-gradient(135deg, #1565c0, #0d47a1); color: #fff; border-color: #0d47a1; }

        .cp-card-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
        .cp-confirm .form-control {
            border: 1.5px solid #e1e7ee; border-radius: 8px; padding: 10px 14px;
            font-size: 14px; margin-bottom: 10px;
        }
        .cp-https-note { font-size: 12px; color: #6c757d; text-align: left; margin-top: 6px; margin-bottom: 16px; }
        .cp-info-block {
            background: #f4f6f9; border-radius: 10px; padding: 14px;
            font-size: 13px; color: #495057; text-align: center; line-height: 1.5;
            margin-bottom: 16px;
        }
        .cp-info-block strong { display: block; color: #0d2b4a; margin-bottom: 4px; font-size: 14px; }

        .cp-confirm-btn {
            width: 100%; padding: 12px; border-radius: 10px; border: 0;
            background: linear-gradient(135deg, #1565c0, #0d47a1); color: #fff;
            font-weight: 700; font-size: 14px;
        }
        .cp-confirm-btn:disabled { background: #cbd5e0; cursor: not-allowed; }
        .cp-confirm-btn:not(:disabled):hover { filter: brightness(1.08); }
        .cp-confirm-hint { text-align: center; font-size: 12px; color: #6c757d; margin-top: 10px; }

        .cp-coupon-row { display: flex; gap: 8px; margin: 8px 0 12px; }
        .cp-coupon-row .form-control { flex: 1; margin-bottom: 0; }
        .cp-coupon-row .btn { border-radius: 8px; padding: 8px 16px; font-weight: 600; }
        .cp-coupon-feedback { font-size: 12px; min-height: 16px; }
    </style>

    <div class="modal-header">
        <h3 data-cp-title="step1">Escolha o plano que mais combina com você</h3>
        <h3 data-cp-title="step2" style="display:none;">Confirme o pagamento</h3>
        <span class="cp-subtitle">Atualize sua assinatura quando quiser, sem complicação.</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">

        {{-- ============== TELA 1: Lista de planos por ciclo ============== --}}
        <div data-cp-step="1">
            <div class="cp-cycle-pills">
                @foreach ($cycles as $cycleKey => $cycleName)
                    <button type="button" class="cp-cycle-pill {{ $cycleKey === $activeCycle ? 'active' : '' }}"
                            data-cycle="{{ $cycleKey }}">{{ $cycleName }}</button>
                @endforeach
            </div>

            @foreach ($cycles as $cycleKey => $cycleName)
                <div class="cp-cycle-block" data-cycle-block="{{ $cycleKey }}"
                     style="{{ $cycleKey === $activeCycle ? '' : 'display:none;' }}">
                    @if (isset($plansByCycle[$cycleKey]) && count($plansByCycle[$cycleKey]))
                        <div class="cp-plans-row">
                            @foreach ($plansByCycle[$cycleKey] as $plan)
                                @php
                                    $abt = $plan->allowed_billing_types ?? null;
                                    if (is_array($abt))                       $abtCsv = implode(',', $abt);
                                    elseif (is_string($abt) && $abt !== '')   $abtCsv = $abt;
                                    else                                      $abtCsv = (string) ($plan->billing_type ?? 'CREDIT_CARD');
                                    $isCurrent = $plan->id === $actualPlan;
                                @endphp
                                <div class="cp-plan {{ $isCurrent ? 'is-current' : ($plan->is_best_seller ? 'is-best' : '') }}">
                                    @if ($isCurrent)
                                        <span class="cp-badge">SEU PLANO ATUAL</span>
                                    @elseif ($plan->is_best_seller)
                                        <span class="cp-badge">MAIS VENDIDO</span>
                                    @endif

                                    <div class="cp-name">{{ $plan->name }}</div>
                                    <div class="cp-desc">{{ \Illuminate\Support\Str::limit($plan->description, 60) }}</div>

                                    <div class="cp-price">
                                        <small>R$</small>
                                        <span data-cp-base="{{ $plan->value }}">{{ number_format($plan->value, 2, ',', '.') }}</span>
                                        <small>{{ $plan->free_for_days > 0 ? $plan->free_for_days . ' dias grátis' : ucfirst(strtolower(\App\Enums\CycleAsaasEnum::tryFrom($plan->cycle)?->getName() ?? '')) }}</small>
                                    </div>

                                    <ul class="cp-benefits">
                                        @foreach ($plan->benefits->take(4) as $benefit)
                                            <li><i class="fa fa-check"></i><span>{{ $benefit->description }}</span></li>
                                        @endforeach
                                    </ul>

                                    @if ($isCurrent)
                                        <div class="cp-current-btn">Plano atual</div>
                                    @else
                                        <button type="button" class="cp-start"
                                                data-plan-id="{{ $plan->id }}"
                                                data-plan-name="{{ $plan->name }}"
                                                data-plan-value="{{ $plan->value }}"
                                                data-plan-cycle="{{ $plan->cycle }}"
                                                data-plan-cycle-label="{{ \App\Enums\CycleAsaasEnum::tryFrom($plan->cycle)?->getName() ?? '' }}"
                                                data-allowed-billing-types="{{ $abtCsv }}">
                                            Começar agora
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted">Nenhum plano disponível para este ciclo.</p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ============== TELA 2: Confirme o pagamento ============== --}}
        <div data-cp-step="2" style="display:none;">
            <div class="cp-confirm">
                <a href="javascript:;" class="cp-back-link" data-cp-back>
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>

                <h4>Confirme o pagamento</h4>
                <div class="cp-confirm-meta">
                    <strong data-cp-selected-name></strong>
                    · R$ <span data-cp-selected-value></span>
                    · <span data-cp-selected-cycle></span>
                </div>

                <div class="cp-coupon-row">
                    <input type="text" class="form-control" id="cpCouponInput" placeholder="Cupom de desconto">
                    <button type="button" class="btn btn-primary" id="cpCouponApply">Aplicar</button>
                </div>
                <small class="cp-coupon-feedback" id="cpCouponFeedback"></small>

                <div class="cp-pay-pills" id="cpPayPills"></div>

                <form action="{{ route('panel.orders.changePlanStore') }}" method="POST" id="cpForm">
                    @csrf
                    <input type="hidden" name="planId"  id="cpPlanId">
                    <input type="hidden" name="orderId" value="{{ $order->id }}">
                    <input type="hidden" name="coupon"  id="cpCouponHidden">
                    <input type="hidden" name="billing_type" id="cpBillingType" value="CREDIT_CARD">

                    {{-- Bloco Cartão --}}
                    <div data-cp-pay="CREDIT_CARD">
                        <input type="text" name="credit_card_name" class="form-control" placeholder="Nome impresso no cartão *">
                        <input type="text" name="credit_card_number" class="form-control" placeholder="Número do cartão *" maxlength="19" inputmode="numeric">
                        <div class="cp-card-row">
                            <input type="text" name="credit_card_expiry_month" class="form-control" placeholder="MM *" maxlength="2" inputmode="numeric">
                            <input type="text" name="credit_card_expiry_year"  class="form-control" placeholder="AAAA *" maxlength="4" inputmode="numeric">
                            <input type="text" name="credit_card_ccv"          class="form-control" placeholder="CVV *" maxlength="4" inputmode="numeric">
                        </div>
                        <p class="cp-https-note">Não armazenamos dados do cartão — vão direto pra Asaas via HTTPS.</p>
                    </div>

                    {{-- Bloco PIX --}}
                    <div data-cp-pay="PIX" style="display:none;">
                        <div class="cp-info-block">
                            <strong><i class="fa fa-qrcode mr-1"></i> Pagamento via PIX</strong>
                            Você receberá um QR Code e código copia-e-cola após confirmar.
                        </div>
                    </div>

                    {{-- Bloco BOLETO --}}
                    <div data-cp-pay="BOLETO" style="display:none;">
                        <div class="cp-info-block">
                            <strong><i class="fa fa-barcode mr-1"></i> Pagamento via Boleto</strong>
                            O link do boleto será gerado após confirmar. Compensação em até 3 dias úteis.
                        </div>
                    </div>

                    <button type="submit" class="cp-confirm-btn" id="cpConfirmBtn" disabled>
                        <i class="fa fa-lock mr-2"></i>Confirmar e ativar plano
                    </button>
                    <div class="cp-confirm-hint" id="cpConfirmHint">Escolha um método de pagamento e preencha os dados.</div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const $modal = $('.cp-modal');
    if (!$modal.length) return;

    // ----- Tela 1: alternância de ciclo -----
    $modal.on('click', '.cp-cycle-pill', function () {
        const cycle = $(this).data('cycle');
        $modal.find('.cp-cycle-pill').removeClass('active');
        $(this).addClass('active');
        $modal.find('.cp-cycle-block').hide();
        $modal.find(`.cp-cycle-block[data-cycle-block="${cycle}"]`).show();
    });

    // ----- "Começar agora" -> abre tela 2 -----
    $modal.on('click', '.cp-start', function () {
        const $btn = $(this);
        const planId    = $btn.data('plan-id');
        const planName  = $btn.data('plan-name');
        const planValue = parseFloat($btn.data('plan-value')) || 0;
        const cycleLabel = $btn.data('plan-cycle-label');
        const allowed   = ($btn.data('allowed-billing-types') || 'CREDIT_CARD').toString().split(',').map(s => s.trim()).filter(Boolean);

        $('#cpPlanId').val(planId);
        $modal.find('[data-cp-selected-name]').text(planName);
        $modal.find('[data-cp-selected-value]').text(planValue.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $modal.find('[data-cp-selected-cycle]').text(cycleLabel);

        // Renderiza pills baseado em allowed_billing_types
        const labels = {
            CREDIT_CARD: '<i class="fa fa-credit-card mr-2"></i>Cartão de crédito',
            PIX:         '<i class="fa fa-qrcode mr-2"></i>PIX',
            BOLETO:      '<i class="fa fa-barcode mr-2"></i>Boleto'
        };
        const $pills = $('#cpPayPills').empty();
        allowed.forEach((m, i) => {
            $pills.append(`<div class="cp-pay-pill ${i === 0 ? 'active' : ''}" data-method="${m}">${labels[m] || m}</div>`);
        });
        $('#cpBillingType').val(allowed[0] || 'CREDIT_CARD');
        showPayBlock(allowed[0] || 'CREDIT_CARD');
        validateConfirm();

        // Reset cupom
        $('#cpCouponInput').val(''); $('#cpCouponHidden').val(''); $('#cpCouponFeedback').text('').removeClass('text-success text-danger');

        // Troca de tela
        $modal.find('[data-cp-step="1"]').hide();
        $modal.find('[data-cp-step="2"]').show();
        $modal.find('[data-cp-title="step1"]').hide();
        $modal.find('[data-cp-title="step2"]').show();
    });

    $modal.on('click', '[data-cp-back]', function () {
        $modal.find('[data-cp-step="2"]').hide();
        $modal.find('[data-cp-step="1"]').show();
        $modal.find('[data-cp-title="step2"]').hide();
        $modal.find('[data-cp-title="step1"]').show();
    });

    // ----- Alterna método de pagamento -----
    $modal.on('click', '.cp-pay-pill', function () {
        const m = $(this).data('method');
        $modal.find('.cp-pay-pill').removeClass('active');
        $(this).addClass('active');
        $('#cpBillingType').val(m);
        showPayBlock(m);
        validateConfirm();
    });

    function showPayBlock(method) {
        $modal.find('[data-cp-pay]').hide();
        $modal.find(`[data-cp-pay="${method}"]`).show();
    }

    // ----- Validação ao vivo do botão Confirmar -----
    $modal.on('input', '#cpForm input', validateConfirm);
    function validateConfirm() {
        const m = $('#cpBillingType').val();
        let ok = true, hint = '';

        if (m === 'CREDIT_CARD') {
            const $form = $('#cpForm');
            const need = ['credit_card_name','credit_card_number','credit_card_expiry_month','credit_card_expiry_year','credit_card_ccv'];
            const missing = need.filter(n => !($form.find(`[name="${n}"]`).val() || '').trim());
            if (missing.length) {
                ok = false;
                hint = 'Preencha os dados do cartão para continuar.';
            }
        }
        $('#cpConfirmBtn').prop('disabled', !ok);
        $('#cpConfirmHint').text(hint || 'Tudo certo. Pode confirmar.');
    }

    // ----- Cupom -----
    $('#cpCouponApply').on('click', function () {
        const coupon = ($('#cpCouponInput').val() || '').trim();
        const planId = $('#cpPlanId').val();
        const $fb = $('#cpCouponFeedback').removeClass('text-success text-danger').text('');

        if (!coupon || !planId) {
            $fb.addClass('text-danger').text('Insira um cupom.');
            return;
        }
        fetch('/validate-coupon', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ coupon, plan_id: planId })
        })
        .then(r => r.json())
        .then(d => {
            if (d.valid) {
                $('#cpCouponHidden').val(coupon);
                $fb.addClass('text-success').text(d.message || 'Cupom aplicado.');
                if (d.discounted_value) {
                    $modal.find('[data-cp-selected-value]').text(String(d.discounted_value));
                }
            } else {
                $('#cpCouponHidden').val('');
                $fb.addClass('text-danger').text(d.message || 'Cupom inválido.');
            }
        })
        .catch(() => $fb.addClass('text-danger').text('Erro ao validar o cupom.'));
    });
})();
</script>
