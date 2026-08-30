@extends('client.schoolPanel.layouts.master')

@section('title', 'Events')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Events</h4>
                <div class="page-title-right">
                    <a href="{{ route('school.events.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="fas fa-plus"></i> Add New Event
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

                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-8">
                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $status == 'upcoming' ? 'active' : '' }}" href="{{ route('school.events.index', ['status' => 'upcoming']) }}">
                                            Upcoming
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $status == 'ongoing' ? 'active' : '' }}" href="{{ route('school.events.index', ['status' => 'ongoing']) }}">
                                            Ongoing
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $status == 'completed' ? 'active' : '' }}" href="{{ route('school.events.index', ['status' => 'completed']) }}">
                                            Completed
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $status == 'cancelled' ? 'active' : '' }}" href="{{ route('school.events.index', ['status' => 'cancelled']) }}">
                                            Cancelled
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-end">
                                    <div class="dropdown">
                                        <button class="btn btn-light dropdown-toggle" type="button" id="programFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                            Filter by Program
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="programFilter">
                                            <li><a class="dropdown-item" href="{{ route('school.events.index', ['status' => $status]) }}">All Programs</a></li>
                                            @foreach($programs as $program)
                                                <li><a class="dropdown-item {{ request('program_id') == $program->id ? 'active' : '' }}" href="{{ route('school.events.index', ['status' => $status, 'program_id' => $program->id]) }}">{{ $program->title }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(count($events) > 0)
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Program</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Featured</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $key => $event)
                                        <tr>
                                            <td>{{ $events->firstItem() + $key }}</td>
                                            <td>
                                                @if($event->image_path)
                                                    <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="rounded" width="50">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $event->title }}</td>
                                            <td>
                                                @if($event->program)
                                                    <a href="{{ route('school.programs.show', $event->program_id) }}">{{ $event->program->title }}</a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $event->event_date->format('M d, Y') }}</td>
                                            <td>{{ $event->location }}</td>
                                            <td>
                                                @if($event->is_featured)
                                                    <span class="badge bg-info">Featured</span>
                                                @else
                                                    <span class="badge bg-light text-dark">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('school.events.show', $event->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('school.events.edit', $event->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('school.events.destroy', $event->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this event?')">
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
                            {{ $events->appends(request()->except('page'))->links() }}
                        </div>
                    @else
                        <div class="text-center p-5">
                            <div class="mb-3">
                                <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                            </div>
                            <h5>No events found</h5>
                            <p class="text-muted">Start by adding your first event</p>
                            <a href="{{ route('school.events.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Event
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection