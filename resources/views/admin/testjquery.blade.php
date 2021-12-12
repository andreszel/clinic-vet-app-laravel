@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-12 form-group" id="validation-message"></div>

        Test jQuery

        <button class="btn btn-sm btn-primary delete">Test click</button>
    </div>
</div>
@endsection

@section('javascript')
const deleteUrl = "{{ route('test.ajax') }}";
@endsection
@section('js-files')
<script src="{{ asset('js/delete.js') }}"></script>
@endsection