@if ($item->is_active)
    <a href="javascript:;" class="btn btn-success btn-is-active"
       data-id="{{ $item->id }}"
       data-url="{{ route('panel.plans.toggleActive', $item->id) }}"
       data-toggle="tooltip" data-placement="bottom" title="Plano ativo">
        <i class="fa fa-check"></i>
    </a>
@else
    <a href="javascript:;" class="btn btn-secondary btn-is-active"
       data-id="{{ $item->id }}"
       data-url="{{ route('panel.plans.toggleActive', $item->id) }}"
       data-toggle="tooltip" data-placement="bottom" title="Plano inativo">
        <i class="fa fa-times"></i>
    </a>
@endif
