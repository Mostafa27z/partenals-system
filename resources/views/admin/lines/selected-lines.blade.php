<table>
    <thead>
        <tr>
            <th>رقم الهاتف</th>
            <th>العميل</th>
            <th>المزود</th>
            <th>النظام</th>
            <th>GCode</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lines as $line)
            <tr>
                <td>{{ $line->phone_number }}</td>
                <td>{{ $line->customer->full_name ?? '-' }}</td>
                <td>{{ $line->provider }}</td>
                <td>{{ $line->plan->name ?? '-' }}</td>
                <td>{{ $line->gcode }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
