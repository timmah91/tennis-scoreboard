<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('APP_NAME', 'Tennis Scoreboard') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
    <div class="wrapper grid h-screen place-items-center bg-gray-500">
        <div class="place-items-center py-10 px-10 border-black border-4 rounded-xl space-y-10 bg-gray-200">

            <div class="bg-green-400 text-black font-bold py-6 px-10 rounded text-center">{{ $scoreboard->display }}</div>
            <form method="POST" action="/">
                @csrf
                @if(!$scoreboard->complete)
                    <button type="submit" name="playerScore" value="1" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Score player 1</button>
                    <button type="submit" name="playerScore" value="2" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Score player 2</button>
                @else
                    <div class="bg-blue-500 text-white font-bold py-2 px-4 rounded text-center">Congrats!</div>
                @endif
            </form>
        </div>
    </div>
</body>
</html>
