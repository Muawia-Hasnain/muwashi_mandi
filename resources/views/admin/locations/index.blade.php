@extends('layouts.app')
@section('title', 'Manage Locations')

@section('content')
<div class="flex-between mb-3">
    <h1 style="font-size:1.5rem; font-weight:700;"><i class="fas fa-map-marker-alt" style="color:var(--primary);"></i> Manage Locations</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

@if(session('success'))
    <div style="background:#dcfce3; color:#166534; padding:1rem; border-radius:8px; margin-bottom:1.5rem;">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#fee2e2; color:#991b1b; padding:1rem; border-radius:8px; margin-bottom:1.5rem;">
        {{ session('error') }}
    </div>
@endif
@if($errors->any())
    <div style="background:#fee2e2; color:#991b1b; padding:1rem; border-radius:8px; margin-bottom:1.5rem;">
        <ul style="margin:0; padding-left:1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
    {{-- Districts Section --}}
    <div>
        <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
            <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:1rem;"><i class="fas fa-city" style="color:var(--accent);"></i> Add District</h2>
            <form action="{{ route('admin.locations.districts.store') }}" method="POST" style="display:flex; gap:0.5rem;">
                @csrf
                <input type="text" name="name" class="form-input" placeholder="e.g. Attock" required>
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </div>

        <div class="card table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>District Name</th>
                        <th>Tehsils Count</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($districts as $district)
                        <tr>
                            <td><strong>{{ $district->name }}</strong></td>
                            <td>{{ $district->tehsils_count }}</td>
                            <td>
                                <form action="{{ route('admin.locations.districts.destroy', $district) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this district?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" {{ $district->tehsils_count > 0 ? 'disabled title="Remove tehsils first"' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tehsils Section --}}
    <div>
        <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
            <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:1rem;"><i class="fas fa-map-pin" style="color:var(--accent);"></i> Add Tehsil</h2>
            <form action="{{ route('admin.locations.tehsils.store') }}" method="POST" style="display:flex; flex-direction:column; gap:0.8rem;">
                @csrf
                <select name="district_id" class="form-select" required>
                    <option value="">Select District...</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                    @endforeach
                </select>
                <div style="display:flex; gap:0.5rem;">
                    <input type="text" name="name" class="form-input" placeholder="e.g. Pindi Gheb" required>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>

        <div class="card table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tehsil Name</th>
                        <th>District</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tehsils as $tehsil)
                        <tr>
                            <td><strong>{{ $tehsil->name }}</strong></td>
                            <td>{{ $tehsil->district->name ?? 'N/A' }}</td>
                            <td>
                                <form action="{{ route('admin.locations.tehsils.destroy', $tehsil) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
