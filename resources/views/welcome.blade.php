<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mohsin Medical Complex | HR Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <nav class="fixed w-full z-50 glass border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div
                    class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-800">Mohsin <span
                        class="text-blue-600">Medical</span></span>
            </div>
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="#features" class="hover:text-blue-600 transition">Features</a>
                <a href="#vault" class="hover:text-blue-600 transition">Document Vault</a>
                <a href="#leaves" class="hover:text-blue-600 transition">Leave System</a>
                @if (Route::has('login'))
                    <nav class="flex items-center justify-end gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Dashboard
                            </a>
                        @else
                           
                    <a href="{{ route('login') }}"
                    class="bg-slate-900 text-white px-6 py-2.5 rounded-full hover:bg-blue-600 transition shadow-lg shadow-slate-200">Staff
                    Login</a>

                            {{-- @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                    Register
                                </a>
                            @endif --}}
                        @endauth
                    </nav>
                @endif
               
            </div>
        </div>
    </nav>

    <header class="pt-40 pb-20 px-6">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div
                    class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider mb-6">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                    </span>
                    Next-Gen Hospital HR
                </div>
                <h1 class="text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                    Manage your medical heroes, <span class="text-blue-600">digitally.</span>
                </h1>
                <p class="text-lg text-slate-600 mb-10 leading-relaxed max-w-lg">
                    Stop losing paperwork and verbal requests. A unified HR system designed specifically for the
                    fast-paced environment of Mohsin Medical Complex.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button
                        class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-blue-700 transition shadow-xl shadow-blue-200 flex items-center justify-center gap-2">
                        Explore Dashboard
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </button>
                    <button
                        class="bg-white border-2 border-slate-200 text-slate-700 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-slate-50 transition">
                        View Schedules
                    </button>
                </div>
            </div>

            <div class="relative">
                <div
                    class="absolute -inset-4 bg-gradient-to-tr from-blue-100 to-emerald-100 rounded-3xl blur-2xl opacity-50">
                </div>
                <div class="relative bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <div class="text-xs font-bold text-slate-400">STAFF OVERVIEW</div>
                    </div>
                    <div class="p-8">
                        <div class="space-y-6">
                            <div
                                class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl border border-blue-100">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                        DR</div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">Dr. Sarah Ahmed</div>
                                        <div class="text-xs text-blue-600 font-semibold">Senior Cardiologist</div>
                                    </div>
                                </div>
                                <span
                                    class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-lg uppercase">On
                                    Duty</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                                        NS</div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">Nurse James Wilson</div>
                                        <div class="text-xs text-slate-500 font-semibold">Emergency Ward</div>
                                    </div>
                                </div>
                                <span
                                    class="px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-lg uppercase">Leave
                                    Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">Goodbye paperwork. Hello clarity.
                </h2>
                <p class="text-slate-600 text-lg">We've identified the biggest pain points in the MMC workflow and built
                    direct digital solutions.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    class="p-10 rounded-3xl bg-slate-50 border border-slate-100 hover:border-blue-300 transition-all hover:shadow-2xl hover:shadow-blue-100 group">
                    <div
                        class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-blue-200 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Digital Document Vault</h3>
                    <p class="text-slate-600 leading-relaxed mb-6">No more lost files. Store medical licenses, IDs, and
                        contracts in a secure, encrypted digital vault attached to staff profiles.</p>
                    <ul class="text-sm text-slate-500 space-y-2">
                        <li class="flex items-center gap-2"><span class="text-blue-600 font-bold">✓</span> Expiry Alerts
                        </li>
                        <li class="flex items-center gap-2"><span class="text-blue-600 font-bold">✓</span> Instant
                            Retrieval</li>
                    </ul>
                </div>

                <div
                    class="p-10 rounded-3xl bg-slate-50 border border-slate-100 hover:border-emerald-300 transition-all hover:shadow-2xl hover:shadow-emerald-100 group">
                    <div
                        class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-emerald-200 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Leave Engine</h3>
                    <p class="text-slate-600 leading-relaxed mb-6">Replace verbal "I'm not coming" with a formal request
                        system. Real-time availability calendars ensure wards are never understaffed.</p>
                    <ul class="text-sm text-slate-500 space-y-2">
                        <li class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> Approval
                            Workflow</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500 font-bold">✓</span> Staff
                            Shortage Alerts</li>
                    </ul>
                </div>

                <div
                    class="p-10 rounded-3xl bg-slate-50 border border-slate-100 hover:border-indigo-300 transition-all hover:shadow-2xl hover:shadow-indigo-100 group">
                    <div
                        class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-indigo-200 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Grievance & Feedback</h3>
                    <p class="text-slate-600 leading-relaxed mb-6">A private space for staff to log complaints or
                        suggestions. Management can track issues from "Logged" to "Resolved."</p>
                    <ul class="text-sm text-slate-500 space-y-2">
                        <li class="flex items-center gap-2"><span class="text-indigo-600 font-bold">✓</span> Anonymous
                            Option</li>
                        <li class="flex items-center gap-2"><span class="text-indigo-600 font-bold">✓</span> Action
                            Tracking</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6">
        <div
            class="max-w-5xl mx-auto bg-slate-900 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/20 blur-3xl rounded-full -mr-20 -mt-20"></div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-8 leading-tight">Ready to modernize Mohsin
                    Medical Complex?</h2>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button
                        class="bg-blue-600 text-white px-10 py-5 rounded-2xl font-bold text-lg hover:bg-blue-500 transition shadow-xl shadow-blue-500/20">
                        Launch System Portal
                    </button>
                    <button
                        class="bg-transparent border-2 border-slate-700 text-white px-10 py-5 rounded-2xl font-bold text-lg hover:bg-slate-800 transition">
                        Contact IT Support
                    </button>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-12 text-center text-slate-400 text-sm border-t border-slate-200">
        <p>© 2026 Mohsin Medical Complex. All Rights Reserved. Built with TALL Stack.</p>
    </footer>

</body>

</html>