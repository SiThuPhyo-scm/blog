@extends('layouts.master')

@section('title', 'Create Post Page')

@section('content')

    <div class="card">
        <div class="card-header">
            <h3>Create A Post</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Post Image</label>
                    <input class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" type="file" name="images[]" multiple>
                    @error('images')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @foreach($errors->get('images.*') as $message)
                    <div class="invalid-feedback">{{ $message[0] }}</div>
                    @endforeach
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Post Title</label>
                    <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" value="{{ old('title') }}">
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Post Body</label>
                    <textarea class="form-control  @error('body') is-invalid @enderror" name="body" rows="5">{{ old('body') }}</textarea>
                    @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Post Cateory</label>
                    <select name="category_ids[]" class="form-select @error('category_ids') is-invalid @enderror" multiple>
                        <option value="">-- select --</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" 
                            @if (in_array($category->id, old('category_ids', [])))
                                selected
                            @endif
                            >{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_ids')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-outline-primary">Create</button>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>

@endsection
