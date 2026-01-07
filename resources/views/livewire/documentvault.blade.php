<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>


    <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight block">Mohsin Medical</span>
                        <span
                            class="text-[10px] uppercase font-bold text-indigo-600 dark:text-indigo-400 tracking-widest">Document
                            Vault</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Upload New File
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Compliance Score</p>
                <div class="flex items-center gap-3">
                    <span class="text-3xl font-black text-emerald-500">92%</span>
                    <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500" style="width: 92%"></div>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Total Files</p>
                <span class="text-3xl font-black dark:text-white">1,402</span>
            </div>
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm border-b-4 border-b-red-500">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Critical Alerts</p>
                <span class="text-3xl font-black text-red-500">08</span>
            </div>
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Storage Used</p>
                <span class="text-3xl font-black dark:text-white text-slate-400">4.2 <span
                        class="text-sm">GB</span></span>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mb-8">
            <div class="relative flex-1">
                <svg class="w-5 h-5 absolute left-4 top-3.5 text-slate-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Search files by staff name or document type..."
                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm dark:text-white shadow-sm">
            </div>
            <select
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-semibold outline-none shadow-sm dark:text-slate-300">
                <option>All Document Types</option>
                <option>Medical Licenses</option>
                <option>Contracts</option>
                <option>ID Proofs</option>
            </select>
        </div>

        <h3 class="text-lg font-bold mb-6 flex items-center gap-2 dark:text-white">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Recent Uploads & Renewals
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div
                class="bg-white dark:bg-slate-900 border-2 border-red-100 dark:border-red-900/30 rounded-2xl p-5 relative overflow-hidden group shadow-sm">
                <div class="absolute top-0 right-0 p-2">
                    <span class="flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                </div>
                <div class="flex items-start gap-4 mb-6">
                    <div
                        class="w-12 h-12 bg-red-50 dark:bg-red-900/20 rounded-xl flex items-center justify-center text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 transition">
                            Medical License (MBBS)</h4>
                        <p class="text-xs text-slate-500 font-medium">Staff: Dr. Sarah Ahmed</p>
                    </div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-3 mb-4">
                    <div
                        class="flex justify-between text-[10px] font-bold uppercase tracking-widest text-red-700 dark:text-red-400">
                        <span>Status</span>
                        <span>Expired</span>
                    </div>
                    <div class="text-sm font-bold text-red-600 mt-1">Jan 04, 2026</div>
                </div>
                <div class="flex gap-2">
                    <button
                        class="flex-1 bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 py-2.5 rounded-lg text-xs font-bold hover:opacity-90 transition">View
                        File</button>
                    <button
                        class="px-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-400 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 group shadow-sm hover:border-indigo-300 transition-all">
                <div class="flex items-start gap-4 mb-6">
                    <div
                        class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 transition">
                            Employment Contract</h4>
                        <p class="text-xs text-slate-500 font-medium">Staff: Nurse James Wilson</p>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3 mb-4">
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        <span>Verified On</span>
                        <span>Safe</span>
                    </div>
                    <div class="text-sm font-bold dark:text-slate-300 mt-1">Dec 15, 2025</div>
                </div>
                <div class="flex gap-2">
                    <button
                        class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 py-2.5 rounded-lg text-xs font-bold hover:bg-indigo-600 hover:text-white transition">Download
                        PDF</button>
                    <button
                        class="px-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-400 hover:text-indigo-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 group shadow-sm hover:border-amber-300 transition-all">
                <div class="flex items-start gap-4 mb-6">
                    <div
                        class="w-12 h-12 bg-amber-50 dark:bg-amber-900/20 rounded-xl flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <svg class="w-6 h-6 absolute animate-pulse opacity-50" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 transition">
                            National ID Card</h4>
                        <p class="text-xs text-slate-500 font-medium">Staff: Dr. Rahul Khan</p>
                    </div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-3 mb-4">
                    <div
                        class="flex justify-between text-[10px] font-bold uppercase tracking-widest text-amber-700 dark:text-amber-400">
                        <span>Expiry Soon</span>
                        <span>12 Days Left</span>
                    </div>
                    <div class="text-sm font-bold text-amber-600 mt-1">Jan 18, 2026</div>
                </div>
                <div class="flex gap-2">
                    <button
                        class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 py-2.5 rounded-lg text-xs font-bold hover:bg-indigo-600 hover:text-white transition">Request
                        Renewal</button>
                    <button
                        class="px-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-400 hover:text-indigo-600 transition text-xs font-bold">Log</button>
                </div>
            </div>

        </div>

        <div
            class="mt-12 p-6 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border border-indigo-100 dark:border-indigo-900/40 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-indigo-900 dark:text-indigo-300">Automated Backup Enabled</h5>
                    <p class="text-xs text-indigo-700 dark:text-indigo-400">All vault documents are mirrored to
                        encrypted cloud storage every 6 hours.</p>
                </div>
            </div>
            <button
                class="text-xs font-bold text-indigo-600 dark:text-indigo-400 underline uppercase tracking-widest">Audit
                Logs</button>
        </div>

    </main>

</div>