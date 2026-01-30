# Real-Time Notifications - Pusher Setup (Hostinger Compatible)

This application supports real-time notifications for complaints/feedback and leave requests using **Pusher** - perfect for shared hosting like Hostinger!

## Why Pusher?

✅ **No Server Processes** - Works on shared hosting (Hostinger)
✅ **Free Tier** - 100 concurrent connections, 200k messages/day
✅ **Reliable** - Managed service, no maintenance needed
✅ **Easy Setup** - Just add credentials to `.env`

## Features

✅ **Real-time Updates**: Admin pages automatically refresh when new complaints or leave requests are created
✅ **Browser Notifications**: Desktop notifications for new submissions
✅ **Toast Messages**: In-app notification toasts
✅ **No Background Processes**: Perfect for shared hosting!

## Quick Setup (5 Minutes)

### Step 1: Create Free Pusher Account

1. Go to [pusher.com](https://pusher.com) and sign up (free)
2. Create a new app:
   - Name: `HR System`
   - Cluster: Choose closest to your location (e.g., `mt1` for US East, `eu` for Europe)
   - Tech: Select `Laravel`
3. Go to "App Keys" tab and copy your credentials

### Step 2: Update Your `.env` File

Replace these values with your Pusher credentials:

```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your_app_id_here
PUSHER_APP_KEY=your_app_key_here
PUSHER_APP_SECRET=your_app_secret_here
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

**Note**: The `.env` file already has placeholder values. Just replace them with your actual Pusher credentials.

### Step 3: Build Frontend Assets

```bash
npm install
npm run build
```

### Step 4: Setup Queue Worker (Hostinger)

Add this to your **crontab** in Hostinger cPanel:

```bash
* * * * * cd /home/your-username/public_html && php artisan schedule:run >> /dev/null 2>&1
```

Then add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('queue:work --stop-when-empty')->everyMinute();
}
```

### Step 5: Upload and Test

1. Upload all files to Hostinger
2. Make sure `.env` has your Pusher credentials
3. Clear cache: `php artisan config:clear`
4. Test it!

## Testing

1. **As Admin**: Open dashboard
2. **As Employee**: Submit a leave request or complaint
3. **Watch**: Admin dashboard updates automatically! 🎉

## How It Works

When an employee submits:
- Employee → `ComplaintCreated` or `LeaveRequestCreated` event
- Event → Pusher servers (via queue job)
- Pusher → All connected admin browsers
- Browser → Livewire refreshes data automatically

## Troubleshooting

### Not Working?

**Check 1: Pusher Credentials**
```bash
php artisan tinker
>>> config('broadcasting.connections.pusher')
```
Make sure credentials are loaded.

**Check 2: Queue is Running**
```bash
php artisan queue:work --once
```
Should process any pending jobs.

**Check 3: Browser Console**
Open browser console (F12), look for:
- Pusher connection messages
- Any JavaScript errors

**Check 4: Test Event**
```bash
php artisan tinker
>>> event(new \App\Events\ComplaintCreated(\App\Models\Complaint::first()))
```

### Common Issues

**"Pusher connection failed"**
- Verify credentials in `.env`
- Run `php artisan config:clear`
- Check if PUSHER_APP_CLUSTER matches your Pusher app

**"Events not broadcasting"**
- Make sure queue is running (cron job)
- Check `storage/logs/laravel.log` for errors
- Verify `BROADCAST_CONNECTION=pusher` in `.env`

**"Page not updating"**
- Hard refresh browser (Ctrl+Shift+R)
- Clear browser cache
- Make sure you're logged in as admin

## Production Checklist

- [ ] Pusher account created
- [ ] Credentials added to `.env`
- [ ] `npm run build` executed
- [ ] All files uploaded to Hostinger
- [ ] Cron job configured for queue
- [ ] `php artisan config:clear` executed on server
- [ ] Tested with employee submitting and admin viewing

## Local Development

For local development, you can use Pusher too! Just use the same credentials. No need to run Reverb.

## Cost

**Pusher Free Tier:**
- 100 max concurrent connections
- 200,000 messages per day
- Unlimited channels

This is perfect for small to medium HR systems. If you grow beyond this, Pusher has affordable paid plans starting at $49/month.

## Advanced: Checking Pusher Dashboard

1. Log into [pusher.com](https://pusher.com)
2. Select your app
3. Go to "Debug Console"
4. Submit a leave request/complaint
5. You should see the event appear in real-time!

This is great for debugging connection issues.

## Need Help?

The following files contain the real-time logic:
- Events: `app/Events/ComplaintCreated.php`, `LeaveRequestCreated.php`
- Listeners: `resources/js/echo.js`
- Admin pages with auto-refresh: `admindashboard.blade.php`, `leaverequest.blade.php`, `feedbacklogs.blade.php`

## Development Notes

- Events are located in `app/Events/`
- Broadcasting listeners are in `resources/js/echo.js`
- Livewire listeners are in the respective blade components
- Toast notification component is at `resources/views/components/toast-notification.blade.php`

## Additional Resources

- [Laravel Broadcasting Documentation](https://laravel.com/docs/broadcasting)
- [Laravel Reverb Documentation](https://laravel.com/docs/reverb)
- [Livewire Events Documentation](https://livewire.laravel.com/docs/events)
