<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Links</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; color: #333; }
        .search-form { margin-bottom: 20px; }
        .search-form input { padding: 10px; width: 300px; border: 1px solid #ddd; border-radius: 4px; }
        .search-form button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px; }
        .search-form button:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: 600; }
        tr:hover { background: #f8f9fa; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .pagination { margin-top: 20px; display: flex; gap: 5px; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #333; border-radius: 4px; }
        .pagination .active { background: #007bff; color: white; border-color: #007bff; }
        .logout-btn { float: right; padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .logout-btn:hover { background: #c82333; }
    </style>
</head>
<body>
<div class="container">
    <div style="overflow: auto; margin-bottom: 20px;">
        <h1 style="float: left;">Links Management</h1>
        <form method="POST" action="{{ route('logout') }}" style="float: right;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <form method="GET" action="{{ route('admin.links.index') }}" class="search-form">
        <input type="text" name="search" placeholder="Search by slug..." value="{{ request('search') }}">
        <button type="submit">Search</button>
        @if(request('search'))
            <a href="{{ route('admin.links.index') }}" style="margin-left: 10px; color: #007bff; text-decoration: none;">Clear</a>
        @endif
    </form>

    <table>
        <thead>
        <tr>
            <th>Slug</th>
            <th>Target URL</th>
            <th>Status</th>
            <th>Total Hits</th>
            <th>Created At</th>
        </tr>
        </thead>
        <tbody>
        @forelse($links as $link)
            <tr>
                <td><strong>{{ $link->slug }}</strong></td>
                <td style="max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ $link->target_url }}
                </td>
                <td>
                    @if($link->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </td>
                <td>{{ $link->hits_count }}</td>
                <td>{{ $link->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                    No links found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $links->links() }}
    </div>
</div>
</body>
</html>
