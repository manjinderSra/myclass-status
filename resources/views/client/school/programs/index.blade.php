@extends('client.schoolPanel.layouts.master')

@section('title', 'Programs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Programs</h4>
                <div class="page-title-right">
                    <a href="{{ route('school.programs.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="fas fa-plus"></i> Add New Program
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(count($programs) > 0)
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Coordinator</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($programs as $key => $program)
                                        <tr>
                                            <td>{{ $programs->firstItem() + $key }}</td>
                                            <td>
                                                @if($program->image_path)
                                                    <img src="{{ asset('storage/' . $program->image_path) }}" alt="{{ $program->title }}" class="rounded" width="50">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $program->title }}</td>
                                            <td>{{ $program->coordinator ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $program->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($program->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($program->is_featured)
                                                    <span class="badge bg-info">Featured</span>
                                                @else
                                                    <span class="badge bg-light text-dark">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('school.programs.show', $program->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('school.programs.edit', $program->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('school.programs.destroy', $program->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this program?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $programs->links() }}
                        </div>
                    @else
                        <div class="text-center p-5">
                            <div class="mb-3">
                                <i class="fas fa-folder-open fa-3x text-muted"></i>
                            </div>
                            <h5>No programs found</h5>
                            <p class="text-muted">Start by adding your first program</p>
                            <a href="{{ route('school.programs.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Program
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 