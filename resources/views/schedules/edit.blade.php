@extends('layouts/main')

@section('container')
    <div class="lime-container">
        <div class="lime-body">
            <div class="container">
                <div class="row">
                    <div class="col-xl">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Ubah Jadwal</h5>
                                <p>Isi data dengan lengkap dan tepat</p>
                                <form method="POST" action="{{ route('schedules.update', $schedules->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="departureTerminal">Terminal Berangkat</label>
                                                <select class="js-states form-control" name="frombusStations[]"
                                                    id="departureTerminal" style="width: 100%"
                                                    {{ Auth::user()->hasRole('PO') ? 'disabled' : '' }}>
                                                    @foreach ($busStations as $busStation)
                                                        <option value="{{ $busStation->id }}"
                                                            {{ $schedules->from_station_id == $busStation->id ? 'selected' : '' }}>
                                                            {{ $busStation->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('tobusStations.0'))
                                                    <div id="terminal-error-message" class="invalid-feedback"
                                                        style="display: block;">
                                                        Terminal berangkat dan tujuan tidak boleh sama
                                                    </div>
                                                @else
                                                    <div id="terminal-error-message" class="invalid-feedback"
                                                        style="display: none;">
                                                        Terminal berangkat dan tujuan tidak boleh sama
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="arrivalTerminal">Terminal Tujuan</label>
                                                <select class="js-states form-control" name="tobusStations[]"
                                                    id="arrivalTerminal" style="width: 100%"
                                                    {{ Auth::user()->hasRole('PO') ? 'disabled' : '' }}>
                                                    @foreach ($busStations as $busStation)
                                                        <option value="{{ $busStation->id }}"
                                                            {{ $schedules->to_station_id == $busStation->id ? 'selected' : '' }}>
                                                            {{ $busStation->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="Bus">Bus</label>
                                                <select class="js-states form-control" name="busses[]" id="busses"
                                                    style="width: 100%" title="Pilih satu atau lebih sopir"
                                                    {{ Auth::user()->hasRole('PO') ? 'disabled' : '' }}>
                                                    @foreach ($busses as $buss)
                                                        <option value="{{ $buss->id }}"
                                                            {{ $schedules->bus_id == $buss->id ? 'selected' : '' }}>
                                                            {{ $buss->name }} ({{ $buss->class }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="min_price">Harga Minimum Bus</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="min_price"
                                                        id="min_price" placeholder="Harga Minimum" required
                                                        value="{{ old('min_price', $schedules->min_price) }}"
                                                        min="0" {{ Auth::user()->hasRole('PO') ? 'disabled' : '' }}
                                                        oninput="validatePrices()">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Min</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="max_price">Harga Maksimum Bus</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="max_price"
                                                        id="max_price" placeholder="Harga Maksimum" required
                                                        value="{{ old('max_price', $schedules->max_price) }}"
                                                        min="0" {{ Auth::user()->hasRole('PO') ? 'disabled' : '' }}
                                                        oninput="validatePrices()">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Max</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                @if (Auth::user()->hasRole('PO'))
                                                    <label for="price">Penentuan Harga</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="price" id="price" placeholder="Penentuan Harga" required value="{{ old('price', $schedules->price) }}">
                                                    </div>
                                                @else
                                                    <input type="hidden" name="price" value="{{ old('price', $schedules->price) }}">
                                                @endif
                                            </div>
                                        </div>
                                        
                                    </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="time_start">Jam Berangkat</label>
                                                    @php
                                                        $timeStart = \Carbon\Carbon::parse($schedules->time_start);
                                                        $formattedTime = $timeStart->format('H:i');
                                                    @endphp
                                                    <input type="time" class="form-control" name="time_start"
                                                        id="time_start" required value="{{ $formattedTime }}"
                                                        {{ Auth::user()->hasRole('PO') ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="time_arrive">Jam Tiba</label>
                                                    @php
                                                        $timeStart = \Carbon\Carbon::parse($schedules->time_arrive);
                                                        $formattedTime = $timeStart->format('H:i');
                                                    @endphp
                                                    <input type="time" class="form-control" name="time_arrive"
                                                        id="time_arrive" required value="{{ $formattedTime }}"
                                                        {{ Auth::user()->hasRole('PO') ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary float-left mr-2"
                                            data-toggle="modal" data-target="#exampleModal">
                                            Ubah
                                        </button>
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-secondary float-left" data-toggle="modal"
                                            data-target="#exampleModalback">
                                            Kembali
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi
                                                            Perubahan Data</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin mengubah data ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Ubah</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModalback" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabelback" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabelback">Konfirmasi
                                                            Kembali</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin kembali?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                        <a href="{{ route('schedules.index') }}"
                                                            class="btn btn-primary">Ya, Kembali</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
