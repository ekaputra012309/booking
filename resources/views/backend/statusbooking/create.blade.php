@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Status Booking</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                        <li class="breadcrumb-item"><a href="{{ route('statusbooking.index') }}">Status Booking</a></li>
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
                            <h3 class="card-title">Tambah Status</h3>
                            {{-- <div class="card-tools">
                                    <a href="{{ route('statusbooking.add') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Data
                            </a>
                        </div> --}}
                    </div>
                    <form action="{{ route('statusbooking.store') }}" method="POST" id="statusbooking-form">
                        @csrf
                        @auth
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        @endauth

                        <div class="card-body">
                            <div class="form-group col-md-4">
                                <label for="nama_status">Nama Status Booking</label>
                                <input type="text" class="form-control" id="nama_status" name="nama_status" placeholder="Nama Status Booking" required>
                            </div>                            
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <a href="{{ route('statusbooking.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection