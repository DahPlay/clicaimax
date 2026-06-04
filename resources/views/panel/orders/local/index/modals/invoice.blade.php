<div class="modal-header">
    <h5 class="modal-title"><i class="fa fa-file-invoice-dollar mr-2"></i>Fatura</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    @if ($url)
        <p class="mb-2">
            Fatura do pedido #{{ $order->id }}.
            <a href="{{ $url }}" target="_blank" rel="noopener" class="ml-2">
                Abrir em nova aba <i class="fa fa-external-link-alt ml-1"></i>
            </a>
        </p>
        <div style="width:100%; height:70vh; border:1px solid #e0e0e0; border-radius:8px; overflow:hidden;">
            <iframe src="{{ $url }}" style="width:100%; height:100%; border:0;" referrerpolicy="no-referrer"></iframe>
        </div>
    @else
        <div class="alert alert-warning mb-0">
            Este pedido ainda não possui fatura gerada no Asaas.
        </div>
    @endif
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
</div>
