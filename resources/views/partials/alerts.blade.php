@if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif

@if (session('failed'))
    <div class="alert alert-danger">
        {{ session('failed') }}
    </div>
@endif
