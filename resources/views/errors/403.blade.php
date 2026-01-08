<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Unauthorized Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 dark:bg-slate-950">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8 shadow-lg text-center">
                <!-- Icon -->
                <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>

                <!-- Error Code -->
                <h1 class="text-6xl font-black text-slate-900 dark:text-white mb-2">403</h1>
                
                <!-- Error Message -->
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Access Denied</h2>
                <p class="text-slate-600 dark:text-slate-400 mb-6">
                    {{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="javascript:history.back()" class="flex-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-900 dark:text-white px-6 py-3 rounded-xl font-bold text-sm transition">
                        Go Back
                    </a>
                    <a href="/dashboard" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition">
                        Go to Dashboard
                    </a>
                </div>

                <!-- Help Text -->
                <p class="text-xs text-slate-400 mt-6">
                    If you believe this is an error, please contact your system administrator.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
