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
    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black">Employee Guide</h1>
                    <p class="text-emerald-100 mt-1">Your complete guide to using the HR system</p>
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
                        <button wire:click="setSection('dashboard')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'dashboard' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Dashboard Overview
                        </button>
                        <button wire:click="setSection('profile')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'profile' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            My Profile
                        </button>
                        <button wire:click="setSection('leave')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'leave' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Applying for Leave
                        </button>
                        <button wire:click="setSection('feedback')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'feedback' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Feedback & Complaints
                        </button>
                        <button wire:click="setSection('documents')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold transition {{ $activeSection === 'documents' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Documents
                        </button>
                    </nav>
                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800">
                        <a href="/empdashboard" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white text-center px-4 py-2 rounded-lg text-sm font-bold transition">
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
                        <h2 class="text-2xl font-black dark:text-white mb-4">Your Dashboard</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Your personal dashboard gives you a quick overview of everything you need.</p>
                        
                        <div class="space-y-4">
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                                <h3 class="font-bold text-emerald-900 dark:text-emerald-400 mb-2">👋 Welcome Banner</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">See a personalized greeting with your name, designation, and current date/time.</p>
                            </div>
                            
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                                <h3 class="font-bold text-blue-900 dark:text-blue-400 mb-2">📊 Your Statistics</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Six key cards showing:</p>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1 ml-4">
                                    <li>• Total Leave Balance - Your available leave days</li>
                                    <li>• Pending Requests - Leaves awaiting approval</li>
                                    <li>• Leave This Month - Days taken in current month</li>
                                    <li>• Time in Company - Your tenure</li>
                                    <li>• Team Members - People in your department</li>
                                    <li>• Upcoming Leaves - Your approved upcoming time off</li>
                                </ul>
                            </div>
                            
                            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                                <h3 class="font-bold text-purple-900 dark:text-purple-400 mb-2">⚡ Quick Actions</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Four shortcut buttons for common tasks - apply for leave, submit feedback, view documents, and check your profile.</p>
                            </div>

                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                                <h3 class="font-bold text-amber-900 dark:text-amber-400 mb-2">📅 Upcoming Leaves</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">See your next approved leaves at a glance with dates and leave types.</p>
                            </div>

                            <div class="p-4 bg-rose-50 dark:bg-rose-900/20 rounded-xl">
                                <h3 class="font-bold text-rose-900 dark:text-rose-400 mb-2">👤 Your Manager</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Quick access to your reporting manager's contact information.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Profile Section -->
                @if($activeSection === 'profile')
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
                        <h2 class="text-2xl font-black dark:text-white mb-4">Your Profile</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">View and manage your personal information.</p>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">👁️ Viewing Your Profile</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> Click your profile picture or "My Profile" in navigation</li>
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> View your complete employment information</li>
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> See contact details and emergency contacts</li>
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> Check reporting manager and department</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📑 Profile Tabs</h3>
                                <div class="space-y-3">
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <p class="font-bold text-blue-600 text-sm mb-1">Personal Information</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Basic details, contact info, address, emergency contacts</p>
                                    </div>
                                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <p class="font-bold text-green-600 text-sm mb-1">Documents</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">View and download your employment documents (contracts, certificates, IDs)</p>
                                    </div>
                                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                        <p class="font-bold text-purple-600 text-sm mb-1">Leave History</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Complete record of all your leave requests with status</p>
                                    </div>
                                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                                        <p class="font-bold text-amber-600 text-sm mb-1">Payroll</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">(Coming soon) Salary information and payment history</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">✏️ Updating Information</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">Some information can be updated directly:</p>
                                <ol class="space-y-2 text-sm text-slate-600 dark:text-slate-400 list-decimal list-inside">
                                    <li>Look for the "Edit Profile" button</li>
                                    <li>Update allowed fields (phone, address, emergency contacts)</li>
                                    <li>Save your changes</li>
                                </ol>
                                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <p class="text-sm text-blue-900 dark:text-blue-400"><strong>Note:</strong> Some fields like employee code, email, and designation can only be changed by HR admin.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Leave Section -->
                @if($activeSection === 'leave')
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
                        <h2 class="text-2xl font-black dark:text-white mb-4">Applying for Leave</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Request time off easily through the system.</p>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📝 How to Apply</h3>
                                <ol class="space-y-3 text-sm text-slate-600 dark:text-slate-400 list-decimal list-inside">
                                    <li><strong>Navigate:</strong> Go to "Apply Leave" from dashboard or navigation menu</li>
                                    <li><strong>Select Leave Type:</strong> Choose from available categories (Sick Leave, Annual Leave, etc.)</li>
                                    <li><strong>Choose Dates:</strong> Select start and end dates for your leave</li>
                                    <li><strong>Provide Reason:</strong> Explain why you need the leave (required)</li>
                                    <li><strong>Handover Instructions:</strong> Add notes about task handover if applicable</li>
                                    <li><strong>Submit:</strong> Click "Submit Leave Request" and wait for approval</li>
                                </ol>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📅 Leave Types</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                                        <p class="font-bold text-sm">🏥 Sick Leave</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">When you're ill and need rest</p>
                                    </div>
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <p class="font-bold text-sm">🏖️ Annual Leave</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Planned vacation time</p>
                                    </div>
                                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <p class="font-bold text-sm">🌟 Casual Leave</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Short-term personal needs</p>
                                    </div>
                                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                                        <p class="font-bold text-sm">🚨 Emergency Leave</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Urgent unforeseen situations</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">⏱️ Processing Time</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> Requests are reviewed by your manager/admin</li>
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> You'll see status updates in your dashboard</li>
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> Check "Leave History" tab in your profile for past requests</li>
                                </ul>
                            </div>

                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800">
                                <h4 class="font-bold text-amber-900 dark:text-amber-400 mb-2">💡 Best Practices</h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Apply well in advance for planned leaves</li>
                                    <li>• Provide clear reasons to speed up approval</li>
                                    <li>• Add handover instructions for urgent work</li>
                                    <li>• Check your balance before applying</li>
                                    <li>• Contact your manager for urgent leaves</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📊 Leave Status</h3>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3 p-2 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                                        <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900 text-amber-600 text-xs font-bold rounded">PENDING</span>
                                        <span class="text-sm text-slate-600 dark:text-slate-400">Awaiting manager approval</span>
                                    </div>
                                    <div class="flex items-center gap-3 p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-600 text-xs font-bold rounded">APPROVED</span>
                                        <span class="text-sm text-slate-600 dark:text-slate-400">Your leave is confirmed</span>
                                    </div>
                                    <div class="flex items-center gap-3 p-2 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                                        <span class="px-2 py-1 bg-rose-100 dark:bg-rose-900 text-rose-600 text-xs font-bold rounded">REJECTED</span>
                                        <span class="text-sm text-slate-600 dark:text-slate-400">Request was denied (check remarks)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Feedback Section -->
                @if($activeSection === 'feedback')
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
                        <h2 class="text-2xl font-black dark:text-white mb-4">Feedback & Complaints</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Share your thoughts, suggestions, or report issues.</p>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📢 Submission Types</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="p-4 bg-rose-50 dark:bg-rose-900/20 rounded-lg text-center">
                                        <div class="text-3xl mb-2">😟</div>
                                        <p class="font-bold text-rose-600 text-sm">Complaint</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Report problems or issues</p>
                                    </div>
                                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
                                        <div class="text-3xl mb-2">💡</div>
                                        <p class="font-bold text-blue-600 text-sm">Suggestion</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Ideas for improvement</p>
                                    </div>
                                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-center">
                                        <div class="text-3xl mb-2">💬</div>
                                        <p class="font-bold text-green-600 text-sm">Feedback</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">General comments</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📝 How to Submit</h3>
                                <ol class="space-y-3 text-sm text-slate-600 dark:text-slate-400 list-decimal list-inside">
                                    <li><strong>Access Form:</strong> Click "Submit Feedback" from dashboard or navigation</li>
                                    <li><strong>Choose Type:</strong> Select Complaint, Suggestion, or Feedback</li>
                                    <li><strong>Add Subject:</strong> Brief title for your submission</li>
                                    <li><strong>Write Description:</strong> Explain in detail (required)</li>
                                    <li><strong>Optional Attachment:</strong> Upload supporting files if needed</li>
                                    <li><strong>Anonymous Option:</strong> Check if you want to submit anonymously</li>
                                    <li><strong>Submit:</strong> Click "Submit" to send to HR admin</li>
                                </ol>
                            </div>

                            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                                <h3 class="font-bold text-purple-900 dark:text-purple-400 mb-2">🔒 Anonymous Submissions</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">You can submit anonymously for sensitive matters. Your identity will be completely hidden from all users, including admins.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📊 Tracking Your Submissions</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> <strong>New:</strong> Just submitted, waiting for review</li>
                                    <li class="flex gap-2"><span class="text-blue-600">•</span> <strong>In Progress:</strong> Being worked on by admin</li>
                                    <li class="flex gap-2"><span class="text-green-600">•</span> <strong>Resolved:</strong> Issue addressed, solution provided</li>
                                    <li class="flex gap-2"><span class="text-slate-600">•</span> <strong>Closed:</strong> Completed and archived</li>
                                </ul>
                            </div>

                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                <h4 class="font-bold text-blue-900 dark:text-blue-400 mb-2">💡 Tips for Effective Submissions</h4>
                                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                                    <li>• Be specific and provide details</li>
                                    <li>• Include relevant dates, names, or locations</li>
                                    <li>• Attach evidence if available (photos, documents)</li>
                                    <li>• Be professional and constructive</li>
                                    <li>• Check for admin remarks in response</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Documents Section -->
                @if($activeSection === 'documents')
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
                        <h2 class="text-2xl font-black dark:text-white mb-4">Your Documents</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">Access your employment documents anytime.</p>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📂 Document Types</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <p class="font-bold text-sm">📄 Employment Contract</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Your signed employment agreement</p>
                                    </div>
                                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <p class="font-bold text-sm">🎓 Certificates</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Educational and professional certificates</p>
                                    </div>
                                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                        <p class="font-bold text-sm">🆔 Identification</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">ID cards, passport copies</p>
                                    </div>
                                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                                        <p class="font-bold text-sm">📋 Other Documents</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Additional employment-related files</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">👁️ Viewing Documents</h3>
                                <ol class="space-y-2 text-sm text-slate-600 dark:text-slate-400 list-decimal list-inside">
                                    <li>Go to your profile page</li>
                                    <li>Click on the "Documents" tab</li>
                                    <li>Browse all your uploaded documents</li>
                                    <li>View document type, upload date, and file name</li>
                                </ol>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">⬇️ Downloading Documents</h3>
                                <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> Click the download button next to any document</li>
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> File will be saved to your device</li>
                                    <li class="flex gap-2"><span class="text-emerald-600">•</span> Supported formats: PDF, DOC, DOCX, JPG, PNG</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📤 Uploading Documents</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">If you need to upload new documents:</p>
                                <ol class="space-y-2 text-sm text-slate-600 dark:text-slate-400 list-decimal list-inside">
                                    <li>Contact HR admin to request document upload</li>
                                    <li>Prepare your file (max 10MB)</li>
                                    <li>Ensure file is in acceptable format</li>
                                    <li>Admin will upload and make it available to you</li>
                                </ol>
                            </div>

                            <div class="p-4 bg-rose-50 dark:bg-rose-900/20 rounded-xl border border-rose-200 dark:border-rose-800">
                                <h4 class="font-bold text-rose-900 dark:text-rose-400 mb-2">🔒 Security Notice</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">These documents contain sensitive personal information. Keep downloaded copies secure and do not share with unauthorized persons.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold dark:text-white mb-3">📋 Accepted File Types</h3>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 text-xs font-bold rounded-full">PDF</span>
                                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 text-xs font-bold rounded-full">DOC</span>
                                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 text-xs font-bold rounded-full">DOCX</span>
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 text-xs font-bold rounded-full">JPG</span>
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 text-xs font-bold rounded-full">PNG</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Getting Help -->
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-8 text-white">
                    <h3 class="text-xl font-bold mb-3">Need Help?</h3>
                    <p class="text-emerald-100 mb-4">If you have questions or need assistance:</p>
                    <ul class="space-y-2 text-sm text-emerald-100">
                        <li class="flex gap-2">• Contact your manager for work-related questions</li>
                        <li class="flex gap-2">• Reach out to HR for policy or system issues</li>
                        <li class="flex gap-2">• Use the feedback form to report technical problems</li>
                        <li class="flex gap-2">• Check dashboard notifications for important updates</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
