@extends('layout')

@section('styles')
    <style>
        body {
            height: 100vh;
            display: flex;
        }

        main {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <main>
        <h1 class="h3 mb-3 fw-normal">No access - no fun :-P</h1>
        <div class="mt-5 mb-3">Changed you mind?</div>
        <a href="<?= route('oauth.twitch.login') ?>" class="w-100 btn btn-lg btn-primary">Sign in with Twitch
            <img src="https://static.twitchcdn.net/assets/favicon-32-e29e246c157142c94346.png">
        </a>
        <p class="mt-5 mb-3 text-muted">
            © 2021 <a href="https://www.linkedin.com/in/yuri-grigoriev-06342095/" target="_blank">Yuri Grigoriev</a>
        </p>
    </main>
@endsection
