@extends('layouts.tabler')

@section('title', 'Cetak Berita Acara - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Detail Berita Acara
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <button type="button" class="btn btn-primary" onclick="window.print();">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
        Cetak
      </button>
      <a href="{{ route('incident-reports.index') }}" class="btn btn-secondary">
        Kembali
      </a>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body p-5">
    <div class="text-center mb-4">
        <h2>BERITA ACARA KERUSAKAN / KEHILANGAN TOOL</h2>
        <h4>Nomor: BA-{{ str_pad($incidentReport->id, 4, '0', STR_PAD_LEFT) }}</h4>
    </div>
    
    <p>Pada hari ini tanggal <strong>{{ \Carbon\Carbon::parse($incidentReport->created_at)->translatedFormat('d F Y') }}</strong>, telah dilaporkan mengenai kerusakan atau kehilangan alat kerja dengan rincian sebagai berikut:</p>
    
    <table class="table table-bordered mt-3">
        <tr>
            <th width="30%">Nama Mekanik</th>
            <td>{{ $incidentReport->mechanic->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jabatan</th>
            <td>{{ $incidentReport->mechanic->jabatan->nama_jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Alat (Tool)</th>
            <td>{{ $incidentReport->transaction->tool->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kronologi / Catatan</th>
            <td>{{ $incidentReport->kronologi }}</td>
        </tr>
    </table>
    
    <p class="mt-4">Demikian berita acara ini dibuat untuk dapat diproses lebih lanjut sesuai dengan ketentuan yang berlaku di perusahaan.</p>
    
    <div class="row mt-5 text-center">
        <div class="col-4">
            <p>Pelapor,</p>
            <br><br><br>
            <p><strong>{{ $incidentReport->mechanic->nama_lengkap ?? '(.....................)' }}</strong></p>
        </div>
        <div class="col-4 offset-4">
            <p>Mengetahui/Menyetujui,</p>
            <br><br><br>
            <p><strong>{{ $incidentReport->approver->name ?? '(.....................)' }}</strong></p>
            <p><small>Status: {{ $incidentReport->status_approval }}</small></p>
        </div>
    </div>
  </div>
</div>
@endsection