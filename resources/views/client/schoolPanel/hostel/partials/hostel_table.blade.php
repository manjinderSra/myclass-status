@if(isset($hostels) && count($hostels) > 0)
    @foreach($hostels as $hostel)
        <tr class="hover:bg-gray-50 transition-colors" 
            data-id="{{ $hostel->id }}" data-name="{{ $hostel->name }}" data-type="{{ $hostel->type }}" 
            data-address="{{ $hostel->address }}" data-intake="{{ $hostel->intake }}"
            data-description="{{ $hostel->description ?? '' }}" data-status="{{ $hostel->status }}">
            <td class="px-6 py-4">{{ $hostel->name }}</td>
            <td class="px-6 py-4">{{ $hostel->type }}</td>
            <td class="px-6 py-4">{{ $hostel->address }}</td>
            <td class="px-6 py-4">{{ $hostel->intake }}</td>
            <td class="px-6 py-4">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $hostel->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $hostel->status ? 'Active' : 'Inactive' }}
                </span>
            </td>
            <td class="px-6 py-4">{{ $hostel->created_at->format('Y-m-d') }}</td>
            <td class="px-6 py-4">
                <button class="text-indigo-600 hover:text-indigo-900 font-medium editHostelBtn">Edit</button>
                <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteHostelBtn">Delete</button>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="7" class="px-6 py-4 text-center">No hostels found</td>
    </tr>
@endif 