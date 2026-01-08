<?php

use Livewire\Volt\Component;

new class extends Component {
    public $activeSection = 'dashboard';

    public function setSection($section)
    {
        $this->activeSection = $section;
    }
}; ?>

<div class="min-h-screen bg-slate-50 dark:bg-slate-950">
    
    <!-- Header -->
    <div class="bg-gradient-to-br from-blue-500 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black">Admin Guide</h1>
                    <p class="text-blue-100 mt-1">Complete guide to managing your HR system</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 sticky top-4">
                    <h3 class="text-sm font-bold text-slate-400 uppercase mb-4">Quick Navigation</h3>
                    <nav class="space-y-1">
                        <button wire:click="setSection('dashboard')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'dashboard' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Dashboard Overview
                        </button>
                        <button wire:click="setSection('staff')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'staff' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Staff Management
                        </button>
                        <button wire:click="setSection('leaves')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'leaves' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Leave Management
                        </button>
                        <button wire:click="setSection('feedback')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'feedback' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Feedback & Complaints
                        </button>
                        <button wire:click="setSection('departments')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'departments' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Departments
                        </button>
                        <button wire:click="setSection('leavetypes')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'leavetypes' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Leave Types
                        </button>
                    </nav>
                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800">
                        <a href="/admindashboard" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-lg text-sm font-bold transition">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-3 space-y-6">
                
                <!-- Dashboard Section -->
                @if($activeSection === 'dashboard')
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
                        <h2 class="text-2xl font-black dark:text-white mb-4">Dashboard Overview</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Your admin dashboard provides a comprehensive overview of your HR system.</p>
                        
                        <div class="space-y-4">
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                                <h3 class="font-bold text-blue-900 dark:text-blue-400 mb-2">📊 Statistics Cards</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">View key metrics including total employees, departments, pending leaves, new complaints, and staff on leave today.</p>
                            </div>
                            
                            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl">
                                <h3 class="font-bold text-green-900 dark:text-green-400 mb-2">⚡ Quick Actions</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Access all major sections quickly with shortcut buttons - perfect for mobile users!</p>
                            </div>
                            
                            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                                <h3 class="font-bold text-purple-900 dark:text-purple-400 mb-2">📋 Recent Activity</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">See the latest 5 leave requests and feedback submissions at a glance.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Staff Management Section -->
                @if($activeSection === 'staff')
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
                        <h2 class="text-2xl font-black dark:text-white mb-4">Staff Management</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Manage your organization's employees efficiently.</p>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">👥 Staff Directory</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> View all employees with their photos, designations, and departments</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Search by name, employee code, or designation</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Filter by department and employment status (active/inactive)</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Click on any employee to view their full profile</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📝 Employee Profiles</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-green-600">•</span> <strong>Personal Info:</strong> View and edit employee details, contact information, and emergency contacts</li>
                                    <li class="flex gap-2"><span class="text-green-600">•</span> <strong>Documents:</strong> Access employee documents, upload new files (contracts, certificates, IDs)</li>
                                    <li class="flex gap-2"><span class="text-green-600">•</span> <strong>Leave History:</strong> Review all leave requests with status and dates</li>
                                    <li class="flex gap-2"><span class="text-green-600">•</span> <strong>Payroll:</strong> (Coming soon) Access salary and payment information</li>
                                </ul>
                            </div>

                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800">
                                <h4 class="font-bold text-amber-900 dark:text-amber-400 mb-2">💡 Pro Tip</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Use the search and filter features together to quickly find specific employees. The filters update in real-time!</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Leave Management Section -->
                @if($activeSection === 'leaves')
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
                        <h2 class="text-2xl font-black dark:text-white mb-4">Leave Management</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Handle employee leave requests efficiently.</p>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📊 Dashboard Statistics</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-amber-600">•</span> Pending requests awaiting your approval</li>
                                    <li class="flex gap-2"><span class="text-green-600">•</span> Employees currently on leave today</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Available staff for the day</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">✅ Approving Leaves</h3>
                                <ol class="space-y-2 text-sm text-slate-600 dark:text-slate-400 list-decimal list-inside">
                                    <li>Review the employee's leave request details (dates, reason, type)</li>
                                    <li>Check handover instructions if provided</li>
                                    <li>Click the "Approve" or "Reject" button</li>
                                    <li>Add optional comments for the employee</li>
                                    <li>Confirm your decision</li>
                                </ol>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">🔍 Filtering & Search</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Filter by status: All, Pending, Approved, Rejected</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Filter by date range to see leaves for specific periods</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> View duration calculations automatically</li>
                                </ul>
                            </div>

                            <div class="p-4 bg-rose-50 dark:bg-rose-900/20 rounded-xl border border-rose-200 dark:border-rose-800">
                                <h4 class="font-bold text-rose-900 dark:text-rose-400 mb-2">⚠️ Important</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Always check the "On Leave Today" and "Available Staff" counts before approving new leaves to ensure adequate staffing.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Feedback Section -->
                @if($activeSection === 'feedback')
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
                        <h2 class="text-2xl font-black dark:text-white mb-4">Feedback & Complaints Management</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Monitor and respond to employee feedback.</p>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📈 Statistics Overview</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Total tickets received</li>
                                    <li class="flex gap-2"><span class="text-rose-600">•</span> Open complaints requiring attention</li>
                                    <li class="flex gap-2"><span class="text-green-600">•</span> Resolved issues</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Suggestions submitted</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">🎯 Ticket Categories</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                                        <p class="font-bold text-rose-600 text-sm">Complaints</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Issues that need resolution</p>
                                    </div>
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <p class="font-bold text-blue-600 text-sm">Suggestions</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Ideas for improvement</p>
                                    </div>
                                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <p class="font-bold text-green-600 text-sm">Feedback</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">General comments</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">🔄 Status Management</h3>
                                <ol class="space-y-2 text-sm text-slate-600 dark:text-slate-400 list-decimal list-inside">
                                    <li>Click "Update Status" on any ticket</li>
                                    <li>Choose new status: New → In Progress → Resolved → Closed</li>
                                    <li>Add admin remarks explaining actions taken</li>
                                    <li>Submit to update and notify the employee</li>
                                </ol>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">🔍 Filtering Options</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Filter by status to focus on open issues</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Filter by category to see complaints, suggestions, or feedback</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> View attachments when employees provide evidence</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> See anonymous submissions (identity protected)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Departments Section -->
                @if($activeSection === 'departments')
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
                        <h2 class="text-2xl font-black dark:text-white mb-4">Department Management</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Organize your company structure.</p>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">➕ Adding a Department</h3>
                                <ol class="space-y-2 text-sm text-slate-600 dark:text-slate-400 list-decimal list-inside">
                                    <li>Click "Add Department" button</li>
                                    <li>Fill in required fields:
                                        <ul class="ml-6 mt-1 space-y-1">
                                            <li>• Department Name (e.g., "Human Resources")</li>
                                            <li>• Prefix (e.g., "HR") - used for employee codes</li>
                                            <li>• Optional: Title and Description</li>
                                            <li>• Optional: Assign a Department Head</li>
                                        </ul>
                                    </li>
                                    <li>Click "Create Department"</li>
                                </ol>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">✏️ Editing Departments</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Click the edit icon on any department</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Update information as needed</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Save changes</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">🗑️ Deleting Departments</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Click the delete icon to remove a department.</p>
                                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                                    <p class="text-sm text-amber-900 dark:text-amber-400"><strong>Note:</strong> You cannot delete departments that have employees assigned. Reassign employees first.</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">👁️ Viewing Information</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-green-600">•</span> See employee count for each department</li>
                                    <li class="flex gap-2"><span class="text-green-600">•</span> View assigned department heads</li>
                                    <li class="flex gap-2"><span class="text-green-600">•</span> Check department prefix for employee codes</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Leave Types Section -->
                @if($activeSection === 'leavetypes')
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
                        <h2 class="text-2xl font-black dark:text-white mb-4">Leave Types Management</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Configure available leave categories.</p>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📝 Common Leave Types</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <p class="font-bold text-sm">Sick Leave</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">For medical reasons</p>
                                    </div>
                                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <p class="font-bold text-sm">Annual Leave</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Vacation time</p>
                                    </div>
                                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                        <p class="font-bold text-sm">Casual Leave</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Short-term personal leave</p>
                                    </div>
                                    <div class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                                        <p class="font-bold text-sm">Emergency Leave</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Urgent situations</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">➕ Creating Leave Types</h3>
                                <ol class="space-y-2 text-sm text-slate-600 dark:text-slate-400 list-decimal list-inside">
                                    <li>Click "Add Leave Type" button</li>
                                    <li>Enter leave type name (must be unique)</li>
                                    <li>Add optional description explaining when to use this type</li>
                                    <li>Click "Create Leave Type"</li>
                                </ol>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">✏️ Editing Leave Types</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Click edit icon on any leave type</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Modify name or description</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> Names must remain unique</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">🗑️ Deleting Leave Types</h3>
                                <div class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-lg border border-rose-200 dark:border-rose-800">
                                    <p class="text-sm text-rose-900 dark:text-rose-400"><strong>Protection:</strong> Leave types currently in use (with existing requests) cannot be deleted. This prevents data integrity issues.</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📊 Usage Tracking</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Each leave type shows a usage count badge indicating how many requests use that category.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Getting Help -->
                <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl p-8 text-white">
                    <h3 class="text-xl font-bold mb-3">Need More Help?</h3>
                    <p class="text-blue-100 mb-4">If you have questions or encounter issues not covered in this guide:</p>
                    <ul class="space-y-2 text-sm text-blue-100">
                        <li class="flex gap-2">• Contact IT Support for technical issues</li>
                        <li class="flex gap-2">• Reach out to HR for policy questions</li>
                        <li class="flex gap-2">• Check system notifications for updates</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
