<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Report</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }
        h1 {
            text-align: center;
            color: rgb(21, 199, 51);
        }
        table {
            width: 75%;
            table-layout: fixed;
            margin: auto;
            border-collapse: separate;
            margin-bottom: 20px;
            border-radius: 10px;
            background-color: #FCF177;
        }
        th, td {  
            width: 50%;
            padding: 8px;
            text-align: center;
            border-radius: 10px;
            font-size: 20px;
            color: #6D1A69;
        }
        th {
            background-color: rgb(27, 199, 21);
        }
        h2 {
            margin-left: 20px;
            text-align: center;
            color: #6D1A69;
        }
        h3 {
            color: white;
        }
        h5 {
            margin-left: 20px;
            text-align: center;
            color: #6D1A69;
        }
    </style>
</head>
<body>
    <h1>Report "Ratings"</h1>
    <h5>Report creation date: {{ $reportGeneratedAt }}</h5>
    <h5>For the period: from {{ $reportStartTime }} to {{ $reportGeneratedAt }}</h5>
    <h2>Methods Invocation Ratings</h2>
    <table>
        <thead>
        <tr>
            <th><h3>Method</h3></th>
            <th><h3>Number of Calls</h3></th>
        </tr>
        </thead>
        <tbody>
        @foreach($method as $m)
            <tr>
                <td>{{ $m->controller_action }}</td>
                <td>{{ $m->total }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    <h2>Entities Changes Ratings</h2>
    <table>
        <thead>
        <tr>
            <th><h3>Entity</h3></th>
            <th><h3>Number of Changes</h3></th>
        </tr>
        </thead>
        <tbody>
        @foreach($entity as $e)
            <tr>
                <td>{{ $e->table }}</td>
                <td>{{ $e->total }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    <h2>Users Requests Ratings</h2>
    <table>
        <thead>
        <tr>
            <th><h3>User (id)</h3></th>
            <th><h3>Number of Requests</h3></th>
        </tr>
        </thead>
        <tbody>
        @foreach($userRequest as $ur)
            <tr>
                <td>{{ $ur->user_id }}</td>
                <td>{{ $ur->total }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    <h2>Users Authentication Ratings</h2>
    <table>
        <thead>
        <tr>
            <th><h3>User (id)</h3></th>
            <th><h3>Number of Logins</h3></th>
        </tr>
        </thead>
        <tbody>
        @foreach($userAuth as $ul)
            <tr>
                <td>{{ $ul->user_id }}</td>
                <td>{{ $ul->total }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    <h2>Users Permissions Ratings</h2>
    <table>
        <thead>
        <tr>
            <th><h3>User (id)</h3></th>
            <th><h3>Number of Permissions</h3></th>
        </tr>
        </thead>
        <tbody>
        @foreach($userPermissions as $up)
            <tr>
                <td>{{ $up->username }}</td>
                <td>{{ $up->total_permissions }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    <h2>Users Changes Ratings<</h2>
    <table>
        <thead>
        <tr>
            <th><h3>User (id)</h3></th>
            <th><h3>Number of Changes</h3></th>
        </tr>
        </thead>
        <tbody>
        @foreach($userChanges as $uc)
            <tr>
                <td>{{ $uc->user_id }}</td>
                <td>{{ $uc->total }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>