@extends('backend/template/app')

@section('content')
@php
    $role = App\Models\Privilage::getRoleKodeForAuthenticatedUser();
@endphp
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Need Approval</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Need Approval</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="accordion">
                            @if ($datatransaksi->isEmpty())
                                <div class="text-center p-4">
                                    <h3>Belum ada transaksi</h3>
                                </div>
                            @else
                                @foreach ($datatransaksi as $index => $dt)
                                    <div class="card 
                                    {{ $dt->status_transaksi == 1 ? 'card-light' : ($dt->status_transaksi == 2 ? 'card-success' : 'card-danger') }}">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                <a class="d-block w-100" data-toggle="collapse" href="#collapse{{ $index }}">
                                                    {{ $dt->user->name ?? 'Unknown User' }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse{{ $index }}" class="collapse {{ $index == 0 ? 'show' : '' }}" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span><i class="fas fa-calendar"></i> Tanggal</span>
                                                        <span class="badge badge-light badge-pill">
                                                            {{ date('d F Y', strtotime($dt->checkin)) }}
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span><i class="fas fa-clock"></i> Time</span>
                                                        <span class="badge badge-light badge-pill">
                                                            {{ date('h:i A', strtotime($dt->checkin)) }} - {{ date('h:i A', strtotime($dt->checkout)) }}
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span><i class="fas fa-tags"></i> Total Bayar</span>
                                                        <span class="badge badge-light badge-pill total-bayar" data-total="{{ $dt->detail->sum('harga') }}">
                                                            Rp {{ number_format($dt->detail->sum('harga'), 0, ',', '.') }}
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span><i class="fas fa-thumbtack"></i> Lantai & Meja</span>
                                                        <span class="badge badge-light badge-pill text-left">
                                                            @if ($dt->detail->isNotEmpty())
                                                                @php
                                                                    // Group meja by lantai
                                                                    $lantaiGrouped = $dt->detail->groupBy('lantai_id');
                                                                @endphp

                                                                @foreach ($lantaiGrouped as $lantaiId => $details)
                                                                    @php
                                                                        $lantaiName = $details->first()->lantai->nama_lantai ?? '-';
                                                                        $mejaNames = $details->map(fn($detail) => $detail->meja->nama_meja ?? '-')->unique()->implode(', ');
                                                                    @endphp
                                                                    <strong>{{ $lantaiName }}</strong>: {{ $mejaNames }} <br>
                                                                @endforeach
                                                            @else
                                                                -
                                                            @endif
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span><i class="fas fa-check-circle"></i> Status</span>
                                                        <span class="d-flex gap-2">
                                                            <!-- Status Button -->
                                                            <button class="btn btn-sm 
                                                                {{ $dt->status_transaksi == 1 ? 'btn-warning' : ($dt->status_transaksi == 2 ? 'btn-success' : 'btn-danger') }}">
                                                                {{ $dt->status_transaksi == 1 ? 'Booking' : ($dt->status_transaksi == 2 ? 'Selesai Booking' : 'Batal Booking') }}
                                                            </button>
                                                            &nbsp;
                                                            <!-- Sudah DP Button (Only Show if ApproveBy is not empty) -->
                                                            @if ($dt->approveby != '')
                                                                <button class="btn btn-sm btn-success">
                                                                    Sudah DP
                                                                </button>
                                                            @endif
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span></span>
                                                        <span class="badge badge-light badge-pill">
                                                            <button class="btn btn-sm btn-success approve-btn 
                                                            {{ ($dt->status_transaksi != 1 || $dt->approveby != '') ? 'd-none' : '' }}" data-id="{{ $dt->id }}">
                                                                <i class="fa fa-check-square"></i> Approve
                                                            </button>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script>
        $(document).on('click', '.approve-btn', function() {
            var transaksiId = $(this).data('id');
            var totalBayar = $(this).closest('.card-body').find('.total-bayar').data('total'); // Fetch total bayar
            var url = '{{ route('transaksi.approve', ':id') }}'.replace(':id', transaksiId);

            Swal.fire({
                title: 'Masukkan DP (Down Payment)',
                input: 'number',
                inputAttributes: {
                    min: 0,
                    step: 1000,
                    required: true
                },
                showCancelButton: true,
                confirmButtonText: 'OK',
                inputValidator: (value) => {
                    if (!value || value < 0) {
                        return 'DP tidak boleh kosong atau negatif!';
                    }
                    if (parseInt(value) > totalBayar) {
                        return 'DP tidak boleh lebih besar dari total bayar (Rp ' + new Intl.NumberFormat('id-ID').format(totalBayar) + ')!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var dpValue = result.value;
                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: {
                            _token: "{{ csrf_token() }}",
                            dp: dpValue
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Approved!',
                                text: 'Transaksi berhasil disetujui.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            var errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                            Swal.fire('Error!', errorMessage, 'error');
                        }
                    });
                }
            });
        });

        // Finish Button Click
        $(document).on('click', '.finish-btn', function() {
            var transaksiId = $(this).data('id');
            var url = '{{ route('transaksi.finish', ':id') }}'.replace(':id', transaksiId);

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tandai transaksi ini sebagai selesai.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesai'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            Swal.fire({
                                title: 'Selesai!',
                                text: 'Transaksi berhasil diselesaikan.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            var errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                            Swal.fire('Error!', errorMessage, 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.delete-btn', function() {
            var transaksiId = $(this).data('id');
            var url = '{{ route('transaksi.destroy', ':id') }}'.replace(':id', transaksiId);

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Batalkan transaksi ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, batal!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { "_token": "{{ csrf_token() }}" },
                        success: function(response) {
                            Swal.fire({
                                title: 'Batal Booking!',
                                text: response.success,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            var errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus.';
                            Swal.fire('Error!', errorMessage, 'error');
                        }
                    });
                }
            });
        });
    </script>
</div>
@endsection
