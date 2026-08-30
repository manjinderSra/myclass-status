@extends('client.schoolPanel.layouts.master')

@section('title', 'Program Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Program Details</h4>
                <div class="page-title-right">
                    <div class="btn-group" role="group">
                        <a href="{{ route('school.programs.edit', $program->id) }}" class="btn btn-primary waves-effect waves-light">
                            <i class="fas fa-edit"></i> Edit Program
                        </a>
                        <a href="{{ route('school.programs.index') }}" class="btn btn-secondary waves-effect waves-light">
                            <i class="fas fa-arrow-left"></i> Back to Programs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        @if($program->image_path)
                            <img src="{{ asset('storage/' . $program->image_path) }}" alt="{{ $program->title }}" class="img-fluid rounded" style="max-height: 250px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-3">
                        <h5 class="text-primary">{{ $program->title }}</h5>
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <p class="mb-1"><strong>Status:</strong></p>
                                <span class="badge bg-{{ $program->status === 'active' ? 'success' : 'danger' }} font-size-12">
                                    {{ ucfirst($program->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="mb-1"><strong>Featured:</strong></p>
                                <span class="badge bg-{{ $program->is_featured ? 'info' : 'light text-dark' }} font-size-12">
                                    {{ $program->is_featured ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($program->coordinator)
                    <div class="mt-4">
                        <h5 class="font-size-15">Coordinator Information</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $program->coordinator }}</td>
                                    </tr>
                                    @if($program->coordinator_contact)
                                    <tr>
                                        <th>Contact</th>
                                        <td>{{ $program->coordinator_contact }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <h5 class="font-size-15">Program Statistics</h5>
                        <div class="row">
                            <div class="col-6">
                                <div class="mt-3">
                                    <p class="mb-1 text-truncate"><i class="fas fa-calendar-alt text-primary me-1"></i> Events</p>
                                    <h5 class="text-dark">{{ $program->events->count() }}</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <p class="mb-1 text-truncate"><i class="fas fa-calendar-check text-success me-1"></i> Upcoming</p>
                                    <h5 class="text-dark">{{ $program->events->where('status', 'upcoming')->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Program Description</h5>
                    <div class="border p-3 rounded">
                        {{ $program->description }}
                    </div>
                    
                    <div class="mt-4">
                        <h5 class="card-title mb-3">Program Events</h5>
                        
                        @if($program->events->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($program->events as $event)
                                            <tr>
                                                <td>{{ $event->title }}</td>
                                                <td>{{ $event->event_date->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $event->status === 'upcoming' ? 'warning' : ($event->status === 'ongoing' ? 'success' : ($event->status === 'completed' ? 'info' : 'danger')) }}">
                                                        {{ ucfirst($event->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('school.events.show', $event->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3 border rounded">
                                <p class="text-muted mb-0">No events found for this program.</p>
                                <a href="{{ route('school.events.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Add Event
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 