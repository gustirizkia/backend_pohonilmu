@extends('layouts.new-admin')

@section('title')
    Edit
@endsection
@section('halaman')
    Edit
@endsection

@section('content')
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form action="{{ route('update-hadiah', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div id="app">
                        <div class="row">
                            <div class="col-md-6">
                                <img :src="gambar" alt="" class="img-fluid w-50 my-2">
                                <br>
                                <label for="">Ganti Gambar</label>
                                <input type="file" class="form-control-file" name="image" @change="changeFile">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Note</label>
                                <input type="text" class="form-control" name="note" value="{{ $item->note }}">
                                <small id="emailHelp" class="form-text text-muted">Judul ataupun deskripsi hadiah.</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Point</label>
                                <input type="number" class="form-control" name="jumlah_point" value="{{ $item->jumlah_point }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection


@push('script')
   <script src="{{ url('backend/vendor/vue/vue.js') }}"></script>
    <script>

        var vm = new Vue({
            el : "#app",
            data : {
                gambar: '{{ $item->image }}',
            },
            mounted(){
                console.log(this.gambar);
            },
             methods: {
                changeFile(event) {
                const file = event.target.files[0]
                this.gambar = URL.createObjectURL(file)
                }
            }
        });
    </script>
@endpush
