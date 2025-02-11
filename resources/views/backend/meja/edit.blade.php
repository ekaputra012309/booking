@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Meja</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('meja.index') }}">Meja</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Edit Meja</h3>
                        </div>
                        <form action="{{ route('meja.update', $meja->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group mandatory">
                                            <label for="lantai-id-column" class="form-label">Nama Lantai</label>
                                            <select name="lantai_id" id="lantai_id" class="form-control select2bs4">
                                                <option value="">Pilih</option>
                                                @foreach ($datalantai as $lt)
                                                <option value="{{ $lt->id }}" {{ $lt->id == $meja->lantai_id ? 'selected' : '' }}>{{ $lt->nama_lantai }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group mandatory">
                                            <label for="nama_meja">Nama Meja</label>
                                            <input type="text" class="form-control" id="nama_meja" name="nama_meja" value="{{ $meja->nama_meja }}" placeholder="Nama Meja" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group mandatory">
                                            <label for="harga">Harga</label>
                                            <input type="number" class="form-control" id="harga" name="harga" value="{{ $meja->harga }}" placeholder="Harga" required>
                                            <input type="hidden" class="form-control" id="status_id" name="status_id" value="{{ $meja->status_id }}">
                                        </div>
                                    </div>  
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('meja.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
</div>
@endsection