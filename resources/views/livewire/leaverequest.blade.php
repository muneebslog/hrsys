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
                   
                    <div>
                        <span class="text-xl font-bold tracking-tight block">Leave Management</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Submit Request
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 shadow-sm">
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pending Review</p>
                    <p class="text-2xl font-black dark:text-white">08 Requests</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 shadow-sm border-l-4 border-l-blue-600">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">On Leave Today</p>
                    <p class="text-2xl font-black dark:text-white">14 Staff</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 shadow-sm">
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Available Staff</p>
                    <p class="text-2xl font-black dark:text-white">110 Present</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1 flex gap-2 overflow-x-auto pb-2 md:pb-0">
                <button class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold whitespace-nowrap">All Requests</button>
                <button class="px-5 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 rounded-lg text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition whitespace-nowrap">Pending</button>
                <button class="px-5 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 rounded-lg text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition whitespace-nowrap">Approved</button>
            </div>
            <div class="flex gap-2">
                <input type="date" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50">
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Staff Member</th>
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Leave Type</th>
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Duration</th>
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Reason/Note</th>
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Status</th>
                            <th class="p-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition group">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-xs font-bold text-blue-600">NA</div>
                                    <div>
                                        <div class="text-sm font-bold dark:text-white">Nurse Amina</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase">ER Ward</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5 text-sm font-medium dark:text-slate-300">Sick Leave</td>
                            <td class="p-5">
                                <div class="text-sm font-bold dark:text-white">3 Days</div>
                                <div class="text-[10px] text-slate-400">Jan 12 - Jan 14</div>
                            </td>
                            <td class="p-5">
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs truncate">Recovering from seasonal flu. Medical certificate uploaded in vault.</p>
                            </td>
                            <td class="p-5 text-center">
                                <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 text-[10px] font-bold rounded-full uppercase">Pending</span>
                            </td>
                            <td class="p-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="p-2 bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 rounded-lg hover:bg-emerald-600 hover:text-white transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                    <button class="p-2 bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 rounded-lg hover:bg-red-600 hover:text-white transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition group">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold">DZ</div>
                                    <div>
                                        <div class="text-sm font-bold dark:text-white">Dr. Zubair</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase">OPD</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5 text-sm font-medium dark:text-slate-300">Casual Leave</td>
                            <td class="p-5">
                                <div class="text-sm font-bold dark:text-white">1 Day</div>
                                <div class="text-[10px] text-slate-400">Jan 10</div>
                            </td>
                            <td class="p-5">
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs truncate">Family emergency. Will be available on call if needed.</p>
                            </td>
                            <td class="p-5 text-center">
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 text-[10px] font-bold rounded-full uppercase">Approved</span>
                            </td>
                            <td class="p-5 text-right">
                                <button class="text-slate-400 hover:text-blue-600 font-bold text-xs px-3 py-1">Details</button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-2xl p-6 flex items-start gap-4">
            <div class="text-blue-600 mt-1">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-blue-900 dark:text-blue-400">Hospital Staffing Rule</h4>
                <p class="text-sm text-blue-700 dark:text-blue-500/80 leading-relaxed">No more than 20% of staff per department can be on leave simultaneously. Approving Dr. Zubair's request will bring OPD to 15% leave status.</p>
            </div>
        </div>

    </main>

</div>
