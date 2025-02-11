@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Meja</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                        {{-- <li class="breadcrumb-item"><a href="#">Layout</a></li> --}}
                        <li class="breadcrumb-item active">Meja</li>
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
                        <div class="card-header">
                            <h3 class="card-title"> </h3>
                            <div class="card-tools">
                                <a href="{{ route('meja.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Data
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="width: 30px">Aksi</th>
                                        <th style="width: 20px">No</th>
                                        <th>Nama Lantai</th>
                                        <th>No Meja</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1 @endphp
                                    @foreach ($datameja as $meja)
                                    <tr>
                                        
                                        <td>
                                            <a class="btn btn-xs btn-primary" href="{{ route('meja.edit', $meja->id ?? '-') }}">
                                                <i class="fas fa-edit"></i>
                                            </a> 
                                            <a class="btn btn-xs btn-danger" href="{{ route('meja.destroy', $meja->id ?? '-') }}" data-confirm-delete="true">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $meja->lantai->nama_lantai ?? 'N/A' }}</td>
                                        <td>{{ $meja->nama_meja ?? 'N/A' }}</td>
                                        <td>{{ $meja->harga ?? 'N/A' }}</td>
                                        <td>{{ $meja->status->nama_status ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            scrollY:        "300px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            fixedColumns:   true,
        });
    </script>
</div>
@endsection