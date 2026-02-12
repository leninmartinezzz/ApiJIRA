{{-- resources/views/jira/tester.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <title>JIRA API - Dashboard</title>

    <link rel="icon" type="image/png" href="https://grupocobeca.atlassian.net/jira-favicon-scaled.png">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {
            --jira-blue: #0052cc;
            --jira-green: #36b37e;
            --jira-yellow: #ffab00;
            --jira-red: #ff5630;
            --jira-gray: #dfe1e6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #dddcdc 0%, #d4d2d2 100%);
            min-height: 100vh;
            padding: 10px;
        }

        /* Responsive containers */
        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 15px;
            margin-bottom: 20px;
        }

        /* ===== NUEVOS ESTILOS PARA VISTA DE ISSUE ESPECÍFICO ===== */
/* Soluciona el problema de texto recortado en Asignado/Reportado */

.issue-detail-card .table {
    min-width: auto !important;
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
}

.issue-detail-card .table td,
.issue-detail-card .table th {
    white-space: normal !important;
    word-wrap: break-word !important;
    word-break: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
    padding: 10px 8px;
    vertical-align: top;
    border: 1px solid #dee2e6;
}

.issue-detail-card .table th {
    width: 35%;
    font-weight: 600;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.issue-detail-card .table td {
    width: 65%;
    background-color: white;
}

/* Celdas específicas para asignado/reportado */
.issue-detail-card .assignee-cell,
.issue-detail-card .reporter-cell {
    word-break: break-word;
    white-space: normal !important;
    line-height: 1.5;
    font-weight: 500;
}

/* Badges dentro de la tarjeta */
.issue-detail-card .status-badge,
.issue-detail-card .priority-badge {
    white-space: normal !important;
    word-break: break-word;
    text-align: center;
    display: inline-block;
    max-width: 100%;
    padding: 6px 12px;
}

/* Título del issue */
.issue-title {
    word-break: break-word;
    white-space: normal !important;
    line-height: 1.4;
    font-size: 1rem;
    font-weight: 600;
    padding-right: 10px;
}

/* Descripción del issue */
.issue-description {
    max-height: 200px;
    overflow-y: auto;
    white-space: pre-wrap;
    word-break: break-word;
    font-size: 13px;
    line-height: 1.6;
    padding: 12px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid var(--jira-blue);
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
}

/* ===== RESPONSIVE PARA MÓVILES ===== */
@media (max-width: 768px) {
    .issue-detail-card .table th {
        width: 40%;
        font-size: 12px;
        padding: 8px 6px;
    }

    .issue-detail-card .table td {
        width: 60%;
        font-size: 12px;
        padding: 8px 6px;
    }

    .issue-detail-card .card-body {
        padding: 12px !important;
    }

    .issue-title {
        font-size: 0.95rem;
    }

    .issue-description {
        font-size: 12px;
        padding: 10px;
        max-height: 180px;
    }
}

/* ===== VISTA EN MÓVIL MUY PEQUEÑO (STACK VERTICAL) ===== */
@media (max-width: 576px) {
    .issue-detail-card .table,
    .issue-detail-card .table tbody,
    .issue-detail-card .table tr,
    .issue-detail-card .table td,
    .issue-detail-card .table th {
        display: block;
        width: 100%;
    }

    .issue-detail-card .table tr {
        margin-bottom: 12px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    .issue-detail-card .table th {
        background-color: #f1f3f5;
        padding: 8px 12px 4px 12px;
        font-size: 11px;
        color: #495057;
        border-bottom: none;
        width: 100%;
    }

    .issue-detail-card .table td {
        padding: 4px 12px 10px 12px;
        border-bottom: none;
        width: 100%;
    }

    .issue-detail-card .table tr:last-child td {
        border-bottom: none;
    }

    .issue-title {
        font-size: 0.9rem;
        white-space: normal;
    }
}

/* ===== MEJORAS EN LA TARJETA ===== */
.issue-detail-card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: none;
    overflow: hidden;
}

.issue-detail-card .card-header {
    background: linear-gradient(90deg, var(--jira-blue), #0047b3);
    border-bottom: none;
    padding: 14px 16px;
}

.issue-detail-card .card-body {
    background: white;
}

/* ===== BOTONES EN LA TARJETA ===== */
.issue-detail-card .btn {
    transition: all 0.2s ease;
}

.issue-detail-card .btn-jira {
    background: var(--jira-blue);
    color: white;
    border: none;
}

.issue-detail-card .btn-jira:hover {
    background: #0047b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 82, 204, 0.3);
}

.issue-detail-card .btn-outline-primary {
    border: 1px solid var(--jira-blue);
    color: var(--jira-blue);
}

.issue-detail-card .btn-outline-primary:hover {
    background: var(--jira-blue);
    color: white;
}

/* ===== ANIMACIÓN PARA COPIAR ===== */
.btn-success {
    background: var(--jira-green) !important;
    border-color: var(--jira-green) !important;
    color: white !important;
}

/* ===== SIN ASIGNAR DESTACADO ===== */
.issue-detail-card .text-danger {
    font-weight: 600;
    background: rgba(255, 86, 48, 0.1);
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
}

        @media (min-width: 768px) {
            body {
                padding: 20px;
            }
            .main-container {
                padding: 25px;
            }
        }

        /* Header responsive */
        .header-section {
            background: linear-gradient(90deg, var(--jira-blue), #0065ff);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        @media (min-width: 768px) {
            .header-section {
                padding: 20px;
            }
        }

        .header-section h1 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        @media (min-width: 768px) {
            .header-section h1 {
                font-size: 2rem;
                margin-bottom: 0;
            }
        }

        .header-section p {
            font-size: 0.85rem;
        }

        @media (min-width: 768px) {
            .header-section p {
                font-size: 1rem;
            }
        }

        /* Stats cards responsive */
        .stat-card {
            text-align: center;
            padding: 15px 10px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            height: 100%;
        }

        @media (min-width: 768px) {
            .stat-card {
                padding: 20px;
                margin-bottom: 0;
            }
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: var(--jira-blue);
            word-break: break-word;
        }

        @media (min-width: 768px) {
            .stat-number {
                font-size: 32px;
            }
        }

        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (min-width: 768px) {
            .stat-label {
                font-size: 14px;
                letter-spacing: 1px;
            }
        }

        /* Cards custom */
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            height: 100%;
        }

        .card-custom:hover {
            transform: translateY(-5px);
        }

        /* Botones responsive */
        .btn-jira, .btn-outline-jira {
            padding: 8px 16px;
            font-size: 14px;
            white-space: nowrap;
        }

        @media (max-width: 576px) {
            .btn-jira, .btn-outline-jira {
                padding: 6px 12px;
                font-size: 13px;
                width: 100%;
                margin-bottom: 5px;
            }

            .d-flex.gap-2 {
                flex-direction: column;
            }

            .d-flex.gap-2 .btn {
                width: 100%;
            }
        }

        @media (min-width: 768px) {
            .btn-jira, .btn-outline-jira {
                padding: 10px 20px;
            }
        }

        /* JQL Input responsive */
        .jql-input {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px;
        }

        @media (min-width: 768px) {
            .jql-input {
                font-size: 14px;
            }
        }

        /* Result box responsive */
        .result-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            border: 2px dashed #dee2e6;
            max-height: 600px;
            overflow-x: auto;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (min-width: 768px) {
            .result-box {
                padding: 20px;
                max-height: 500px;
            }
        }

        /* Tablas responsive */
        .table-responsive {
            border-radius: 8px;
            margin-bottom: 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            min-width: 800px;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 12px;
                min-width: 650px;
            }
        }

        .table td, .table th {
            white-space: nowrap;
            padding: 10px 8px;
            vertical-align: middle;
        }

        @media (max-width: 576px) {
            .table td, .table th {
                padding: 8px 5px;
            }
        }

        /* Issue row */
        .issue-row {
            border-left: 4px solid var(--jira-blue);
            transition: all 0.3s ease;
        }

        .issue-row:hover {
            background: #f5f9ff;
            transform: translateX(5px);
        }

        /* Badges responsive */
        .status-badge, .priority-badge {
            padding: 4px 8px;
            font-size: 11px;
            white-space: nowrap;
            display: inline-block;
        }

        @media (min-width: 768px) {
            .status-badge {
                padding: 5px 12px;
                font-size: 12px;
            }
            .priority-badge {
                padding: 4px 10px;
                font-size: 11px;
            }
        }

        /* Tabs responsive */
        .nav-tabs {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            padding-bottom: 0;
            margin-bottom: -1px;
        }

        .nav-tabs .nav-item {
            white-space: nowrap;
        }

        .nav-tabs .nav-link {
            border: 1px solid transparent;
            padding: 10px 12px;
            font-size: 13px;
        }

        @media (min-width: 768px) {
            .nav-tabs .nav-link {
                padding: 10px 16px;
                font-size: 14px;
            }
            .nav-tabs {
                overflow-x: visible;
                flex-wrap: wrap;
            }
        }

        .tab-content {
            padding: 15px;
            background: white;
            border-radius: 0 0 10px 10px;
            border: 1px solid #dee2e6;
            border-top: none;
        }

        @media (min-width: 768px) {
            .tab-content {
                padding: 20px;
            }
        }

        /* Quick action buttons responsive */
        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .quick-actions .btn {
            flex: 1 1 calc(50% - 4px);
            font-size: 12px;
            padding: 8px 5px;
        }

        @media (min-width: 576px) {
            .quick-actions .btn {
                flex: 0 1 auto;
                font-size: 13px;
                padding: 8px 12px;
            }
        }

        /* Modals responsive */
        .modal-dialog {
            margin: 10px;
            max-width: calc(100% - 20px);
        }

        @media (min-width: 576px) {
            .modal-dialog {
                margin: 1.75rem auto;
                max-width: 500px;
            }
            .modal-lg {
                max-width: 800px;
            }
        }

        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* JSON viewer responsive */
        .json-viewer {
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 11px;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 12px;
            border-radius: 8px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            max-height: 350px;
        }

        @media (min-width: 768px) {
            .json-viewer {
                font-size: 13px;
                padding: 15px;
                max-height: 400px;
            }
        }

        /* Loading spinner */
        .loading-spinner {
            width: 35px;
            height: 35px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--jira-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 15px auto;
        }

        @media (min-width: 768px) {
            .loading-spinner {
                width: 40px;
                height: 40px;
            }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Issue key */
        .issue-key {
            font-weight: bold;
            color: var(--jira-blue);
            cursor: pointer;
            display: inline-block;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (min-width: 576px) {
            .issue-key {
                max-width: 120px;
            }
        }

        @media (min-width: 768px) {
            .issue-key {
                max-width: none;
            }
        }

        .issue-key:hover {
            text-decoration: underline;
        }

        /* Grid system improvements */
        .row {
            margin-left: -8px;
            margin-right: -8px;
        }

        .row > [class*="col-"] {
            padding-left: 8px;
            padding-right: 8px;
        }

        @media (min-width: 768px) {
            .row {
                margin-left: -12px;
                margin-right: -12px;
            }
            .row > [class*="col-"] {
                padding-left: 12px;
                padding-right: 12px;
            }
        }

        /* Alert messages responsive */
        .alert {
            padding: 12px;
            margin-bottom: 15px;
            font-size: 14px;
            word-wrap: break-word;
        }

        @media (min-width: 768px) {
            .alert {
                padding: 16px;
                font-size: 15px;
            }
        }

        /* List group responsive */
        .list-group-item {
            padding: 12px 15px;
            font-size: 14px;
        }

        @media (max-width: 576px) {
            .list-group-item {
                padding: 10px 12px;
                font-size: 13px;
            }
        }

        /* Progress bar */
        .progress {
            height: 20px;
            margin-bottom: 10px;
        }

        /* Fix for Bootstrap columns on very small devices */
        @media (max-width: 575.98px) {
            .col-xs-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .d-flex.justify-content-end {
                justify-content: flex-start !important;
                margin-top: 10px;
            }

            .text-end {
                text-align: left !important;
            }

            .header-section .row.align-items-center > div:last-child {
                margin-top: 10px;
            }
        }

        /* Hide scrollbar for clean look on desktop */
        @media (min-width: 768px) {
            .nav-tabs::-webkit-scrollbar {
                display: none;
            }
        }

        /* Utility classes for responsive spacing */
        .responsive-mt {
            margin-top: 10px;
        }

        @media (min-width: 768px) {
            .responsive-mt {
                margin-top: 0;
            }
        }

        .responsive-mb {
            margin-bottom: 15px;
        }

        @media (min-width: 768px) {
            .responsive-mb {
                margin-bottom: 20px;
            }
        }

        /* Improve touch targets on mobile */
        @media (max-width: 768px) {
            button,
            .btn,
            .issue-key,
            .nav-link,
            .list-group-item,
            .table tbody tr {
                cursor: pointer;
                min-height: 44px;
                min-width: 44px;
            }

            .btn-sm {
                min-height: 38px;
                min-width: 38px;
            }
        }

        /* Assignment modal specific */
        #tickets-to-assign {
            max-height: 250px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (min-width: 768px) {
            #tickets-to-assign {
                max-height: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header - Ya mejorado con clases responsive -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <h1 class="text-center text-md-start">
                        <i class="fa-brands fa-atlassian"></i> JIRA API
                    </h1>
                    <p class="mb-0 text-center text-md-start">
                        <span class="d-none d-sm-inline">Panel de pruebas para la API de JIRA - </span>
                        <strong>{{ config('services.jira.base_url') }}</strong>
                    </p>
                </div>
                <div class="col-12 col-md-4 text-center text-md-end">
                    <div class="d-flex flex-wrap flex-md-nowrap justify-content-center justify-content-md-end gap-2">
                        <button class="btn btn-light w-100 w-md-auto" onclick="testConnection()" id="connection-btn">
                            <i class="fas fa-plug"></i> <span class="d-none d-sm-inline">Probar Conexión</span><span class="d-sm-none">Test</span>
                        </button>
                        <button class="btn btn-light w-100 w-md-auto" onclick="getProjects()">
                            <i class="fas fa-project-diagram"></i> <span class="d-none d-sm-inline">Proyectos</span><span class="d-sm-none">Proy</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Row - Mejorado para responsive -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-number" id="total-issues">0</div>
                    <div class="stat-label">Total Issues</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-number" id="total-projects">0</div>
                    <div class="stat-label">Proyectos</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-number" id="unassigned-count">0</div>
                    <div class="stat-label">Sin Asignar</div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-danger w-100" onclick="assignUnassignedTickets()" id="assign-btn">
                            <i class="fas fa-user-plus"></i> <span class="d-none d-sm-inline">Asignar</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-number">
                        <span class="badge bg-danger" id="status-badge">Desconectado</span>
                    </div>
                    <div class="stat-label">Estado</div>
                </div>
            </div>
        </div>

        <!-- Main Content with Tabs - Mejorado responsive -->
        <div class="main-container p-0 p-md-3">
            <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="search-tab" data-bs-toggle="tab" data-bs-target="#search" type="button" role="tab">
                        <i class="fas fa-search"></i> <span class="d-none d-sm-inline">Búsqueda</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects" type="button" role="tab">
                        <i class="fas fa-folder"></i> <span class="d-none d-sm-inline">Proyectos</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="quickview-tab" data-bs-toggle="tab" data-bs-target="#quickview" type="button" role="tab">
                        <i class="fas fa-eye"></i> <span class="d-none d-sm-inline">Vista Rápida</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tester-tab" data-bs-toggle="tab" data-bs-target="#tester" type="button" role="tab">
                        <i class="fas fa-vial"></i> <span class="d-none d-sm-inline">Tester</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="dashboardTabsContent">
                <!-- Tab 1: Búsqueda Avanzada - Mejorado responsive -->
                <div class="tab-pane fade show active" id="search" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-filter"></i> Consulta JQL</label>
                                <input type="text" class="form-control jql-input" id="jql"
                                       value="ORDER BY created DESC" placeholder="Ingresa tu consulta JQL...">
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-list-ol"></i> Límite</label>
                                <input type="number" class="form-control" id="limit" value="20" min="1" max="100">
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary btn-jira w-100" onclick="searchIssues()">
                                <i class="fas fa-search"></i> <span class="d-none d-sm-inline">Buscar</span>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-bolt"></i> Consultas Predefinidas</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-success btn-outline-jira btn-sm flex-fill flex-md-grow-0" onclick="setJQLTicketsrecientes()">
                                <i class="fas fa-clock"></i> <span class="d-none d-sm-inline">Recientes</span>
                            </button>
                            <button class="btn btn-primary btn-outline-jira btn-sm flex-fill flex-md-grow-0" onclick="setJQLTicketsenprogreso()">
                                <i class="fas fa-sync-alt"></i> <span class="d-none d-sm-inline">En Progreso</span>
                            </button>
                            <button class="btn btn-warning btn-outline-jira btn-sm flex-fill flex-md-grow-0" onclick="setJQLTicketsesperandorespuesta()">
                                <i class="fas fa-hourglass-half"></i> <span class="d-none d-sm-inline">Esperando Respuesta</span>
                            </button>
                            <button class="btn btn-secondary btn-outline-jira btn-sm flex-fill flex-md-grow-0" onclick="setJQLTicketscorporativos()">
                                <i class="fas fa-building"></i> <span class="d-none d-sm-inline">Tickets Corporativos</span>
                            </button>
                            <button class="btn btn-info btn-outline-jira btn-sm flex-fill flex-md-grow-0" onclick="setJQLFarmaciaSinAsignar()">
                                <i class="fas fa-pills"></i> <span class="d-none d-sm-inline">Sin Asignar</span>
                            </button>
                            <button class="btn btn-danger btn-outline-jira btn-sm flex-fill flex-md-grow-0" onclick="setJQLTicketsAltaprioridad()">
                                <i class="fas fa-exclamation-triangle"></i> <span class="d-none d-sm-inline">Alta Prioridad</span>
                            </button>
                        </div>
                    </div>

                    <div class="result-box">
                        <h5 class="h6 h5-md"><i class="fas fa-table"></i> Resultados de la Búsqueda</h5>
                        <div id="search-result">
                            <div class="text-center py-4">
                                <i class="fas fa-search fa-2x text-muted mb-3"></i>
                                <p class="text-muted small">Ingresa una consulta JQL y haz clic en "Buscar"</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Proyectos -->
                <div class="tab-pane fade" id="projects" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                        <h5 class="h6 h5-md mb-0"><i class="fas fa-project-diagram"></i> Lista de Proyectos</h5>
                        <button class="btn btn-primary btn-jira btn-sm w-100 w-md-auto" onclick="getProjects()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                    <div class="result-box">
                        <div id="projects-result">
                            <div class="text-center py-4">
                                <i class="fas fa-folder fa-2x text-muted mb-3"></i>
                                <p class="text-muted small">Cargando proyectos...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Vista Rápida - Mejorado responsive -->
                <div class="tab-pane fade" id="quickview" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-ticket-alt"></i> Buscar Issue Específico</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="issueKey" placeholder="Ej: COB-123" value="COB-">
                                    <button class="btn btn-jira" onclick="getIssue()">
                                        <i class="fas fa-search"></i> <span class="d-none d-sm-inline">Buscar</span>
                                    </button>
                                </div>
                            </div>
                            <div id="issue-result" class="mt-3"></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-chart-bar"></i> Estadísticas Rápidas</label>
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" onclick="runQuickQuery('total')">
                                        <i class="fas fa-hashtag me-2"></i> Total de Issues
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" onclick="runQuickQuery('unassigned')">
                                        <i class="fas fa-user-slash me-2"></i> Issues sin Asignar
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" onclick="runQuickQuery('recent')">
                                        <i class="fas fa-clock me-2"></i> Issues últimos 7 días
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" onclick="runQuickQuery('highpriority')">
                                        <i class="fas fa-exclamation-circle me-2"></i> Alta Prioridad
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Tester API -->
                <div class="tab-pane fade" id="tester" role="tabpanel">
                    <div class="mb-3">
                        <h5 class="h6 h5-md"><i class="fas fa-vial"></i> Pruebas de Endpoints</h5>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button class="btn btn-outline-jira btn-sm flex-fill flex-md-grow-0" onclick="testEndpoint('/test')">
                                <i class="fas fa-plug"></i> /test
                            </button>
                            <button class="btn btn-outline-jira btn-sm flex-fill flex-md-grow-0" onclick="testEndpoint('/projects')">
                                <i class="fas fa-folder"></i> /projects
                            </button>
                        </div>
                    </div>

                    <div class="result-box">
                        <h6 class="small"><i class="fas fa-code"></i> Respuesta de la API</h6>
                        <div id="api-response" class="json-viewer">
                            <pre class="mb-0 small">// La respuesta de la API aparecerá aquí...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales - Ya responsive por Bootstrap -->
    <div class="modal fade" id="issueModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6 h5-md" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    Cargando...
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title h6 h5-md"><i class="fas fa-user-plus"></i> Asignación Masiva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <h6 class="small"><i class="fas fa-info-circle"></i> Usuarios disponibles</h6>
                            <div class="alert alert-info p-2 p-md-3">
                                <ul class="mb-0 small" id="users-list">
                                    <!-- Lista de usuarios se llenará dinámicamente -->
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <h6 class="small"><i class="fas fa-cogs"></i> Configuración</h6>
                            <div class="mb-2">
                                <label class="form-label small">Estrategia</label>
                                <select class="form-select form-select-sm" id="assignment-strategy">
                                    <option value="round-robin">Round Robin</option>
                                    <option value="random">Aleatorio</option>
                                    <option value="first-available">Primer Disponible</option>
                                    <option value="Oscar-Rivas">Oscar Rivas</option>
                                    <option value="Lenin-Martinez">Lenin Martinez</option>
                                    <option value="Luis-Fernandez">Luis Fernandez</option>
                                    <option value="Luis-Cardenas">Luis Cárdenas</option>
                                    <option value="Armando-Sandoval">Armando Sandoval</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Límite por usuario</label>
                                <input type="number" class="form-control form-control-sm" id="limit-per-user" value="5" min="1" max="20">
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h6 class="small"><i class="fas fa-tasks"></i> Tickets a Asignar</h6>
                        <div id="tickets-to-assign" class="bg-light p-2 p-md-3 rounded small">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin"></i> Cargando tickets...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="executeAssignment()">
                        <i class="fas fa-play"></i> Ejecutar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts (sin cambios) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Tu JavaScript original sin cambios
        const API_BASE = 'https://apijira-production.up.railway.app/api/jira';
        let currentStats = { total: 0, unassigned: 0, projects: 0 };
        const usuariosFarmacia = [
            { id: '712020:537e8b9f-a906-480d-892e-654c3fc9b353', nombre: 'Oscar Enrique Rivas Gonzalez' },
            { id: '712020:221ce705-244d-4a0a-8f20-4775f6aa7a07', nombre: 'Lenin Martínez' },
            { id: '712020:6f7f47d9-7e60-4562-b7c9-aa873600c941', nombre: 'Luis Fernandez' },
            { id: '712020:8a013538-df10-439c-8c2b-5800d84dca6c', nombre: 'Luis Miguel Cárdenas Herrera' },
            { id: '712020:36804383-1c8c-43ce-aad3-27d34b0b1466', nombre: 'Armando Jose Sandoval Urdaneta' }
        ];
        let ticketsSinAsignar = [];

        // ... (resto de tu JavaScript sin cambios)
        // Inicializar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            testConnection();
        });

        function updateConnectionStatus(connected) {
            const badge = document.getElementById('status-badge');
            const btn = document.getElementById('connection-btn');

            if (connected) {
                badge.className = 'badge bg-success';
                badge.textContent = 'Conectado';
                btn.innerHTML = '<i class="fas fa-check-circle"></i> <span class="d-none d-sm-inline">Conectado</span><span class="d-sm-none">OK</span>';
                btn.className = 'btn btn-success w-100 w-md-auto';
            } else {
                badge.className = 'badge bg-danger';
                badge.textContent = 'Desconectado';
                btn.innerHTML = '<i class="fas fa-plug"></i> <span class="d-none d-sm-inline">Probar Conexión</span><span class="d-sm-none">Test</span>';
                btn.className = 'btn btn-light w-100 w-md-auto';
            }
        }

        function updateStats() {
            document.getElementById('total-issues').textContent = currentStats.total;
            document.getElementById('unassigned-count').textContent = currentStats.unassigned;
            document.getElementById('total-projects').textContent = currentStats.projects;
        }

        function showLoading(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.innerHTML = `
                    <div class="text-center py-4">
                        <div class="loading-spinner"></div>
                        <p class="mt-2 small">Cargando...</p>
                    </div>
                `;
            }
        }

        function showError(elementId, error) {
            const element = document.getElementById(elementId);
            if (element) {
                element.innerHTML = `
                    <div class="alert alert-danger small">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Error:</strong> ${error}
                    </div>
                `;
            }
        }

        // 🔗 1. Probar Conexión
        async function testConnection() {
            updateConnectionStatus(false);
            showLoading('search-result');

            try {
                const response = await axios.get(`${API_BASE}/test`);

                if (response.data.success) {
                    updateConnectionStatus(true);

                    if (response.data.total_issues) {
                        currentStats.total = response.data.total_issues;
                        updateStats();
                    }

                    document.getElementById('search-result').innerHTML = `
                        <div class="alert alert-success small">
                            <h5 class="h6"><i class="fas fa-check-circle"></i> ${response.data.message}</h5>
                            <p class="mb-0 small"><strong>Detalles:</strong></p>
                            <ul class="mb-0 small">
                                <li>Total issues: ${response.data.total_issues || 'N/A'}</li>
                                <li>Issues en respuesta: ${response.data.issues_found || 'N/A'}</li>
                                <li>Tipo: ${response.data.debug_info?.class_name || 'N/A'}</li>
                            </ul>
                        </div>
                    `;
                } else {
                    updateConnectionStatus(false);
                    showError('search-result', response.data.error || 'Error desconocido');
                }
            } catch (error) {
                updateConnectionStatus(false);
                showError('search-result', error.message);
            }
        }

        // 📁 2. Obtener Proyectos
        async function getProjects() {
            showLoading('projects-result');

            try {
                const response = await axios.get(`${API_BASE}/projects`);

                if (response.data.success && response.data.data) {
                    let html = `
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Key</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    response.data.data.forEach(project => {
                        html += `
                            <tr>
                                <td><strong class="issue-key" onclick="searchProject('${project.key}')">${project.key}</strong></td>
                                <td>${project.name}</td>
                                <td>${project.description || '<em class="text-muted">-</em>'}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary py-1 px-2" onclick="searchProject('${project.key}')">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;

                    document.getElementById('projects-result').innerHTML = html;
                    currentStats.projects = response.data.count;
                    updateStats();
                }
            } catch (error) {
                showError('projects-result', error.response?.data?.error || error.message);
            }
        }

        // 🔍 3. Buscar Issues
        async function searchIssues() {
            const jql = document.getElementById('jql').value;
            const limit = document.getElementById('limit').value;

            showLoading('search-result');

            try {
                const response = await axios.get(`${API_BASE}/issues`, {
                    params: { jql, limit }
                });

                let html = `
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
                        <div>
                            <span class="badge bg-primary">${response.data.total || 0} issues</span>
                            <span class="ms-2 text-muted small">Mostrando ${response.data.issues?.length || 0}</span>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-primary" onclick="exportResults()">
                                <i class="fas fa-download"></i> Exportar
                            </button>
                        </div>
                    </div>
                `;

                if (jql.includes('assignee = EMPTY') && jql.includes('Cobeca-usuarios-farmacia')) {
                    const unassignedCount = response.data.issues?.filter(issue => !issue.assignee || issue.assignee === 'Sin asignar').length || 0;
                    if (unassignedCount > 0) {
                        html += `
                            <div class="alert alert-warning mb-3 p-2 p-md-3">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                                    <div class="small">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>${unassignedCount} tickets sin asignar</strong>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-danger w-100 w-sm-auto" onclick="assignUnassignedTickets()">
                                            <i class="fas fa-user-plus"></i> Asignar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                }

                if (!response.data.issues || response.data.issues.length === 0) {
                    html += `
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle"></i> No se encontraron issues.
                        </div>
                    `;

                } else {
                    html += `
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Resumen</th>
                                        <th>Estado</th>
                                        <th>Prioridad</th>
                                        <th>Asignado</th>
                                        <th>Creado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    response.data.issues.forEach(issue => {
                        const statusClass = getStatusClass(issue.status);
                        const priorityClass = getPriorityClass(issue.priority);

                        if(issue.status.toLowerCase() === 'esperando soporte' && issue.assignee === 'Sin asignar') {
                        html += `
                            <tr class="issue-row">
                                <td>
                                    <strong class="issue-key" onclick="viewIssueDetail('${issue.key}')">${issue.key}</strong>
                                </td>
                                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ${escapeHtml(issue.summary)}
                                </td>
                                <td><span class="status-badge ${statusClass}">${issue.status}</span></td>
                                <td><span class="priority-badge ${priorityClass}">${issue.priority}</span></td>
                                <td>${issue.assignee || '<span class="text-danger">Sin asignar</span>'}</td>
                                <td><small>${formatDateShort(issue.created.date)}</small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary py-1 px-2" onclick="viewIssueDetail('${issue.key}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary py-1 px-2" onclick="assignUnassignedTicketsSpecify('${issue.key}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }else{
                            html += `
                            <tr class="issue-row">
                                <td>
                                    <strong class="issue-key" onclick="viewIssueDetail('${issue.key}')">${issue.key}</strong>
                                </td>
                                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ${escapeHtml(issue.summary)}
                                </td>
                                <td><span class="status-badge ${statusClass}">${issue.status}</span></td>
                                <td><span class="priority-badge ${priorityClass}">${issue.priority}</span></td>
                                <td>${issue.assignee || '<span class="text-danger">Sin asignar</span>'}</td>
                                <td><small>${formatDateShort(issue.created.date)}</small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary py-1 px-2" onclick="viewIssueDetail('${issue.key}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }
                    });



                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                }

                document.getElementById('search-result').innerHTML = html;

                currentStats.total = response.data.max_results || 0;
                currentStats.unassigned = response.data.issues?.filter(issue => !issue.assignee || issue.assignee === 'Sin asignar').length || 0;
                updateStats();

            } catch (error) {
                showError('search-result', error.response?.data?.error || error.message);
            }
        }

      // 📄 4. Ver Issue Específico - VERSIÓN CORREGIDA (CON WRAP)
async function getIssue() {
    const issueKey = document.getElementById('issueKey').value.trim();
    if (!issueKey) {
        alert('Por favor ingresa un código de issue (ej: PROY-123)');
        return;
    }

    console.log(issueKey);

    showLoading('issue-result');

    try {
        const response = await axios.get(`${API_BASE}/issue/${issueKey}`);


        if (response.data.success) {
            const issue = response.data.data;

            // 🔴 IMPORTANTE: Forzar que el asignado y reportero NO se recorten
            let assigneeText = '';
            if (issue.assignee) {
                // Si es un objeto con displayName
                if (typeof issue.assignee === 'object' && issue.assignee.displayName) {
                    assigneeText = issue.assignee.displayName;
                }
                // Si es string directo
                else if (typeof issue.assignee === 'string') {
                    assigneeText = issue.assignee;
                }
                // Si tiene accountId pero necesitamos el nombre
                else {
                    assigneeText = JSON.stringify(issue.assignee);
                }
            } else {
                assigneeText = '<span class="text-danger fw-bold">Sin asignar</span>';
            }

            let reporterText = '';
            if (issue.reporter) {
                if (typeof issue.reporter === 'object' && issue.reporter.displayName) {
                    reporterText = issue.reporter.displayName;
                } else if (typeof issue.reporter === 'string') {
                    reporterText = issue.reporter;
                } else {
                    reporterText = JSON.stringify(issue.reporter);
                }
            } else {
                reporterText = '<span class="text-muted">N/A</span>';
            }

            let html = `
                <div class="card issue-detail-card">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 issue-title">
                            <i class="fas fa-ticket-alt"></i> ${issue.key}: ${escapeHtml(issue.summary)}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Columna Información General -->
                            <div class="col-12 col-md-6">
                                <h6 class="section-title"><i class="fas fa-info-circle me-2"></i>Información General</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Estado:</th>
                                        <td><span class="status-badge ${getStatusClass(issue.status)}">${issue.status || 'N/A'}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Prioridad:</th>
                                        <td><span class="priority-badge ${getPriorityClass(issue.priority)}">${issue.priority || 'N/A'}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Proyecto:</th>
                                        <td class="wrap-cell">${issue.project?.name || issue.project || 'N/A'}</td>
                                    </tr>
                                    <tr>
                                        <th>Creado:</th>
                                        <td class="wrap-cell">${formatDate(issue.created?.date || issue.created)}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Columna Asignación - ESTA ES LA QUE ESTABA RECORTADA -->
                            <div class="col-12 col-md-6">
                                <h6 class="section-title"><i class="fas fa-users me-2"></i>Asignación</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Asignado a:</th>
                                        <td class="assignee-cell">
                                            ${typeof assigneeText === 'string' && assigneeText.includes('Sin asignar')
                                                ? assigneeText
                                                : `<span class="fw-bold assignee-name">${escapeHtml(assigneeText)}</span>`}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Reportado por:</th>
                                        <td class="reporter-cell">
                                            <span class="reporter-name">${escapeHtml(reporterText)}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        ${issue.description ? `
                        <div class="mt-4">
                            <h6 class="section-title"><i class="fas fa-align-left me-2"></i>Descripción</h6>
                            <div class="issue-description">
                                ${escapeHtml(issue.description).replace(/\n/g, '<br>')}
                            </div>
                        </div>
                        ` : `
                        <div class="mt-4">
                            <h6 class="section-title"><i class="fas fa-align-left me-2"></i>Descripción</h6>
                            <div class="text-muted fst-italic p-3 bg-light rounded border">
                                Sin descripción
                            </div>
                        </div>
                        `}

                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <a href="https://grupocobeca.atlassian.net/browse/${issue.key}"
                               target="_blank"
                               class="btn btn-jira">
                                <i class="fas fa-external-link-alt"></i> Abrir en Jira
                            </a>
                            <button class="btn btn-outline-primary" onclick="copyIssueKey('${issue.key}')">
                                <i class="fas fa-copy"></i> Copiar Key
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('issue-result').innerHTML = html;
        }
    } catch (error) {
        showError('issue-result', error.response?.data?.error || 'Issue no encontrado');
    }
}

        // 📋 Funciones JQL
        function setJQLFarmaciaSinAsignar() {
            const jql = `project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status NOT IN (Cerrada, Cancelled, "En Progreso", "En revisión", Resuelta, "Derivada a terceros", "Derivada Soporte N2", "Derivada Soporte N 2", Rechazado, "Esperando respuesta del usuario") AND reporter in membersOf("Cobeca-usuarios-farmacia") and assignee = EMPTY order BY createdDate desc`;
            document.getElementById('jql').value = jql;
            searchIssues();
        }

        function setJQLTicketsenprogreso() {
            const jql = `project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status = "En Progreso" ORDER BY createdDate DESC`;
            document.getElementById('jql').value = jql;
            searchIssues();
        }

        function setJQLTicketsesperandorespuesta() {
            const jql = `project = HelpyIT and "Request Type" not in ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status not in (Cerrada, Resuelta, Rechazado) and reporter in membersOf("Cobeca-usuarios-farmacia") AND assignee IN membersOf("Cobeca-usuarios-farmacia") ORDER BY created desc`;
            document.getElementById('jql').value = jql;
            searchIssues();
        }

        function setJQLTicketscorporativos() {
            const jql = `project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status not in (Cerrada, Resuelta, Rechazado, "Derivada a terceros", "Derivada Soporte N2", "Derivada Soporte N 2", "En Progreso") and reporter NOT IN membersOf("Cobeca-usuarios-farmacia") ORDER BY created desc`;
            document.getElementById('jql').value = jql;
            searchIssues();
        }

        function setJQLTicketsrecientes() {
            const jql = `project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") ORDER BY createdDate DESC`;
            document.getElementById('jql').value = jql;
            searchIssues();
        }

        function setJQLTicketsAltaprioridad() {
            const jql = `project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status NOT IN (Cerrada, Cancelled, "En Progreso", "En revisión", Resuelta, "Derivada a terceros", "Derivada Soporte N2", "Derivada Soporte N 2", Rechazado, "Esperando respuesta del usuario") AND assignee = EMPTY and priority = High ORDER BY createdDate DESC`;
            document.getElementById('jql').value = jql;
            searchIssues();
        }

        // 🎯 Función para asignar tickets sin asignar
        async function assignUnassignedTickets() {
            const jql = `project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status NOT IN (Cerrada, Cancelled, "En Progreso", "En revisión", Resuelta, "Derivada a terceros", "Derivada Soporte N2", "Derivada Soporte N 2", Rechazado, "Esperando respuesta del usuario") AND reporter in membersOf("Cobeca-usuarios-farmacia") and assignee = EMPTY order BY createdDate desc`;

            showLoading('search-result');

            try {
                const response = await axios.get(`${API_BASE}/issues`, {
                    params: { jql, limit: 50 }
                });

                ticketsSinAsignar = response.data.issues || [];

                if (ticketsSinAsignar.length === 0) {
                    showError('search-result', 'No hay tickets sin asignar para procesar');
                    return;
                }

                showAssignmentModal();

            } catch (error) {
                showError('search-result', error.response?.data?.error || error.message);
            }
        }

                // 🎯 Función para asignar tickets sin asignar
        async function assignUnassignedTicketsSpecify(issueKey) {
            const jql = `project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status NOT IN (Cerrada, Cancelled, "En Progreso", "En revisión", Resuelta, "Derivada a terceros", "Derivada Soporte N2", "Derivada Soporte N 2", Rechazado, "Esperando respuesta del usuario") AND reporter in membersOf("Cobeca-usuarios-farmacia") and assignee = EMPTY and issue = "${issueKey}" order BY createdDate desc`;

            showLoading('search-result');

            console.log(issueKey);

            try {
                const response = await axios.get(`${API_BASE}/issues`, {
                    params: { jql, limit: 50 }
                });

                ticketsSinAsignar = response.data.issues || [];

                if (ticketsSinAsignar.length === 0) {
                    showError('search-result', 'No hay tickets sin asignar para procesar');
                    return;
                }

                showAssignmentModal();

            } catch (error) {
                showError('search-result', error.response?.data?.error || error.message);
            }
        }

        // 🪟 Mostrar modal de asignación
        function showAssignmentModal() {
            const usersList = document.getElementById('users-list');
            usersList.innerHTML = '';
            usuariosFarmacia.forEach(user => {
                usersList.innerHTML += `<li class="small"><strong>${user.nombre}</strong></li>`;
            });

            const ticketsContainer = document.getElementById('tickets-to-assign');
            ticketsContainer.innerHTML = '';

            if (ticketsSinAsignar.length === 0) {
                ticketsContainer.innerHTML = '<div class="alert alert-info small">No hay tickets para asignar</div>';
                return;
            }

            let ticketsHTML = `
                <p class="small"><strong>${ticketsSinAsignar.length} tickets encontrados:</strong></p>
                <div class="table-responsive">
                    <table class="table table-sm small">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Resumen</th>
                                <th>Creado</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            ticketsSinAsignar.forEach(ticket => {
                ticketsHTML += `
                    <tr>
                        <td><strong>${ticket.key}</strong></td>
                        <td style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${escapeHtml(ticket.summary)}</td>
                        <td><small>${formatDateShort(ticket.created)}</small></td>
                    </tr>
                `;
            });

            ticketsHTML += `
                        </tbody>
                    </table>
                </div>
            `;

            ticketsContainer.innerHTML = ticketsHTML;

            const assignModal = new bootstrap.Modal(document.getElementById('assignModal'));
            assignModal.show();
        }

        // 🚀 Ejecutar asignación
        async function executeAssignment() {
            if (ticketsSinAsignar.length === 0) {
                alert('No hay tickets para asignar');
                return;
            }

            const strategy = document.getElementById('assignment-strategy').value;
            const limitPerUser = parseInt(document.getElementById('limit-per-user').value);

            const assignments = createAssignments(strategy, limitPerUser);

            if (!confirm(`¿Estás seguro de asignar ${assignments.length} tickets?`)) {
                return;
            }

            const modalBody = document.querySelector('#assignModal .modal-body');
            modalBody.innerHTML = `
                <div class="text-center">
                    <div class="loading-spinner"></div>
                    <h6 class="small">Procesando asignación...</h6>
                    <div id="assignment-progress" class="mt-3">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 id="progress-bar" style="width: 0%"></div>
                        </div>
                        <div id="progress-text" class="mt-2 small">0/${assignments.length}</div>
                    </div>
                </div>
            `;

            const results = [];
            let successful = 0;
            let failed = 0;

            for (let i = 0; i < assignments.length; i++) {
                const assignment = assignments[i];

                const progressPercent = Math.round((i + 1) / assignments.length * 100);
                document.getElementById('progress-bar').style.width = `${progressPercent}%`;
                document.getElementById('progress-text').textContent =
                    `${i + 1}/${assignments.length} - ${assignment.issueKey}`;

                try {
                    const result = await assignSingleTicket(assignment.issueKey, assignment.userId);
                    results.push({
                        issueKey: assignment.issueKey,
                        userName: assignment.userName,
                        success: true,
                        message: 'Asignado correctamente'
                    });
                    successful++;
                } catch (error) {
                    results.push({
                        issueKey: assignment.issueKey,
                        userName: assignment.userName,
                        success: false,
                        message: error.message
                    });
                    failed++;
                }
            }

            showAssignmentResults(results, successful, failed);
        }

        // 🎲 Crear asignaciones según estrategia
        function createAssignments(strategy, limitPerUser) {
            const assignments = [];
            let userIndex = 0;
            const userCounts = {};

            usuariosFarmacia.forEach(user => {
                userCounts[user.id] = 0;
            });

            ticketsSinAsignar.forEach(ticket => {
                let user;

                switch(strategy) {
                    case 'round-robin':
                        user = usuariosFarmacia[userIndex % usuariosFarmacia.length];
                        while (userCounts[user.id] >= limitPerUser) {
                            userIndex++;
                            user = usuariosFarmacia[userIndex % usuariosFarmacia.length];
                        }
                        break;

                    case 'random':
                        const availableUsers = usuariosFarmacia.filter(u => userCounts[u.id] < limitPerUser);
                        if (availableUsers.length === 0) break;
                        user = availableUsers[Math.floor(Math.random() * availableUsers.length)];
                        break;

                    case 'Oscar-Rivas':
                        user = usuariosFarmacia[0];
                        console.log(user);
                        break;

                    case 'Lenin-Martinez':
                        user = usuariosFarmacia[1];
                        console.log(user);
                        break;

                    case 'Luis-Fernandez':
                        user = usuariosFarmacia[2];
                        console.log(user);
                        break;

                    case 'Luis-Cardenas':
                        user = usuariosFarmacia[3];
                        console.log(user);
                        break;

                    case 'Armando-Sandoval':
                        user = usuariosFarmacia[4];
                        console.log(user);
                        break;

                    case 'first-available':
                    default:
                        user = usuariosFarmacia.find(u => userCounts[u.id] < limitPerUser) || usuariosFarmacia[0];
                        console.log(user);
                        break;
                }

                if (user) {
                    assignments.push({
                        issueKey: ticket.key,
                        userId: user.id,
                        userName: user.nombre
                    });
                    userCounts[user.id]++;
                    userIndex++;
                }
            });

            return assignments;
        }

        // 📝 Asignar un ticket individual
        async function assignSingleTicket(issueKey, userId) {
            try {
                const response = await axios.post(`${API_BASE}/issue/${issueKey}/assign`, {
                    assigneeId: userId
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (response.data.success) {
                    return {
                        success: true,
                        message: response.data.message || 'Asignado correctamente'
                    };
                } else {
                    throw new Error(response.data.error || 'Error desconocido');
                }

            } catch (error) {
                let errorMessage = `Error asignando ${issueKey}`;

                if (error.response) {
                    errorMessage += `: ${error.response.status} - ${error.response.data?.error || error.response.statusText}`;
                } else if (error.request) {
                    errorMessage += ': No se recibió respuesta del servidor';
                } else {
                    errorMessage += `: ${error.message}`;
                }

                throw new Error(errorMessage);
            }
        }

        // 📊 Mostrar resultados de asignación
        function showAssignmentResults(results, successful, failed) {
            const modalBody = document.querySelector('#assignModal .modal-body');

            let resultsHTML = `
                <div class="alert ${successful > 0 && failed === 0 ? 'alert-success' : failed > 0 ? 'alert-warning' : 'alert-danger'} p-2 p-md-3">
                    <h6 class="small"><i class="fas fa-clipboard-check"></i> Resultados</h6>
                    <p class="small mb-0"><strong>${successful} exitosas</strong> | <strong class="text-danger">${failed} fallidas</strong></p>
                </div>

                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-sm small">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Usuario</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            results.forEach(result => {
                resultsHTML += `
                    <tr class="${result.success ? 'table-success' : 'table-danger'}">
                        <td><strong>${result.issueKey}</strong></td>
                        <td>${result.userName}</td>
                        <td>
                            ${result.success ?
                                '<span class="badge bg-success"><i class="fas fa-check"></i></span>' :
                                '<span class="badge bg-danger"><i class="fas fa-times"></i></span>'
                            }
                        </td>
                    </tr>
                `;
            });

            resultsHTML += `
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex flex-column flex-sm-row gap-2">
                    <button class="btn btn-primary btn-sm w-100 w-sm-auto" onclick="refreshUnassignedTickets()">
                        <i class="fas fa-sync-alt"></i> Actualizar
                    </button>
                    <button class="btn btn-secondary btn-sm w-100 w-sm-auto" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            `;

            modalBody.innerHTML = resultsHTML;
        }

        // 🔄 Refrescar lista de tickets sin asignar
        async function refreshUnassignedTickets() {
            showLoading('search-result');

            try {
                const jql = `project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status NOT IN (Cerrada, Cancelled, "En Progreso", "En revisión", Resuelta, "Derivada a terceros", "Derivada Soporte N2", "Derivada Soporte N 2", Rechazado, "Esperando respuesta del usuario") AND reporter in membersOf("Cobeca-usuarios-farmacia") and assignee = EMPTY order BY createdDate desc`;

                const response = await axios.get(`${API_BASE}/issues`, {
                    params: { jql, limit: 50 }
                });

                ticketsSinAsignar = response.data.issues || [];

                bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
                document.getElementById('jql').value = jql;
                searchIssues();

            } catch (error) {
                showError('search-result', error.message);
            }
        }

        // 🔧 Funciones Auxiliares
        function setJQL(jql) {
            document.getElementById('jql').value = jql;
            document.getElementById('search-tab').click();
            searchIssues();
        }

        function searchProject(projectKey) {
            setJQL(`project = ${projectKey} ORDER BY created DESC`);
        }

        function viewIssueDetail(issueKey) {
            document.getElementById('issueKey').value = issueKey;
            document.getElementById('quickview-tab').click();
            getIssue();
        }

        function getStatusClass(status) {
            if (!status) return 'status-todo';
            const statusLower = status.toLowerCase();
            if (statusLower.includes('resuelta') || statusLower.includes('cerrada')) {
                return 'status-done';
            } else if (statusLower.includes('progreso') || statusLower.includes('progress')) {
                return 'status-progress';
            }
            return 'status-todo';
        }

        function getPriorityClass(priority) {
            if (!priority) return 'priority-medium';
            const priorityLower = priority.toLowerCase();
            if (priorityLower.includes('high') || priorityLower.includes('alta') || priorityLower.includes('critic')) {
                return 'priority-high';
            } else if (priorityLower.includes('low') || priorityLower.includes('baja')) {
                return 'priority-low';
            }
            return 'priority-medium';
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatDateShort(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', {
                day: 'numeric',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function runQuickQuery(type) {
            switch(type) {
                case 'total':
                    setJQL('project IS NOT NULL ORDER BY created DESC');
                    break;
                case 'unassigned':
                    setJQL('project = HelpyIT and "Request Type" NOT IN ("Incidente de Infraestructura (COB)", "Solicitud de Infraestructura (COB)") and status NOT IN (Cerrada, Cancelled, "En Progreso", "En revisión", Resuelta, "Derivada a terceros", "Derivada Soporte N2", "Derivada Soporte N 2", Rechazado, "Esperando respuesta del usuario") AND reporter in membersOf("Cobeca-usuarios-farmacia") and assignee = EMPTY order BY createdDate desc');
                    break;
                case 'recent':
                    setJQL('created >= -7d ORDER BY created DESC');
                    break;
                case 'highpriority':
                    setJQL('priority in (High, Highest, Critical) ORDER BY created DESC');
                    break;
            }
        }

        function testEndpoint(endpoint) {
            showLoading('api-response');

            axios.get(API_BASE + endpoint)
                .then(response => {
                    document.getElementById('api-response').innerHTML = `
                        <pre class="small">${JSON.stringify(response.data, null, 2)}</pre>
                    `;
                })
                .catch(error => {
                    document.getElementById('api-response').innerHTML = `
                        <pre class="text-danger small">${JSON.stringify(error.response?.data || error.message, null, 2)}</pre>
                    `;
                });
        }

        function exportResults() {
            const jql = document.getElementById('jql').value;
            const limit = document.getElementById('limit').value;
            const url = `${API_BASE}/issues?jql=${encodeURIComponent(jql)}&limit=${limit}&export=csv`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
