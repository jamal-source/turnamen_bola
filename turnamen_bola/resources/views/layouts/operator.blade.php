<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', 'Panel Operator')</title>

	<!-- Google Fonts Inter -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

	@vite(['resources/css/app.css', 'resources/js/app.js'])    @stack('head')
	<style>body{font-family: 'Inter', sans-serif;}</style>
</head>
<body class="h-full bg-slate-50 text-slate-800">

	<div class="min-h-screen flex">
		<!-- Sidebar (desktop) -->
		@include('components.sidebar')

		<!-- Main content -->
		<main class="flex-1 p-6">
			<header class="mb-6">
				<h1 class="text-2xl font-semibold">@yield('header-title', 'Operator')</h1>
			</header>

			<section>
				@yield('content')
			</section>
		</main>
	</div>

	@stack('scripts')
</body>
</html>
