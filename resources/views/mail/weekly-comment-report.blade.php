<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        h2 { color: #2d3748; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px 12px; text-align: left; }
        th { background-color: #f7fafc; font-weight: 600; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .total { font-weight: bold; background-color: #edf2f7; }
        .period { color: #718096; font-size: 14px; }
    </style>
</head>
<body>
    <h2>Weekly Comment Report</h2>
    <p class="period">Period: {{ $periodStart }} — {{ $periodEnd }}</p>

    <p>Total comments for the week: <strong>{{ $totalComments }}</strong></p>

    <table>
        <thead>
            <tr>
                <th>Blog Post</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report as $item)
                <tr>
                    <td>
                        <a href="{{ url('/post/' . $item['blog_id']) }}">
                            {{ $item['blog_title'] }}
                        </a>
                    </td>
                    <td>{{ $item['comments_count'] }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td>Total</td>
                <td>{{ $totalComments }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
