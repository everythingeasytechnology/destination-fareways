@extends('layouts.admin')

@section('title', 'Import Airports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.airports.index') }}" class="text-decoration-none text-muted">Airports</a></li>
    <li class="breadcrumb-item active" aria-current="page">Import</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Import Airports</h2>
    <p class="text-muted mb-0">Bulk import airports from an Excel or CSV file.</p>
</div>

@if(isset($result))
{{-- ── Import Result ── --}}
<div class="card premium-card border-0 shadow-sm p-4 mb-4">
    <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
        <i class="fa-solid fa-chart-bar text-warning me-2"></i>Import Results
    </h5>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 bg-light text-center py-3 px-2 h-100">
                <div class="fs-2 fw-bold text-navy">{{ $result['total'] }}</div>
                <div class="small text-muted mt-1">Total Records</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 bg-success bg-opacity-10 text-center py-3 px-2 h-100">
                <div class="fs-2 fw-bold text-success">{{ $result['imported'] }}</div>
                <div class="small text-muted mt-1">Imported</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 bg-primary bg-opacity-10 text-center py-3 px-2 h-100">
                <div class="fs-2 fw-bold text-primary">{{ $result['updated'] }}</div>
                <div class="small text-muted mt-1">Updated</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 bg-warning bg-opacity-10 text-center py-3 px-2 h-100">
                <div class="fs-2 fw-bold text-warning">{{ $result['skipped'] }}</div>
                <div class="small text-muted mt-1">Skipped</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 bg-danger bg-opacity-10 text-center py-3 px-2 h-100">
                <div class="fs-2 fw-bold text-danger">{{ $result['failed'] }}</div>
                <div class="small text-muted mt-1">Failed</div>
            </div>
        </div>
    </div>

    @if(($result['imported'] + $result['updated']) > 0)
        <div class="alert alert-success border-0">
            <i class="fa-solid fa-circle-check me-2"></i>
            Successfully processed <strong>{{ $result['imported'] + $result['updated'] }}</strong> airport(s):
            {{ $result['imported'] }} new, {{ $result['updated'] }} updated.
        </div>
    @endif

    @if(!empty($result['errors']))
        <div class="alert alert-danger border-0">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <strong>{{ count($result['errors']) }} error(s) encountered:</strong>
            <ul class="mb-0 mt-2 small">
                @foreach($result['errors'] as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex gap-2 mt-3">
        <a href="{{ route('admin.airports.index') }}" class="btn btn-action rounded-pill px-4">
            <i class="fa-solid fa-list me-2"></i>View All Airports
        </a>
        <a href="{{ route('admin.airports.import.form') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-rotate-left me-2"></i>Import Again
        </a>
    </div>
</div>
@endif

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card premium-card border-0 shadow-sm p-4">
            <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                <i class="fa-solid fa-file-import text-warning me-2"></i>Upload File
            </h5>

            <form action="{{ route('admin.airports.import') }}" method="POST" enctype="multipart/form-data" id="import-form">
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger border-0 mb-3">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-4">
                    <label class="form-label fw-bold">Select Excel / CSV File <span class="text-danger">*</span></label>
                    <div class="upload-zone border-2 border-dashed rounded-3 p-5 text-center position-relative" id="upload-zone"
                         style="border-color:#cdd5df; cursor:pointer; transition:all .2s;">
                        <input type="file" name="file" id="file-input" class="position-absolute opacity-0 w-100 h-100 top-0 start-0"
                               style="cursor:pointer;" accept=".xlsx,.xls,.csv" required>
                        <i class="fa-solid fa-cloud-arrow-up fa-3x text-muted mb-3 opacity-50" id="upload-icon"></i>
                        <p class="fw-semibold text-navy mb-1" id="upload-label">Drag &amp; drop or click to browse</p>
                        <p class="text-muted small mb-0" id="upload-hint">Supports: .xlsx, .xls, .csv &mdash; Max 5MB</p>
                    </div>
                </div>

                <button type="submit" class="btn btn-action w-100 py-2" id="import-btn">
                    <i class="fa-solid fa-file-import me-2"></i>Import Airports
                </button>
            </form>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card premium-card border-0 shadow-sm p-4 mb-4">
            <h5 class="fw-bold text-navy mb-3 border-bottom pb-2">
                <i class="fa-solid fa-circle-info text-warning me-2"></i>Expected Format
            </h5>
            <p class="text-muted small mb-3">Your file must have the following column headers in the first row:</p>
            <div class="table-responsive">
                <table class="table table-sm table-bordered text-center small mb-3">
                    <thead class="table-light">
                        <tr>
                            <th class="font-monospace fw-bold">ST</th>
                            <th class="font-monospace fw-bold">Locid</th>
                            <th class="font-monospace fw-bold">City</th>
                            <th class="font-monospace fw-bold">Airport Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-muted">GA</td>
                            <td class="text-primary fw-bold">ATL</td>
                            <td>Atlanta</td>
                            <td>Hartsfield/Jackson...</td>
                        </tr>
                        <tr>
                            <td class="text-muted">CA</td>
                            <td class="text-primary fw-bold">LAX</td>
                            <td>Los Angeles</td>
                            <td>Los Angeles International</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-grid">
                <a href="{{ route('admin.airports.sample') }}" class="btn btn-outline-primary rounded-pill">
                    <i class="fa-solid fa-file-arrow-down me-2"></i>Download Sample File
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-4 bg-light">
            <h6 class="fw-bold text-navy mb-3"><i class="fa-solid fa-lightbulb text-warning me-2"></i>Import Rules</h6>
            <ul class="small text-muted mb-0 ps-3">
                <li class="mb-1"><strong>IATA Code, City, Airport Name</strong> are required per row.</li>
                <li class="mb-1">If the <strong>IATA code already exists</strong>, the record will be <strong>updated</strong>.</li>
                <li class="mb-1">All imported airports default to <strong>Active</strong> status.</li>
                <li class="mb-1">IATA codes are saved in <strong>uppercase</strong> automatically.</li>
                <li>Rows with missing required fields will be <strong>skipped</strong>.</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    var $zone  = $('#upload-zone');
    var $input = $('#file-input');
    var $label = $('#upload-label');
    var $hint  = $('#upload-hint');
    var $icon  = $('#upload-icon');

    $input.on('change', function () {
        if (this.files && this.files[0]) {
            var name = this.files[0].name;
            var size = (this.files[0].size / 1024).toFixed(1) + ' KB';
            $icon.removeClass('fa-cloud-arrow-up').addClass('fa-file-excel text-success');
            $label.text(name);
            $hint.text(size);
            $zone.css('border-color', '#198754');
        }
    });

    $zone.on('dragover', function (e) {
        e.preventDefault();
        $(this).css('border-color', '#0d6efd').css('background', '#f0f4ff');
    }).on('dragleave', function () {
        $(this).css('border-color', '#cdd5df').css('background', '');
    }).on('drop', function (e) {
        e.preventDefault();
        $(this).css('border-color', '#cdd5df').css('background', '');
        var files = e.originalEvent.dataTransfer.files;
        if (files.length) {
            $input[0].files = files;
            $input.trigger('change');
        }
    });

    $('#import-form').on('submit', function () {
        $('#import-btn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Importing...');
    });
});
</script>
@endsection
