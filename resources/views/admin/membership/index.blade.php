@extends('admin.layout.index')
@section('title', 'Cài đặt')

@section('style')

@endsection

@section('main')
    <main id="main" class="main d-flex flex-column justify-content-center">
        <div class="">
            <h1 class="h3 mb-4 text-gray-800">{{$titlePage}}</h1>
            <hr>
            <form action="{{ route('admin.membership.update') }}" method="POST">
                @csrf
                @foreach($data as $key => $dataItem)
                    <div class="row mt-3">
                        <input type="hidden" name="memberships[{{ $key }}][id]" value="{{ $dataItem->id }}">
                        <div class="col-2">
                            <input class="form-control" name="memberships[{{ $key }}][name]" value="{{ $dataItem->name }}" type="text">
                        </div>
                        <div class="col-10">
                            <input class="form-control" name="memberships[{{ $key }}][discount_percent]" value="{{ $dataItem->discount_percent }}" type="text">
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary mt-3">Lưu</button>
            </form>

        </div>

    </main>
@endsection
@section('script')

@endsection
