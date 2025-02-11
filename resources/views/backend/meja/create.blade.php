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
                        <li class="breadcrumb-item"><a href="{{ route('meja.index') }}">Meja</a></li>
                        <li class="breadcrumb-item active">Add</li>
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
                            <h3 class="card-title">Tambah Meja</h3>
                        </div>

                        <!-- Add Form with jQuery Validation -->
                        <form id="meja-form" action="{{ route('meja.store') }}" method="POST">
                            @csrf
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
                                                    <option value="{{ $lt->id }}" {{ old('lantai_id') == $lt->id ? 'selected' : '' }}>{{ $lt->nama_lantai }}</option>
                                                @endforeach
                                            </select>
                                            @error('lantai_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group mandatory">
                                            <label for="nama_meja">Nama Meja</label>
                                            <input type="text" class="form-control" id="nama_meja" name="nama_meja" placeholder="Nama Meja" value="{{ old('nama_meja') }}" required>
                                            @error('nama_meja')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group mandatory">
                                            <label for="harga">Harga</label>
                                            <input type="number" class="form-control" id="harga" name="harga" placeholder="Harga" value="{{ old('harga') }}" required>
                                            @error('harga')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                               
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
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
            // jQuery Validation for form
            $("#meja-form").validate({
                rules: {
                    lantai_id: {
                        required: true
                    },
                    nama_meja: {
                        required: true,
                        minlength: 3,
                        remote: {
                            url: "{{ route('meja.checkNamaMeja') }}",  // Route to check if 'nama_meja' exists
                            type: "GET",
                            data: {
                                lantai_id: function() {
                                    return $("#lantai_id").val();  // Get the lantai_id value
                                },
                                nama_meja: function() {
                                    return $("#nama_meja").val();  // Get the nama_meja value
                                }
                            }
                        }
                    },
                    harga: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    nama_meja: {
                        remote: "This name already exists in the selected floor!"
                    }
                }
            });

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
</div>
@endsection
