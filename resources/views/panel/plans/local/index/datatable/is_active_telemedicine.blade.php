@if ($item->is_active_telemedicine)
    <a href='#' class='btn-is-active' data-id="{{ $item->id }}" data-is-active="0"
       data-toggle="tooltip" data-placement="bottom" title="Telemedicina Sim">
        <i class="fas fa-toggle-on fa-2x text-success"></i>
    </a>
@else
    <a href='#' class='btn-is-active' data-id="{{ $item->id }}" data-is-active="1"
       data-toggle="tooltip" data-placement="bottom" title="Telemedicina Não">
        <i class="fas fa-toggle-off fa-2x text-secondary"></i>
    </a>
@endif
