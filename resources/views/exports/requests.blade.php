<table>
    <thead>
        <tr>
            <th>الرقم</th>
            <th>نوع الطلب</th>
            <th>المشغل</th>
            <th>الحالة</th>
            <th>تاريخ الطلب</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requests as $r)
            <tr>
                <td>{{ $r->line->phone_number ?? '-' }}</td>
                <td>{{ $r->request_type }}</td>
                <td>{{ $r->line->provider ?? '-' }}</td>
                <td>{{ $r->status }}</td>
                <td>{{ $r->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
