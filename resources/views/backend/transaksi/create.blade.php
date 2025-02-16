@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Booking</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Booking</a></li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tambah Booking</h3>
                        </div>

                        <form action="{{ route('transaksi.store') }}" method="POST" id="barang-masuk-form">
                            @csrf
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth

                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="invoice_number">No Transaksi</label>
                                        <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" name="invoice_number" value="{{ $invoiceNumber }}" placeholder="No PO" readonly>
                                        @error('invoice_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="checkin">Check In</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('checkin') is-invalid @enderror" id="checkin" name="checkin" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>                                        
                                        @error('checkin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="checkout">Check Out</label>                                        
                                        <div class="input-group">
                                        <input type="text" class="form-control @error('checkout') is-invalid @enderror" id="checkout" name="checkout" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @error('checkout')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>                             
                                </div>

                                <!-- Container for Dynamic Barang Items -->
                                <div id="items-container">
                                    <div class="item-row row">
                                        <div class="form-group col-md-3">
                                            <label for="lantai_id">Lantai</label>
                                            <select class="form-control select2bs4 lantai-select" name="items[0][lantai_id]" required>
                                                <option value="" data-harga="" disabled selected>Pilih Lantai</option>
                                                @foreach ($lantais as $lt)
                                                    <option value="{{ $lt->id }}" data-harga="{{ $lt->nama_lantai }}">{{ $lt->nama_lantai }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="meja_id">Meja</label>
                                            <select class="form-control select2bs4 meja-select" name="items[0][meja_id]" required>
                                                <option value="" data-harga="" disabled selected>Pilih Meja</option>
                                                
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="harga">Harga</label>
                                            <input type="text" class="form-control harga-input" name="items[0][harga]" placeholder="Harga" readonly>
                                        </div>
                                        <div class="form-group col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-item" class="btn btn-success btn-sm">Tambah Item</button>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Booking</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>

                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Total Bayar</h3>
                        </div>
                        <div class="card-body">
                            <h3 id="total_bayar"></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $('#checkin').datetimepicker({
                "allowInputToggle": true,
                "showClose": true,
                "showClear": true,
                "showTodayButton": true,
                format: 'MM/DD/YYYY H:mm',
            });

            $('#checkout').datetimepicker({
                "allowInputToggle": true,
                "showClose": true,
                "showClear": true,
                "showTodayButton": true,
                format: 'MM/DD/YYYY H:mm',
            });

            let itemIndex = 1;

            function updateTotalBayar() {
                let total = 0;
                $('.harga-input').each(function() {
                    let harga = parseFloat($(this).val()) || 0;
                    total += harga;
                });
                $('#total_bayar').text('Rp ' + total.toLocaleString('id-ID'));
            }

            function updateOptions() {
                let selectedBarang = [];
                $('[name^="items["][name$="[meja_id]"]').each(function() {
                    const value = $(this).val();
                    if (value) selectedBarang.push(value);
                });

                $('[name^="items["][name$="[meja_id]"]').each(function() {
                    $(this).find('option').each(function() {
                        if (selectedBarang.includes($(this).val()) && !$(this).is(':selected')) {
                            $(this).attr('disabled', 'disabled');
                        } else {
                            $(this).removeAttr('disabled');
                        }
                    });
                });
            }

            $('#add-item').on('click', function() {
                const newItemRow = `
                    <div class="item-row row">
                        <div class="form-group col-md-3">
                            <label for="lantai_id">Lantai</label>
                            <select class="form-control select2bs4 lantai-select" name="items[${itemIndex}][lantai_id]" required>
                                <option value="" disabled selected>Pilih Lantai</option>
                                @foreach ($lantais as $lt)
                                    <option value="{{ $lt->id }}">{{ $lt->nama_lantai }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="meja_id">Meja</label>
                            <select class="form-control select2bs4 meja-select" name="items[${itemIndex}][meja_id]" required>
                                <option value="" data-harga="" disabled selected>Pilih Meja</option>
                                
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="harga">Harga</label>
                            <input type="text" class="form-control harga-input" name="items[${itemIndex}][harga]" placeholder="Harga" readonly>
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>`;

                $('#items-container').append(newItemRow);
                $('.select2bs4').select2({ theme: 'bootstrap4' });
                itemIndex++;
                updateOptions();
            });

            $(document).on('change', '.lantai-select', function() {
                let lantaiId = $(this).val();
                let mejaSelect = $(this).closest('.item-row').find('.meja-select');

                if (lantaiId) {
                    $.ajax({
                        url: "{{ route('getMejaByLantai') }}",
                        type: "GET",
                        data: { lantai_id: lantaiId },
                        success: function(response) {
                            mejaSelect.empty().append('<option value="" disabled selected>Pilih Meja</option>');
                            $.each(response, function(index, meja) {
                                mejaSelect.append(`<option value="${meja.id}" data-harga="${meja.harga}">${meja.nama_meja}</option>`);
                            });
                            mejaSelect.prop('disabled', false);
                            updateOptions();
                            updateTotalBayar();
                        }
                    });
                } else {
                    mejaSelect.empty().append('<option value="" disabled selected>Pilih Meja</option>').prop('disabled', true);
                }
            });


            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                updateOptions();
                updateTotalBayar();
            });

            $(document).on('change', '[name^="items["][name$="[meja_id]"]', function() {
                updateOptions();
            });

            $(document).on('change', '.meja-select', function() {
                const harga = $(this).find('option:selected').data('harga');
                $(this).closest('.item-row').find('.harga-input').val(harga);
                updateTotalBayar();
            });
                       
        });
    </script>

</div>
@endsection
