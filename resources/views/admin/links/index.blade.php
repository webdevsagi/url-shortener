<!-- inside resources/views/admin/links/index.blade.php -->

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>פאנל ניהול - קישורים</title>
    <style> /* ניתן להוסיף כאן עיצוב CSS בסיסי */ </style>
</head>
<body>
    <h1>ניהול קישורים</h1>

    <form method="GET" action="{{ route(\'admin.links.index\') }}">
        <input type="text" name="search" placeholder="חיפוש לפי slug..." value="{{ request(\'search\') }}">
        <button type="submit">חיפוש</button>
    </form>

    <table border="1" width="100%">
        <thead>
            <tr>
                <th>Slug</th>
                <th>כתובת יעד</th>
                <th>סטטוס</th>
                <th>סך הקלקות</th>
                <th>תאריך יצירה</th>
            </tr>
        </thead>
        <tbody>
@forelse($links as $link)
    <tr>
        <td>{{ $link->slug }}</td>
        <td>{{ $link->target_url }}</td>
        <td>{{ $link->is_active ? \'פעיל\' : \'לא פעיל\' }}</td>
        <td>{{ $link->hits_count }}</td>
        <td>{{ $link->created_at->format(\'Y-m-d H:i\') }}</td>
    </tr>
@empty
    <tr>
        <td colspan="5">לא נמצאו קישורים.</td>
    </tr>
    @endforelse
    </tbody>
    </table>

    {{ $links->links() }}
    </body>
    </html>
