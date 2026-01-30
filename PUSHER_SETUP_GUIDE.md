# 🚀 Pusher Setup - Step by Step Guide

Follow these exact steps to get real-time notifications working on Hostinger.

## Step 1: Create Pusher Account (2 minutes)

1. Go to https://pusher.com
2. Click **"Sign Up"** (top right)
3. Use your email or sign up with GitHub/Google
4. Verify your email

## Step 2: Create Pusher App (2 minutes)

1. After logging in, click **"Create app"**
2. Fill in the form:
   - **App name**: `HR System` (or any name you like)
   - **Cluster**: Choose closest to your users:
     - `mt1` = US East Coast
     - `us2` = US West Coast
     - `eu` = Europe
     - `ap1` = Asia Pacific
   - **Tech stack**: Select **Laravel**
3. Click **"Create app"**

## Step 3: Get Your Credentials (1 minute)

1. Click on your new app
2. Go to **"App Keys"** tab
3. You'll see:
   - `app_id` (e.g., 1234567)
   - `key` (e.g., a1b2c3d4e5f6g7h8i9j0)
   - `secret` (e.g., x1y2z3w4v5u6t7s8r9q0)
   - `cluster` (e.g., mt1)

**Keep this page open!** You'll need these values.

## Step 4: Update Your .env File

Open your `.env` file and find these lines:

```env
PUSHER_APP_ID=your_app_id_here
PUSHER_APP_KEY=your_app_key_here
PUSHER_APP_SECRET=your_app_secret_here
PUSHER_APP_CLUSTER=mt1
```

Replace with YOUR actual values from Pusher:

```env
PUSHER_APP_ID=1234567
PUSHER_APP_KEY=a1b2c3d4e5f6g7h8i9j0
PUSHER_APP_SECRET=x1y2z3w4v5u6t7s8r9q0
PUSHER_APP_CLUSTER=mt1
```

**Important**: Don't add quotes around the values!

## Step 5: Build Frontend Assets

Open terminal in your project folder and run:

```bash
npm run build
```

Wait for it to finish. You should see `✓ built in` message.

## Step 6: Upload to Hostinger

Upload these files/folders to Hostinger:
- `.env` (with your Pusher credentials)
- `public/build/` folder
- All PHP files (if not already uploaded)

## Step 7: Setup Cron Job on Hostinger

1. Log into **Hostinger Panel**
2. Go to **Advanced** → **Cron Jobs**
3. Click **"Create Cron Job"**
4. Set:
   - **Common Settings**: Custom
   - **Minute**: `*`
   - **Hour**: `*`
   - **Day**: `*`
   - **Month**: `*`
   - **Weekday**: `*`
   - **Command**: 
     ```bash
     cd /home/your-username/public_html && php artisan schedule:run >> /dev/null 2>&1
     ```
   
   Replace `your-username` with your actual Hostinger username!

5. Click **"Create"**

## Step 8: Clear Cache on Server

Connect to your Hostinger via **SSH** or use **File Manager**:

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 9: Test It! 🎉

1. **Open browser**: Go to your website
2. **Login as Admin**: Open the dashboard
3. **Open another browser/incognito**: Login as employee
4. **As employee**: Submit a leave request or complaint
5. **Watch admin dashboard**: It should update automatically!

You should see:
- ✅ Toast notification popup
- ✅ Stats refresh automatically
- ✅ New entry appears in list

## Troubleshooting

### "It's not working!"

**Check 1: Pusher Credentials**
- Make sure you copied ALL values correctly
- No spaces before or after values
- No quotes around values

**Check 2: Browser Console**
1. Press `F12` to open developer tools
2. Go to **Console** tab
3. Refresh page
4. Look for errors (red text)
5. You should see: `Pusher : State changed : connecting -> connected`

**Check 3: Pusher Debug Console**
1. Go to pusher.com
2. Click your app
3. Go to **"Debug Console"**
4. Submit a leave request
5. You should see the event appear!

### Common Issues

❌ **"Connection failed"**
→ Check your PUSHER_APP_KEY and PUSHER_APP_CLUSTER in .env

❌ **"Nothing happens"**
→ Make sure you ran `npm run build` and uploaded the build folder

❌ **"Only works locally"**
→ Upload the `public/build` folder to Hostinger

## Verify Setup is Correct

Run this command to check configuration:

```bash
php artisan tinker
```

Then type:
```php
config('broadcasting.connections.pusher.key')
```

Should show your Pusher key. If it shows `null`, run:
```bash
php artisan config:clear
```

## Production Checklist

- [ ] Pusher account created ✓
- [ ] App created on Pusher ✓
- [ ] Credentials copied to .env ✓
- [ ] `npm run build` executed ✓
- [ ] Files uploaded to Hostinger ✓
- [ ] Cron job created ✓
- [ ] Cache cleared on server ✓
- [ ] Tested and working! ✓

## Need More Help?

### Check Pusher Connection Status

Add this to any page temporarily:

```javascript
<script>
console.log('Pusher Key:', import.meta.env.VITE_PUSHER_APP_KEY);
console.log('Pusher Cluster:', import.meta.env.VITE_PUSHER_APP_CLUSTER);
</script>
```

### Test Broadcasting Manually

```bash
php artisan tinker
>>> event(new \App\Events\ComplaintCreated(\App\Models\Complaint::first()))
```

Check Pusher Debug Console to see if event was received.

## Free Tier Limits

Your free Pusher account includes:
- ✅ 100 concurrent connections
- ✅ 200,000 messages per day
- ✅ Unlimited channels
- ✅ SSL included
- ✅ Support included

This is perfect for most small to medium businesses!

---

**That's it!** Your real-time notifications should now be working on Hostinger. 🎉
