@extends('master')


@section('content')
{{-- <pre>
    {{ print_r($output) }}
</pre> --}}
<br>
<br>
<div class="row">
    <div class="row">
        <div class="input-field col s12">
        <textarea id="textarea2" class="materialize-textarea" data-length="120"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
        <textarea id="textarea3" class="materialize-textarea" data-length="120"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12">
        <textarea id="textarea4" class="materialize-textarea" data-length="120"></textarea>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    $("textarea").characterCounter();
  });
</script>
@endpush