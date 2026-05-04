<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modular Architecture — Laravel 13</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/bash.min.js"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            fontFamily: { mono: ['JetBrains Mono', 'Fira Code', 'monospace'] },
        }
    }
}
</script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; }
    .hljs { background: #0d1117; border-radius: 0.5rem; }
    pre code.hljs { padding: 1.25rem 1.5rem; font-size: 0.8125rem; line-height: 1.7; }
    :target { scroll-margin-top: 6rem; }
    .badge { @apply inline-flex items-center px-2 py-0.5 rounded text-xs font-medium; }
    .copy-btn { @apply absolute top-3 right-3 px-2 py-1 text-xs bg-slate-700 hover:bg-slate-600 text-slate-300 rounded transition-colors cursor-pointer select-none; }

    /* ── Sidebar ── */
    .sidebar-group {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #94a3b8;
        padding: 1.4rem 0.75rem 0.4rem;
        margin-top: 2px;
    }
    .sidebar-group:first-child { padding-top: 0.5rem; }

    .sidebar-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0.38rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8375rem;
        color: #475569;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
        margin-bottom: 1px;
    }
    .sidebar-link:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    .sidebar-link.active {
        background: #eef2ff;
        color: #4338ca;
        font-weight: 600;
    }
    .sidebar-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 20%;
        height: 60%;
        width: 3px;
        background: #6366f1;
        border-radius: 0 3px 3px 0;
    }
    .sidebar-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.35;
        flex-shrink: 0;
    }
    .sidebar-link.active .sidebar-dot { opacity: 1; background: #6366f1; }
    .sidebar-link:hover .sidebar-dot { opacity: 0.6; }
</style>
</head>
<body class="bg-white text-slate-900 antialiased">

{{-- ── Top nav ──────────────────────────────────────────────────────── --}}
<header class="fixed top-0 inset-x-0 z-30 h-14 border-b border-slate-200 bg-white/95 backdrop-blur flex items-center px-4 lg:px-6 gap-3">
    {{-- Mobile menu button --}}
    <button onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')" class="lg:hidden p-2 rounded-md hover:bg-slate-100 text-slate-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
        </div>
        <div class="hidden sm:block">
            <span class="font-bold text-slate-900 text-sm">Modular Architecture</span>
        </div>
        <span class="badge bg-indigo-100 text-indigo-700 hidden sm:inline-flex">Laravel 13</span>
    </div>

    {{-- Breadcrumb-style path --}}
    <div class="hidden md:flex items-center gap-1 text-sm text-slate-400 ml-2">
        <span>app</span>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span>Modules</span>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-600 font-medium">*</span>
    </div>

    <div class="ml-auto flex items-center gap-3">
        {{-- Quick links --}}
        <div class="hidden lg:flex items-center gap-1 text-xs text-slate-500 border border-slate-200 rounded-lg px-3 py-1.5 bg-slate-50">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="font-mono">php artisan make:module</span>
        </div>
        <span class="badge bg-emerald-100 text-emerald-700">v1.0</span>
    </div>
</header>

{{-- Mobile sidebar overlay --}}
<div id="mobile-sidebar" class="hidden fixed inset-0 z-40 lg:hidden" onclick="this.classList.add('hidden')">
    <div class="absolute inset-0 bg-slate-900/40"></div>
    <div class="relative w-64 h-full bg-white border-r border-slate-200 overflow-y-auto" onclick="event.stopPropagation()">
        <div class="px-4 py-4 border-b border-slate-100 flex items-center gap-2">
            <div class="w-7 h-7 bg-indigo-600 rounded-md flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </div>
            <span class="font-bold text-sm">Modular Architecture</span>
        </div>
        <nav class="px-3 py-4">
            <div class="sidebar-group"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>Getting Started</div>
            <a href="#overview" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>Overview</a>
            <a href="#quick-start" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>Quick Start</a>
            <a href="#folder-layout" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>Folder Layout</a>
            <div class="sidebar-group"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>Commands</div>
            <a href="#make-module" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>make:module</a>
            <a href="#make-module-model" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>make:module-model</a>
            <div class="sidebar-group"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>Architecture</div>
            <a href="#request-lifecycle" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>Request Lifecycle</a>
            <a href="#platform-services" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>Platform Services</a>
            <a href="#repository-pattern" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>Repository Pattern</a>
            <a href="#routes" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>Routes</a>
            <div class="sidebar-group"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>Reference</div>
            <a href="#naming" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>Naming Conventions</a>
            <a href="#bootstrap" class="sidebar-link" onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"><span class="sidebar-dot"></span>bootstrap/providers.php</a>
        </nav>
    </div>
</div>

<div class="flex pt-14 min-h-screen">

    {{-- ── Sidebar ─────────────────────────────────────────────────── --}}
    <aside class="fixed top-14 left-0 bottom-0 w-64 border-r border-slate-200 overflow-y-auto hidden lg:flex flex-col bg-white">

        {{-- Search hint --}}
        <div class="px-4 py-3 border-b border-slate-100">
            <div class="flex items-center gap-2 bg-slate-100 rounded-lg px-3 py-2 text-slate-400 text-sm cursor-default select-none">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                <span class="text-xs">Quick search…</span>
                <kbd class="ml-auto text-xs bg-slate-200 text-slate-500 px-1.5 py-0.5 rounded font-mono">/</kbd>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 overflow-y-auto">

            {{-- Getting Started --}}
            <div class="sidebar-group">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Getting Started
            </div>
            <a href="#overview" class="sidebar-link"><span class="sidebar-dot"></span>Overview</a>
            <a href="#quick-start" class="sidebar-link"><span class="sidebar-dot"></span>Quick Start</a>
            <a href="#folder-layout" class="sidebar-link"><span class="sidebar-dot"></span>Folder Layout</a>

            {{-- Commands --}}
            <div class="sidebar-group">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Artisan Commands
            </div>
            <a href="#make-module" class="sidebar-link">
                <span class="sidebar-dot"></span>
                <span>make:module</span>
                <span class="ml-auto text-xs bg-indigo-100 text-indigo-600 px-1.5 py-0.5 rounded font-mono leading-none">new</span>
            </a>
            <a href="#make-module-model" class="sidebar-link">
                <span class="sidebar-dot"></span>
                <span>make:module-model</span>
                <span class="ml-auto text-xs bg-violet-100 text-violet-600 px-1.5 py-0.5 rounded font-mono leading-none">add</span>
            </a>

            {{-- Architecture --}}
            <div class="sidebar-group">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                Architecture
            </div>
            <a href="#request-lifecycle" class="sidebar-link"><span class="sidebar-dot"></span>Request Lifecycle</a>
            <a href="#platform-services" class="sidebar-link"><span class="sidebar-dot"></span>Platform Services</a>
            <a href="#repository-pattern" class="sidebar-link"><span class="sidebar-dot"></span>Repository Pattern</a>
            <a href="#routes" class="sidebar-link"><span class="sidebar-dot"></span>Routes</a>

            {{-- Module Files --}}
            <div class="sidebar-group">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                Module Files
            </div>
            <a href="#generated-files" class="sidebar-link"><span class="sidebar-dot"></span>Generated Files</a>
            <a href="#requests" class="sidebar-link"><span class="sidebar-dot"></span>Requests</a>
            <a href="#resources" class="sidebar-link"><span class="sidebar-dot"></span>Resources</a>
            <a href="#views" class="sidebar-link"><span class="sidebar-dot"></span>Views</a>

            {{-- Data --}}
            <div class="sidebar-group">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 4 3h8c2.5 0 4-1 4-3V7c0-2-1.5-3-4-3H8C5.5 4 4 5 4 7z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7c0 2 1.5 3 4 3h8c2.5 0 4-1 4-3"/></svg>
                Data
            </div>
            <a href="#migrations" class="sidebar-link"><span class="sidebar-dot"></span>Migrations</a>
            <a href="#seeding" class="sidebar-link"><span class="sidebar-dot"></span>Seeding</a>

            {{-- Base Module --}}
            <div class="sidebar-group">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                Base Module
            </div>
            <a href="#base-module" class="sidebar-link"><span class="sidebar-dot"></span>What Base Provides</a>
            <a href="#helpers" class="sidebar-link"><span class="sidebar-dot"></span>Helpers &amp; Traits</a>

            {{-- Reference --}}
            <div class="sidebar-group">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Reference
            </div>
            <a href="#naming" class="sidebar-link"><span class="sidebar-dot"></span>Naming Conventions</a>
            <a href="#bootstrap" class="sidebar-link"><span class="sidebar-dot"></span>bootstrap/providers.php</a>

        </nav>

        {{-- Footer --}}
        <div class="px-4 py-3 border-t border-slate-100 bg-slate-50">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-indigo-100 rounded flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-700">Laravel 13</p>
                    <p class="text-xs text-slate-400">app/Modules/*</p>
                </div>
                <span class="ml-auto text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-medium">v1.0</span>
            </div>
        </div>

    </aside>

    {{-- ── Main content ─────────────────────────────────────────────── --}}
    <main class="lg:ml-64 flex-1 max-w-4xl mx-auto px-6 lg:px-12 py-10">

        {{-- ── Overview ───────────────────────────────────────────── --}}
        <section id="overview" class="mb-16">
            <h1 class="text-3xl font-bold text-slate-900 mb-3">Modular Architecture</h1>
            <p class="text-lg text-slate-600 mb-6">
                Every business domain lives in a self-contained folder under <code class="text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded text-sm">app/Modules/</code>.
                A module owns its models, migrations, services, controllers, requests, resources, routes, views, and provider.
            </p>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
                <strong>Key principle:</strong> Deleting a module = remove its folder + remove one line from <code>bootstrap/providers.php</code>. No other file is touched.
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Quick Start ─────────────────────────────────────────── --}}
        <section id="quick-start" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Quick Start</h2>
            <p class="text-slate-500 mb-6">Set up the architecture in a fresh Laravel 13 project in four steps.</p>

            <div class="space-y-6">
                {{-- Step 1 --}}
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shrink-0 mt-0.5">1</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-slate-900 mb-1">Copy the Base module</h3>
                        <p class="text-sm text-slate-500 mb-3">Copy <code class="bg-slate-100 px-1 rounded">app/Modules/Base/</code> into your project. It brings the repository pattern, traits, helpers, providers, and the <code class="bg-slate-100 px-1 rounded">make:module</code> command.</p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shrink-0 mt-0.5">2</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-slate-900 mb-1">Add helper autoload to composer.json</h3>
                        <div class="relative">
                            <pre><code class="language-json">"autoload": {
    "psr-4": { "App\\": "app/" },
    "files": [
        "app/Modules/Base/Http/Helpers/helpers.php"
    ]
}</code></pre>
                            <button class="copy-btn" onclick="copyCode(this)">Copy</button>
                        </div>
                        <p class="text-sm text-slate-500 mt-2">Then run: <code class="bg-slate-100 px-1 rounded">composer dump-autoload</code></p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shrink-0 mt-0.5">3</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-slate-900 mb-1">Register Base providers</h3>
                        <div class="relative">
                            <pre><code class="language-php">// bootstrap/providers.php
return [
    App\Modules\Base\Providers\BaseServiceProvider::class,
    App\Modules\Base\Providers\RepositoryServiceProvider::class,
    App\Providers\AppServiceProvider::class,
];</code></pre>
                            <button class="copy-btn" onclick="copyCode(this)">Copy</button>
                        </div>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shrink-0 mt-0.5">4</div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-slate-900 mb-1">Enable API routes in bootstrap/app.php</h3>
                        <div class="relative">
                            <pre><code class="language-php">->withRouting(
    web:      __DIR__.'/../routes/web.php',
    api:      __DIR__.'/../routes/api.php',  // add this
    commands: __DIR__.'/../routes/console.php',
    health:   '/up',
)</code></pre>
                            <button class="copy-btn" onclick="copyCode(this)">Copy</button>
                        </div>
                    </div>
                </div>

                {{-- Verify --}}
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-slate-900 mb-1">Verify</h3>
                        <div class="relative">
                            <pre><code class="language-bash">php artisan list make
# You should see: make:module and make:module-model</code></pre>
                            <button class="copy-btn" onclick="copyCode(this)">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Folder Layout ───────────────────────────────────────── --}}
        <section id="folder-layout" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Folder Layout</h2>
            <p class="text-slate-500 mb-6">Every module follows the exact same structure. No exceptions.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Top-level</p>
                    <div class="relative">
                        <pre><code class="language-bash">app/Modules/
├── Base/           ← shared foundation
├── Auth/
├── Category/
│   ├── Models/Category.php
│   └── Models/SubCategory.php
├── Products/
└── Orders/</code></pre>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Inside a module</p>
                    <div class="relative">
                        <pre><code class="language-bash">Products/
├── Http/
│   ├── Controllers/Api/V1/
│   ├── Controllers/Dashboard/
│   ├── Requests/Api/{Model}/
│   ├── Requests/Dashboard/{Model}/
│   ├── Resources/{Model}/
│   └── Services/
│       ├── Api/{Model}/
│       │   ├── {Model}Service.php
│       │   ├── {Model}WebService.php
│       │   └── {Model}MobileService.php
│       └── Dashboard/{Model}/
├── Models/
├── Repositories/Eloquent/
├── Routes/api/v1/
├── Routes/dashboard/
├── Resources/views/dashboard/{seg}/
├── database/migrations/
├── database/seeders/
└── Providers/</code></pre>
                    </div>
                </div>
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── make:module ─────────────────────────────────────────── --}}
        <section id="make-module" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">make:module</h2>
            <p class="text-slate-500 mb-6">Scaffolds a complete new module in one command. Automatically registers its provider in <code class="bg-slate-100 px-1 rounded text-sm">bootstrap/providers.php</code>.</p>

            <div class="relative mb-4">
                <pre><code class="language-bash">php artisan make:module {name}
    [--model=]        # Custom model name (default: singular of module name)
    [--api]           # API surface only
    [--dashboard]     # Dashboard surface only
    [--force]         # Overwrite existing files
    [--with-factory]  # Add Eloquent factory
    [--with-tests]    # Add feature test</code></pre>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>

            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 mt-6">Examples</p>
            <div class="space-y-3">
                @foreach([
                    ['Full module (API + Dashboard)','php artisan make:module Products'],
                    ['API only','php artisan make:module Articles --api'],
                    ['Dashboard only','php artisan make:module Settings --dashboard'],
                    ['Custom model name','php artisan make:module Catalog --model=Product'],
                    ['With factory + tests','php artisan make:module Orders --with-factory --with-tests'],
                    ['Force overwrite','php artisan make:module Products --force'],
                ] as [$label,$cmd])
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-500 w-48 shrink-0">{{ $label }}</span>
                    <div class="relative flex-1">
                        <pre style="margin:0"><code class="language-bash" style="padding: 0.5rem 0.75rem; font-size:0.78rem">{{ $cmd }}</code></pre>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── make:module-model ───────────────────────────────────── --}}
        <section id="make-module-model" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">make:module-model</h2>
            <p class="text-slate-500 mb-6">Adds a new model with all its files into an <strong>existing</strong> module — without touching any other file in that module.</p>

            <div class="relative mb-6">
                <pre><code class="language-bash">php artisan make:module-model {module} {model}
    [--api]           # API surface only
    [--dashboard]     # Dashboard surface only
    [--force]         # Overwrite existing files
    [--with-factory]  # Add Eloquent factory
    [--with-tests]    # Add feature test</code></pre>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>

            <div class="bg-slate-900 rounded-lg p-4 mb-3">
                <p class="text-xs text-slate-400 mb-2 font-mono">Add SubCategory inside the Category module</p>
                <code class="text-emerald-400 font-mono text-sm">php artisan make:module-model Category SubCategory</code>
            </div>
            <p class="text-sm text-slate-500">This also automatically appends <code class="bg-slate-100 px-1 rounded">bindPlatformService(SubCategoryService::class, ...)</code> to <code class="bg-slate-100 px-1 rounded">CategoryServiceProvider</code>.</p>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Request Lifecycle ──────────────────────────────────── --}}
        <section id="request-lifecycle" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Request Lifecycle</h2>
            <p class="text-slate-500 mb-6">Strict layering — nothing skips a layer. Controllers stay under 30 lines.</p>

            <div class="flex flex-col gap-0 mb-8">
                @foreach([
                    ['Route','Matches the URL and picks the controller','slate'],
                    ['FormRequest','Validates input + authorizes the action','slate'],
                    ['Controller','Forwards validated data to the service — nothing else','indigo'],
                    ['Service','Business logic, DB transactions, file uploads','slate'],
                    ['Repository','All Eloquent access — no raw queries elsewhere','slate'],
                    ['Model','Schema, relations, scopes, accessors','slate'],
                ] as [$name,$desc,$color])
                <div class="flex items-stretch">
                    <div class="flex flex-col items-center mr-4">
                        <div class="w-3 h-3 rounded-full {{ $color === 'indigo' ? 'bg-indigo-600' : 'bg-slate-300' }} mt-4"></div>
                        <div class="w-px flex-1 bg-slate-200"></div>
                    </div>
                    <div class="pb-6 flex-1">
                        <p class="font-semibold text-slate-900">{{ $name }}</p>
                        <p class="text-sm text-slate-500">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">A controller must NOT</p>
            <div class="grid grid-cols-2 gap-2">
                @foreach([
                    'Touch the DB directly (no Model::create() in controllers)',
                    'Validate input — use a FormRequest',
                    'Send notifications or emails',
                    'Write files or call external services',
                ] as $rule)
                <div class="flex items-start gap-2 text-sm text-slate-600 bg-red-50 border border-red-100 rounded-lg p-3">
                    <svg class="w-4 h-4 text-red-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ $rule }}
                </div>
                @endforeach
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Platform Services ──────────────────────────────────── --}}
        <section id="platform-services" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Platform Services</h2>
            <p class="text-slate-500 mb-6">Each API entity has three service classes. The container resolves the right one based on the request URL — controllers are unaware of the platform.</p>

            <div class="grid grid-cols-3 gap-3 mb-6">
                @foreach([
                    ['Abstract','ProductService.php','Defines the contract — all abstract methods'],
                    ['Web','ProductWebService.php','Serves api/v1/web/* requests'],
                    ['Mobile','ProductMobileService.php','Serves api/v1/mobile/* requests'],
                ] as [$type,$file,$desc])
                <div class="border border-slate-200 rounded-lg p-4">
                    <span class="badge {{ $type === 'Abstract' ? 'bg-slate-100 text-slate-600' : ($type === 'Web' ? 'bg-blue-100 text-blue-700' : 'bg-violet-100 text-violet-700') }} mb-2">{{ $type }}</span>
                    <p class="text-sm font-mono text-slate-700 mb-1">{{ $file }}</p>
                    <p class="text-xs text-slate-500">{{ $desc }}</p>
                </div>
                @endforeach
            </div>

            <div class="relative mb-4">
                <pre><code class="language-php">// In the module's ServiceProvider::register()
use App\Modules\Base\Http\Traits\ResolvesPlatformService;

class ProductsServiceProvider extends ServiceProvider
{
    use ResolvesPlatformService;

    public function register(): void
    {
        $this->bindPlatformService(
            ProductService::class,       // abstract — what the controller injects
            ProductWebService::class,    // resolved for api/v1/web/*
            ProductMobileService::class, // resolved for api/v1/mobile/*
        );
    }
}</code></pre>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>

            <div class="relative">
                <pre><code class="language-php">// Controller is platform-agnostic
class ProductController extends BaseController
{
    public function __construct(
        protected ProductService $productService // container picks Web or Mobile
    ) {}

    public function index(Request $request): JsonResponse
    {
        return $this->productService->index($request);
    }
}</code></pre>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Repository Pattern ─────────────────────────────────── --}}
        <section id="repository-pattern" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Repository Pattern</h2>
            <p class="text-slate-500 mb-6">All DB access goes through a repository. Services type-hint the interface — never the concrete class. <code class="bg-slate-100 px-1 rounded text-sm">RepositoryServiceProvider</code> auto-binds every pair with zero configuration.</p>

            <div class="relative mb-6">
                <pre><code class="language-php">// 1. Interface — just extend the base composite interface
namespace App\Modules\Products\Repositories;

use App\Modules\Base\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    // add domain-specific methods here
    public function findBySlug(string $slug): ?Product;
}

// 2. Concrete — extend the base Repository and inject the model
namespace App\Modules\Products\Repositories\Eloquent;

use App\Modules\Base\Repositories\Eloquent\Repository;

class ProductRepository extends Repository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model->where('slug', $slug)->first();
    }
}</code></pre>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>

            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Available Methods (from base)</p>
            <div class="overflow-hidden border border-slate-200 rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">Method</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">Returns</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach([
                            ['getAll()','Collection','All rows'],
                            ['getById($id)','?Model','Find by primary key'],
                            ['first()','?Model','First row'],
                            ['query()','Builder','Raw query builder for complex filters'],
                            ['create($data)','Model','Insert one row'],
                            ['update($id, $data)','bool','Update by id'],
                            ['delete($id)','?bool','Soft-delete or delete'],
                            ['forceDelete($id)','?bool','Permanent delete (bypasses soft deletes)'],
                            ['paginate($perPage)','LengthAwarePaginator','Paginated results'],
                            ['paginateWithQuery($query)','LengthAwarePaginator','Paginate a scoped query'],
                            ['getTrashed()','Collection','Only soft-deleted rows'],
                            ['restoreById($id)','bool','Restore a soft-deleted row'],
                        ] as [$method,$return,$desc])
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2.5 font-mono text-indigo-600 text-xs">{{ $method }}</td>
                            <td class="px-4 py-2.5 text-slate-500 text-xs">{{ $return }}</td>
                            <td class="px-4 py-2.5 text-slate-600">{{ $desc }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Routes ─────────────────────────────────────────────── --}}
        <section id="routes" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Routes</h2>
            <p class="text-slate-500 mb-6">Three surfaces per module. Each is a separate file loaded by the module's ServiceProvider.</p>

            <div class="overflow-hidden border border-slate-200 rounded-lg mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">Surface</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">URL Prefix</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">Route Names</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">File</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3"><span class="badge bg-blue-100 text-blue-700">Web API</span></td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-700">api/v1/web/{resource}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-500">api.v1.web.*</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-500">Routes/api/v1/web.php</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3"><span class="badge bg-violet-100 text-violet-700">Mobile API</span></td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-700">api/v1/mobile/{resource}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-500">api.v1.mobile.*</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-500">Routes/api/v1/mobile.php</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3"><span class="badge bg-emerald-100 text-emerald-700">Dashboard</span></td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-700">dashboard/{resource}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-500">dashboard.*</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-500">Routes/dashboard/dashboard.php</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="relative">
                <pre><code class="language-php">// Routes/api/v1/web.php
Route::middleware(['api'])
    ->prefix('api/v1/web')
    ->name('api.v1.web.')
    ->group(function (): void {
        Route::apiResource('products', ProductController::class);
    });</code></pre>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Generated Files ────────────────────────────────────── --}}
        <section id="generated-files" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Generated Files</h2>
            <p class="text-slate-500 mb-6">Every file below is created automatically. You only fill in column rules, fillable fields, and business logic.</p>

            <div class="overflow-hidden border border-slate-200 rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">File</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">Purpose</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600 text-center">API</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600 text-center">Dash</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                        $files = [
                            ['Models/{Model}.php','Eloquent model',true,true],
                            ['database/migrations/*_create_{table}.php','Schema migration',true,true],
                            ['database/seeders/{Model}Seeder.php','Model seeder',true,true],
                            ['Repositories/{Model}RepositoryInterface.php','Repository contract',true,true],
                            ['Repositories/Eloquent/{Model}Repository.php','Concrete repository',true,true],
                            ['Providers/{Module}ServiceProvider.php','Module boot/register',true,true],
                            ['Http/Services/Api/{Model}/{Model}Service.php','Abstract API service',true,false],
                            ['Http/Services/Api/{Model}/{Model}WebService.php','Web platform logic',true,false],
                            ['Http/Services/Api/{Model}/{Model}MobileService.php','Mobile platform logic',true,false],
                            ['Http/Services/Dashboard/{Model}/{Model}Service.php','Dashboard service (views/redirects)',false,true],
                            ['Http/Controllers/Api/V1/{Model}Controller.php','Thin API controller',true,false],
                            ['Http/Controllers/Dashboard/{Model}Controller.php','Thin dashboard controller',false,true],
                            ['Http/Requests/Api/{Model}/Store{Model}Request.php','API create validation',true,false],
                            ['Http/Requests/Api/{Model}/Update{Model}Request.php','API update validation',true,false],
                            ['Http/Requests/Dashboard/{Model}/Store{Model}Request.php','Dashboard create validation',false,true],
                            ['Http/Requests/Dashboard/{Model}/Update{Model}Request.php','Dashboard update validation',false,true],
                            ['Http/Resources/{Model}/{Model}Resource.php','JSON transformer',true,false],
                            ['Routes/api/v1/web.php','Web API routes',true,false],
                            ['Routes/api/v1/mobile.php','Mobile API routes',true,false],
                            ['Routes/dashboard/dashboard.php','Dashboard routes',false,true],
                            ['Resources/views/dashboard/{seg}/layout.blade.php','Blade layout',false,true],
                            ['Resources/views/dashboard/{seg}/index.blade.php','Index view',false,true],
                            ['Resources/views/dashboard/{seg}/create.blade.php','Create view',false,true],
                            ['Resources/views/dashboard/{seg}/edit.blade.php','Edit view',false,true],
                        ];
                        @endphp
                        @foreach($files as [$file,$purpose,$api,$dash])
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2.5 font-mono text-xs text-slate-700">{{ $file }}</td>
                            <td class="px-4 py-2.5 text-slate-600 text-xs">{{ $purpose }}</td>
                            <td class="px-4 py-2.5 text-center">
                                @if($api)<svg class="w-4 h-4 text-emerald-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>@else<span class="text-slate-300 text-xs mx-auto block text-center">—</span>@endif
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                @if($dash)<svg class="w-4 h-4 text-emerald-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>@else<span class="text-slate-300 text-xs mx-auto block text-center">—</span>@endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Seeding ─────────────────────────────────────────────── --}}
        <section id="seeding" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Seeding</h2>
            <p class="text-slate-500 mb-6"><code class="bg-slate-100 px-1 rounded text-sm">DatabaseSeeder</code> auto-discovers every module seeder. No manual registration ever needed.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Seed all modules</p>
                    <div class="relative">
                        <pre><code class="language-bash">php artisan migrate --seed
# or
php artisan db:seed</code></pre>
                        <button class="copy-btn" onclick="copyCode(this)">Copy</button>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Seed one module</p>
                    <div class="relative">
                        <pre><code class="language-bash">php artisan db:seed \
  --class="App\Modules\Products\Database\Seeders\ProductSeeder"</code></pre>
                        <button class="copy-btn" onclick="copyCode(this)">Copy</button>
                    </div>
                </div>
            </div>

            <div class="relative">
                <pre><code class="language-php">// database/seeders/ProductSeeder.php
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()->count(20)->create();
        // or manual inserts:
        Product::create(['name' => 'Sample', 'price' => 99]);
    }
}</code></pre>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Base Module ─────────────────────────────────────────── --}}
        <section id="base-module" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Base Module</h2>
            <p class="text-slate-500 mb-6"><code class="bg-slate-100 px-1 rounded text-sm">app/Modules/Base/</code> is the only module every other module depends on. Copy it once — never modify it per project.</p>

            <div class="overflow-hidden border border-slate-200 rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">File</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">Purpose</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach([
                            ['Repositories/RepositoryInterface.php','Composite interface (read + write + paginate + soft-delete)'],
                            ['Repositories/Eloquent/Repository.php','Abstract base — all CRUD implemented in one class'],
                            ['Http/Helpers/Http.php','HTTP status code constants (Http::OK, Http::NOT_FOUND, …)'],
                            ['Http/Helpers/helpers.php','responseSuccess(), responseFail(), catchError(), paginatedJsonResponse()'],
                            ['Http/Traits/Responser.php','Trait — clean JSON response helpers for services'],
                            ['Http/Traits/ResolvesPlatformService.php','Trait — bindPlatformService() for module providers'],
                            ['Http/Controllers/BaseController.php','Abstract controller — all API controllers extend this'],
                            ['Providers/BaseServiceProvider.php','Registers make:module and make:module-model commands'],
                            ['Providers/RepositoryServiceProvider.php','Auto-discovers + binds all XRepositoryInterface → XRepository'],
                            ['Console/Commands/MakeModuleCommand.php','php artisan make:module'],
                            ['Console/Commands/MakeModuleModelCommand.php','php artisan make:module-model'],
                            ['Console/Stubs/','23 stub templates used during code generation'],
                        ] as [$file,$purpose])
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2.5 font-mono text-xs text-indigo-600">{{ $file }}</td>
                            <td class="px-4 py-2.5 text-slate-600 text-xs">{{ $purpose }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Helpers ─────────────────────────────────────────────── --}}
        <section id="helpers" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Helpers & Traits</h2>
            <p class="text-slate-500 mb-6">Global functions auto-loaded via Composer. Available everywhere without any <code class="bg-slate-100 px-1 rounded text-sm">use</code> statement.</p>

            <div class="relative mb-4">
                <pre><code class="language-php">// Standard success response
return responseSuccess(Http::OK, 'Fetched.', $data);
// { "status": 200, "message": "Fetched.", "data": {...} }

// Standard failure response
return responseFail(Http::NOT_FOUND, 'Not found.');
// { "status": 404, "message": "Not found.", "errors": null }

// Paginated response with resource
return paginatedJsonResponse($paginator, ProductResource::class);
// { "status": 200, "message": "OK", "data": { "items": [...], "meta": {...} } }

// In a catch block — rolls back DB, logs, returns 500
return catchError($e);

// Full path to a storage file
fileFullPath('products/image.jpg'); // → https://example.com/storage/products/image.jpg</code></pre>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Naming ──────────────────────────────────────────────── --}}
        <section id="naming" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">Naming Conventions</h2>
            <div class="overflow-hidden border border-slate-200 rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">Item</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">Convention</th>
                            <th class="text-left px-4 py-2.5 font-medium text-slate-600">Example</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach([
                            ['Module folder','StudlyCase plural (or singular when conceptual)','Products, Quizzes, Auth'],
                            ['Model','Singular StudlyCase','Product, Quiz, SubCategory'],
                            ['Repository interface','{Model}RepositoryInterface','ProductRepositoryInterface'],
                            ['Repository concrete','{Model}Repository','ProductRepository'],
                            ['Abstract API service','{Model}Service','ProductService'],
                            ['Web service','{Model}WebService','ProductWebService'],
                            ['Mobile service','{Model}MobileService','ProductMobileService'],
                            ['Dashboard service','{Model}Service (inside Dashboard/{Model}/)','Dashboard\\Product\\ProductService'],
                            ['Form requests','Store{Model}Request, Update{Model}Request','StoreProductRequest'],
                            ['JSON resource','{Model}Resource (inside Resources/{Model}/)','Product/ProductResource'],
                            ['Migration table','snake_case plural','products, sub_categories'],
                            ['View namespace','lowercase module name','products, category'],
                            ['Route name','{surface}.{resource}.{action}','api.v1.web.products.index'],
                            ['Service provider','{Module}ServiceProvider','ProductsServiceProvider'],
                        ] as [$item,$conv,$ex])
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2.5 font-medium text-slate-700 text-xs">{{ $item }}</td>
                            <td class="px-4 py-2.5 text-slate-500 text-xs">{{ $conv }}</td>
                            <td class="px-4 py-2.5 font-mono text-indigo-600 text-xs">{{ $ex }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <hr class="border-slate-200 mb-16">

        {{-- ── Bootstrap providers ─────────────────────────────────── --}}
        <section id="bootstrap" class="mb-16">
            <h2 class="text-2xl font-bold mb-1">bootstrap/providers.php</h2>
            <p class="text-slate-500 mb-4">Order matters. Base providers must come first.</p>
            <div class="relative">
                <pre><code class="language-php">return [
    // 1. Base foundation — always first
    App\Modules\Base\Providers\BaseServiceProvider::class,
    App\Modules\Base\Providers\RepositoryServiceProvider::class,

    // 2. Laravel app provider
    App\Providers\AppServiceProvider::class,

    // 3. Your modules — added automatically by make:module
    App\Modules\Products\Providers\ProductsServiceProvider::class,
    App\Modules\Category\Providers\CategoryServiceProvider::class,
    App\Modules\Orders\Providers\OrdersServiceProvider::class,
];</code></pre>
                <button class="copy-btn" onclick="copyCode(this)">Copy</button>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4 text-sm text-blue-800">
                <strong>Auto-registered:</strong> Running <code>php artisan make:module</code> or <code>php artisan make:module-model</code> automatically appends the provider line. You don't have to edit this file manually.
            </div>
        </section>

        <div class="border-t border-slate-200 pt-8 pb-16 text-sm text-slate-400 flex items-center justify-between">
            <span>Laravel 13 · app/Modules/* architecture</span>
            <span>Generated by make:module</span>
        </div>

    </main>
</div>

<script>
hljs.highlightAll();

function copyCode(btn) {
    const code = btn.parentElement.querySelector('code').innerText;
    navigator.clipboard.writeText(code).then(() => {
        const original = btn.innerText;
        btn.innerText = 'Copied!';
        btn.classList.add('bg-emerald-700');
        setTimeout(() => { btn.innerText = original; btn.classList.remove('bg-emerald-700'); }, 1500);
    });
}

// Sidebar active link on scroll
const sections = document.querySelectorAll('section[id]');
const links    = document.querySelectorAll('a.sidebar-link');

const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            links.forEach(l => l.classList.remove('active'));
            const active = document.querySelector(`a[href="#${entry.target.id}"]`);
            if (active) active.classList.add('active');
        }
    });
}, { rootMargin: '-20% 0px -70% 0px' });

sections.forEach(s => observer.observe(s));
</script>
</body>
</html>
