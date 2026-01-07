<x-layouts.app :title="__('Dashboard')">
   

    {{-- <aside class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 z-50 hidden lg:block">
        <div class="flex flex-col h-full">
            <div class="h-20 flex items-center px-6 border-b border-slate-100 dark:border-slate-800">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3 shadow-lg shadow-blue-200 dark:shadow-none">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <span class="text-lg font-bold tracking-tight">MMC <span class="text-blue-600">Admin</span></span>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="#" class="flex items-center px-4 py-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl font-semibold">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-12 0v1zm0-11a3 3 0 110-6 3 3 0 010 6z"></path></svg>
                    Staff Directory
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Leave Requests
                    <span class="ml-auto bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 text-xs font-bold px-2 py-0.5 rounded-full">4</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Document Vault
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    Feedback Logs
                </a>
            </nav>

            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                <button class="flex items-center w-full px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 rounded-xl transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4-4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </div>
        </div>
    </aside> --}}

    <main class=" min-h-screen">
        <header class="h-20 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-8 sticky top-0 z-40">
            <h1 class="text-xl font-bold dark:text-white">Admin Overview</h1>
            <div class="flex items-center gap-4">
                <button class="p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
                <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900/40 rounded-full border-2 border-white dark:border-slate-800 flex items-center justify-center font-bold text-blue-600 dark:text-blue-400">
                    AD
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="text-slate-500 dark:text-slate-400 text-sm font-semibold mb-2 uppercase">Total Staff</div>
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-bold dark:text-white">124</span>
                        <span class="text-green-500 text-sm font-bold flex items-center">↑ 2%</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="text-slate-500 dark:text-slate-400 text-sm font-semibold mb-2 uppercase">On Leave Today</div>
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-bold dark:text-white">12</span>
                        <span class="text-amber-500 text-xs bg-amber-50 dark:bg-amber-900/20 px-2 py-1 rounded">Action Needed</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="text-slate-500 dark:text-slate-400 text-sm font-semibold mb-2 uppercase">Expired Docs</div>
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-bold dark:text-white text-red-600 dark:text-red-400">8</span>
                        <span class="text-slate-400 text-xs">Alerts Sent</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="text-slate-500 dark:text-slate-400 text-sm font-semibold mb-2 uppercase">New Feedback</div>
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-bold dark:text-white">5</span>
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <h2 class="font-bold dark:text-white">Pending Leave Approvals</h2>
                            <button class="text-blue-600 dark:text-blue-400 text-sm font-semibold hover:underline">View All</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                                        <th class="p-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Staff Member</th>
                                        <th class="p-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Type</th>
                                        <th class="p-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Duration</th>
                                        <th class="p-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold">NA</div>
                                                <div>
                                                    <div class="text-sm font-bold dark:text-white">Nurse Amina</div>
                                                    <div class="text-[10px] text-slate-400 uppercase">Emergency Ward</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4"><span class="text-sm text-slate-600 dark:text-slate-400">Sick Leave</span></td>
                                        <td class="p-4 text-sm dark:text-slate-300">Jan 12 - Jan 14</td>
                                        <td class="p-4 text-right">
                                            <button class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700">Review</button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold">DZ</div>
                                                <div>
                                                    <div class="text-sm font-bold dark:text-white">Dr. Zubair</div>
                                                    <div class="text-[10px] text-slate-400 uppercase">Surgery</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4"><span class="text-sm text-slate-600 dark:text-slate-400">Personal</span></td>
                                        <td class="p-4 text-sm dark:text-slate-300">Jan 20</td>
                                        <td class="p-4 text-right">
                                            <button class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700">Review</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <h3 class="font-bold mb-4 flex items-center gap-2 dark:text-white">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Document Expiration
                        </h3>
                        <div class="space-y-4">
                            <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-900/40">
                                <div class="text-xs font-bold text-red-700 dark:text-red-400 uppercase mb-1">Medical License Expired</div>
                                <div class="text-sm font-semibold dark:text-white">Dr. Sarah Ahmed</div>
                                <div class="text-[10px] text-red-600/70 dark:text-red-400/70 mt-1">Expired 2 days ago</div>
                            </div>
                            <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-100 dark:border-amber-900/40">
                                <div class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase mb-1">ID Card Expiring</div>
                                <div class="text-sm font-semibold dark:text-white">Nurse Sarah Wilson</div>
                                <div class="text-[10px] text-amber-600/70 dark:text-amber-400/70 mt-1">Expires in 5 days</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-600 p-6 rounded-2xl shadow-xl shadow-blue-200 dark:shadow-none text-white">
                        <h3 class="font-bold mb-2">New Staff Suggestion</h3>
                        <p class="text-blue-100 text-sm mb-4">"We need more ergonomic chairs in the break room to reduce fatigue during night shifts."</p>
                        <button class="w-full bg-white/10 hover:bg-white/20 transition py-2 rounded-xl text-xs font-bold uppercase tracking-widest">Mark as Read</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
