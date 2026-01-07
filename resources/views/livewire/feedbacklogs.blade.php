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
                    <div class="w-10 h-10 bg-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-rose-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight block">Mohsin Medical</span>
                        <span class="text-[10px] uppercase font-bold text-rose-600 dark:text-rose-400 tracking-widest">Feedback & Grievance</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="bg-slate-900 dark:bg-white dark:text-slate-900 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition">
                        Export Report
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Total Tickets</p>
                <span class="text-3xl font-black dark:text-white">42</span>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm border-l-4 border-l-rose-500">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Open Complaints</p>
                <span class="text-3xl font-black text-rose-500">05</span>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm border-l-4 border-l-emerald-500">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Resolved</p>
                <span class="text-3xl font-black text-emerald-500">31</span>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm border-l-4 border-l-blue-500">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Suggestions</p>
                <span class="text-3xl font-black text-blue-500">06</span>
            </div>
        </div>

        <div class="space-y-4">
            
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-rose-50 dark:bg-rose-900/20 rounded-xl flex-shrink-0 flex items-center justify-center text-rose-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="font-bold text-lg dark:text-white">AC Repair in Nurse Station</h4>
                                <span class="px-2 py-0.5 bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 text-[10px] font-bold rounded uppercase">Complaint</span>
                                <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 text-[10px] font-bold rounded uppercase">Ticket #882</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 leading-relaxed">
                                The AC in the 2nd-floor nurse station has been leaking for 3 days. It's causing a slip hazard and making it difficult to focus during night shifts.
                            </p>
                            <div class="flex items-center gap-6 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Nurse Maryam Ali</span>
                                <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Jan 05, 2026</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex md:flex-col justify-end gap-2 shrink-0">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition">Update Status</button>
                        <button class="bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 px-4 py-2 rounded-lg text-xs font-bold transition">View History</button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm opacity-75">
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl flex-shrink-0 flex items-center justify-center text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="font-bold text-lg dark:text-white line-through">Water Dispenser Filter</h4>
                                <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 text-[10px] font-bold rounded uppercase">Suggestion</span>
                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold rounded uppercase">Resolved</span>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4 leading-relaxed">
                                Suggestion to replace the filters in the main lobby dispenser. Water has a metallic taste.
                            </p>
                            <div class="flex items-center gap-6 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Anonymous</span>
                                <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Dec 28, 2025</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center text-emerald-500 font-bold text-xs italic">
                        Resolved by Admin on Jan 02
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition border-l-4 border-l-blue-500">
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex-shrink-0 flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="font-bold text-lg dark:text-white">Deduction in December Salary</h4>
                                <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 text-[10px] font-bold rounded uppercase">Inquiry</span>
                                <span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 text-[10px] font-bold rounded uppercase">In Progress</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 leading-relaxed">
                                I noticed a deduction of 2,000/- for "Unpaid Leave" but I was present on all shifts. Please clarify.
                            </p>
                            <div class="flex items-center gap-6 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Dr. Rahul Khan</span>
                                <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Jan 04, 2026</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex md:flex-col justify-end gap-2 shrink-0">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition">Reply</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-12 p-8 bg-slate-900 rounded-[2rem] text-white flex flex-col md:flex-row items-center justify-between gap-8 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-rose-500/20 blur-3xl rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <h3 class="text-xl font-bold mb-2">Staff Satisfaction Insight</h3>
                <p class="text-slate-400 text-sm max-w-md">80% of recent complaints are related to "Maintenance." Addressing the AC and lighting issues could improve staff morale by 15% this month.</p>
            </div>
            <div class="flex gap-4 relative z-10">
                <div class="text-center">
                    <div class="text-3xl font-black text-rose-500">80%</div>
                    <div class="text-[10px] uppercase font-bold text-slate-500">Facility Issues</div>
                </div>
                <div class="w-px h-12 bg-slate-800"></div>
                <div class="text-center">
                    <div class="text-3xl font-black text-emerald-500">20%</div>
                    <div class="text-[10px] uppercase font-bold text-slate-500">Policy Issues</div>
                </div>
            </div>
        </div>

    </main>

</div>
