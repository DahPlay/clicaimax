<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard | '. config('custom.project_name') }}</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('Auth-Panel/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Auth-Panel/dist/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Auth-Panel/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Auth-Panel/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Auth-Panel/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('Auth-Panel/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <link rel="shortcut icon" href="{{ config('custom.favicon') }}" />
    <link rel="stylesheet" href="{{ asset('Auth-Panel/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Auth-Panel/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .carregando {
            background: url("{{ asset('Auth-Panel/dist/img/spinner.gif') }}") center no-repeat #FFF;
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            opacity: 0.9;
            background-color: #fff;
        }

        /*Select2 ReadOnly Start*/
        select[readonly].select2-hidden-accessible+.select2-container {
            pointer-events: none;
            touch-action: none;
        }

        select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
            background: #eee;
            box-shadow: none;
        }

        select[readonly].select2-hidden-accessible+.select2-container .select2-selection__arrow,
        select[readonly].select2-hidden-accessible+.select2-container .select2-selection__clear {
            display: none;
        }

        .datatable-header {
            display: flex;
            justify-content: space-between;
        }

        .datatable-footer {
            display: flex;
            justify-content: space-between;
        }

        /* Front button responsive Datatable centralizado */
        /* Button responsive Datatable */
        table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>td:first-child:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>th:first-child:before {
            position: relative;
            top: 0px;
            left: 0px;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>td:first-child,
        table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>th:first-child {
            padding-left: 0px;
        }

        /* Front button colvis Datatable green */
        /* Button colvis Datatable green in active */
        button.dt-button.active {
            color: #fff !important;
            background: #28a745 !important;
            border-color: #28a745 !important;
            box-shadow: none !important;
        }

        /* Arrow button colvis */
        .dt-down-arrow {
            margin-left: 5px !important;
        }

        /* Active de nav-legacy */
        /* nav-legacy li.active */
        [class*=sidebar-dark] .nav-legacy .nav-treeview>.nav-item>.nav-link.active,
        [class*=sidebar-dark] .nav-legacy .nav-treeview>.nav-item>.nav-link:focus,
        [class*=sidebar-dark] .nav-legacy .nav-treeview>.nav-item>.nav-link:hover {
            border-left: 3px solid !important;
        }

        [class*=sidebar-dark] .nav-legacy .nav-treeview>.nav-item>.nav-link,
        [class*=sidebar-dark] .nav-legacy .nav-treeview>.nav-item>.nav-link:focus {
            border-left: 3px solid !important;
            border-color: #343a40 !important;
        }

        .sidebar-mini .nav-legacy>.nav-item .nav-link.active .nav-icon,
        .sidebar-mini-md .nav-legacy>.nav-item .nav-link.active .nav-icon {
            margin-left: .2rem;
        }

        .sidebar-mini .nav-legacy>.nav-item .nav-link .nav-icon,
        .sidebar-mini-md .nav-legacy>.nav-item .nav-link .nav-icon {
            margin-left: .2rem;
        }

        /* Solução para abertura por cima de toasts de mensagem */
        .toasts-top-right.fixed {
            position: fixed;
            z-index: 10000 !important;
            top: 10px;
            right: 10px;
        }

        /* ==================================================== */
        /* === Bloco 8: Estilo global unificado (tema verde) ===*/
        /* ==================================================== */

        /* ---- MODAIS ---- */
        .modal-content {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 12px 36px rgba(0,0,0,.18);
        }
        .modal-header {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            color: #fff;
            border-bottom: 0;
            padding: 16px 22px;
        }
        .modal-header .modal-title { font-weight: 700; }
        .modal-header .close,
        .modal-header .close span { color: #fff; opacity: .9; text-shadow: none; }
        .modal-header .close:hover { opacity: 1; }
        .modal-body {
            background: #fff;
            padding: 22px 22px;
        }
        .modal-footer {
            border-top: 1px solid #eef2e8;
            padding: 14px 22px;
            gap: 8px;
        }
        .modal-footer .btn {
            border-radius: 999px;
            padding: 7px 22px;
            font-weight: 600;
        }
        .modal-footer .btn-primary {
            background: linear-gradient(135deg, #1565c0, #0d47a1);
            border: 0;
        }
        .modal-footer .btn-primary:hover { filter: brightness(1.05); }
        .modal-footer .btn-secondary {
            background: #e9ecef;
            color: #495057;
            border: 0;
        }
        .modal-footer .btn-secondary:hover { background: #dde0e3; color: #0d2b4a; }
        .modal-footer .btn-danger {
            background: linear-gradient(135deg, #ef5350, #c62828);
            border: 0;
        }

        /* ---- DATATABLES ---- */
        #table thead th {
            background: #e3f2fd !important;
            color: #0d2b4a !important;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            border-bottom: 2px solid #90caf9;
            font-size: 12.5px;
        }
        #table tbody tr:hover {
            background: #eaf3fc;
        }
        .dataTables_filter input {
            border-radius: 999px !important;
            border: 1.5px solid #90caf9 !important;
            padding: 4px 14px !important;
            outline: none;
        }
        .dataTables_filter input:focus {
            border-color: #1565c0 !important;
            box-shadow: 0 0 0 .15rem rgba(46,125,50,.18);
        }
        .dataTables_length select {
            border-radius: 999px !important;
            border: 1.5px solid #90caf9 !important;
            padding: 2px 24px 2px 12px !important;
        }
        .dataTables_paginate .paginate_button {
            border-radius: 999px !important;
            margin: 0 2px;
        }
        .dataTables_paginate .paginate_button.current,
        .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(135deg, #1565c0, #0d47a1) !important;
            border-color: #0d47a1 !important;
            color: #fff !important;
        }

        /* Botões DT (Excel/PDF/Colvis) */
        .dt-buttons .dt-button {
            background: linear-gradient(135deg, #1565c0, #0d47a1) !important;
            color: #fff !important;
            border: 0 !important;
            border-radius: 999px !important;
            padding: 5px 16px !important;
            margin-right: 6px;
            font-weight: 600;
            font-size: 12.5px;
        }
        .dt-buttons .dt-button:hover {
            filter: brightness(1.08);
            color: #fff !important;
        }

        /* Botão de ação inline (Bloco 7 — Ver Fatura e correlatos) */
        .ap-btn-action {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            background: linear-gradient(135deg, #0d47a1, #082968);
            color: #fff !important;
            text-decoration: none !important;
            transition: filter .15s ease, transform .15s ease;
            border: 0;
        }
        .ap-btn-action:hover {
            filter: brightness(1.12);
            transform: translateY(-1px);
            color: #fff !important;
        }
    </style>

    @yield('headLocal')
</head>
