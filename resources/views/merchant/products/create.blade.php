@extends('layouts.app')

@section('content')
<div class="container">

    @if ($errors->all())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col mb-4">
            <h1 class="text-left">Add new product</strong></h1>
        </div>
    </div>

    <form action="{{ route('merchant.products.store') }}" method="POST" enctype="multipart/form-data" class="was-validated">
        @csrf
        @method('POST')
        <div class="row">
            <div class="col-9 mb-4">
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" class="form-control is-invalid" placeholder="Name" min="4" max="50" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control is-invalid" placeholder="Short description" max="255" name="description" value="{{ old('description') }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="validatedCustomFile" name="image">
                            <label class="custom-file-label" for="validatedCustomFile">Upload photo</label>
                            <div class="invalid-feedback">Feedback to add</div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <input type="number" class="form-control" placeholder="Price" min="1" max="100" step="0.01" name="price" value="{{ old('price') }}" required>
                    </div>

                    <div class="col-md-3">
                        <select name="category_id" class="form-control" value="{{ old('category_id') }}"" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <a class="btn btn-secondary mb-2" href="{{ route('merchant.index') }}" role="button">Back to Dashboard</a>
                        <input class="btn btn-success mb-2" type="submit" value="Add product" />
                </div>
            </div>
        </div>

    </form>
</div>
@endsection