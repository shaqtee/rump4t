<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Card</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: #f4f4f4; /* Fallback color */
            background-image: url("{{ $url_bg }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 600px;
            height: 500px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            padding: 30px;
        }

        .player-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            display: block;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 50px;
            height: auto;
        }

        .card-details {
            text-align: center;
            margin-top: 20px;
            flex-grow: 1;
            overflow: auto;
        }

        .card-table {
            width: 100%;
            border-collapse: collapse;
        }

        .card-table th,
        .card-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .card-table th {
            background-color: #ffffff;
        }

        .player-name {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .player-id,
        .hcp-index,
        .member-since {
            font-size: 20px;
            margin-bottom: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="card">
            <img src="{{ asset('/images/logo-dgolf3.png') }}" alt="Logo-Dgolf" class="logo">
            <img src="{{ $datas->image }}" alt="Player Image" class="player-image">
            <div class="card-details">
                <h2 class="player-name">{{ $datas->name }}</h2>
                <table class="card-table">
                    <tr>
                        <th>ID</th>
                        <td>:</td>
                        <td>{{ $datas->player_id }}</td>
                    </tr>
                    <tr>
                        <th>HCP Index</th>
                        <td>:</td>
                        <td>{{ $hcp }}</td>
                    </tr>
                    <tr>
                        <th>Member Since</th>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($datas->created_at)->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
