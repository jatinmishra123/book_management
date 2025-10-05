@foreach ($students as $student)
    <tr>
        <td>
            <input type="checkbox" name="student_id[]" value="{{ $student->id }}" class="student-checkbox">
        </td>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $student->name }}</td>
        <td>{{ $student->email }}</td>
        <td>{{ $student->phone }}</td>
        <td>{{ $student->enrollment_date }}</td>
        <td>{{ $student->payment_status }}</td>
        <td>
            {{-- Edit Button --}}
            <button class="btn btn-sm btn-info edit-btn" data-id="{{ $student->id }}">
                <i class="ri-edit-line"></i>
            </button>
            {{-- Delete Button --}}
            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $student->id }}">
                <i class="ri-delete-bin-line"></i>
            </button>
        </td>
    </tr>
@endforeach