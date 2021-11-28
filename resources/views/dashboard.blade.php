@extends('layout')

@section('styles')
    <style>
        body {
            height: 100vh;
            display: flex;
            background: url("/background.jpg");
        }

        main {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
            text-align: center;
        }
    </style>
    @parent
@endsection

@section('content')
    <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 450px;">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <img src="<?= $user->image_url ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong><?= $user->name ?></strong>
            </a>
            <ul class="dropdown-menu text-small shadow">
                <li><a class="dropdown-item" href="<?= route('signout') ?>">Sign out</a></li>
            </ul>
        </div>
        <hr>
        <div><strong>Last data update:</strong> {{ $populationTime?->updated_at->rawFormat(\DateTimeInterface::RFC2822) }}</div>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            @foreach ($routes as $route)
                <li class="nav-item">
                    <a href="{{ route($route['name'], $route['params'] ?? []) }}"
                       class="nav-link {{ $route['name'] === $currentRoute ? 'active' : 'link-dark' }}">
                        {{ $route['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @include($currentRoute, ['data' => $data['data']])
@endsection
