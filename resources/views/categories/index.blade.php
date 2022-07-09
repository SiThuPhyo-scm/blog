@extends('layouts.master')

@section('title', 'Category Page List')

@section('content')

    <div class="d-flex justify-content-end">
        <a class="btn btn-success" href="{{ route('category.create') }}">Create A Category</a>
    </div>
    @foreach ($categories as $category)
        <div>
            <h3><a href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a></h3>
            January 1, 2021 by Mark
            <div class="d-flex justify-content-end">
                <a href="{{ route('category.edit', $category->id) }}" class="btn btn-outline-success">Edit</a>
                <form action="{{ route('category.destroy', $category->id) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure to delete?')">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-outline-danger ms-2">Delete</button>
                </form>
            </div>
        </div>

        <hr>
    @endforeach
    
@endsection
