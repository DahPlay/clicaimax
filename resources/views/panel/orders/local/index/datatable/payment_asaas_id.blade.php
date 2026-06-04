@if (empty($item->subscription_asaas_id))
    <span class="badge bg-secondary">SEM FATURA</span>
@else
    <a href="javascript:;"
       class="ap-btn-action btn-invoice btn-edit"
       data-id="{{ $item->id }}"
       data-url="/orders/invoice"
       data-modal-size="modal-xl"
       title="Ver fatura no Asaas">
        <i class="far fa-eye mr-1"></i> Ver Fatura
    </a>
@endif
