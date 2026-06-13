# 🚀 External APIs Setup Instructions

## Quick Start: 5 APIs in 10 Minutes

### Step 1: Install Packages (DONE ✅)

Packages already installed:
- ✅ `stripe/stripe-php` - Stripe payment processing
- ✅ `twilio/sdk` - SMS notifications
- ✅ `cloudinary/cloudinary_php` - Image management
- ✅ `symfony/mailgun-mailer` - Email service

### Step 2: Get API Credentials

#### 🔵 Stripe

1. Go to: https://dashboard.stripe.com/register
2. Sign up → Accept terms
3. Go to: https://dashboard.stripe.com/apikeys
4. Copy **Publishable Key** (starts with `pk_test_`)
5. Copy **Secret Key** (starts with `sk_test_`)

**Paste in `.env`:**
```env
STRIPE_PUBLIC_KEY=pk_test_YOUR_KEY_HERE
STRIPE_SECRET_KEY=sk_test_YOUR_KEY_HERE
```

---

#### 🟢 Twilio

1. Go to: https://www.twilio.com/console
2. Sign up → Create account
3. Copy **Account SID** and **Auth Token**
4. Go to: https://www.twilio.com/console/phone-numbers/incoming
5. Click "Get a Number" → Pick country → Get number
6. Copy the phone number (with +1)

**Paste in `.env`:**
```env
TWILIO_ACCOUNT_SID=ACxxxxx
TWILIO_AUTH_TOKEN=xxxxx
TWILIO_PHONE_NUMBER=+1234567890
```

---

#### 📧 Mailgun

1. Go to: https://signup.mailgun.com/new/signup
2. Sign up → Create account
3. Go to: https://app.mailgun.com/app/domains
4. Create domain or use default sandbox
5. Go to Settings → API Keys
6. Copy **Domain** and **API Key**

**Paste in `.env`:**
```env
MAILGUN_DOMAIN=sandboxXXX.mailgun.org
MAILGUN_SECRET=key-xxxxx
```

---

#### 🗺️ Google Maps

1. Go to: https://console.cloud.google.com
2. Create new project
3. Enable APIs:
   - Distance Matrix API
   - Geocoding API
   - Maps JavaScript API
4. Go to: Credentials → Create API Key
5. Copy API Key

**Paste in `.env`:**
```env
GOOGLE_MAPS_API_KEY=AIzaSy_xxxxx
```

---

#### 🖼️ Cloudinary

1. Go to: https://cloudinary.com/users/register
2. Sign up → Create account
3. Go to: Dashboard → Settings → API Keys
4. Copy:
   - Cloud Name
   - API Key
   - API Secret

**Paste in `.env`:**
```env
CLOUDINARY_CLOUD_NAME=dxxxxx
CLOUDINARY_API_KEY=123456789
CLOUDINARY_API_SECRET=abcdefg
```

---

### Step 3: Update `.env` File

Edit `d:\APIIT\Thushain\ssp2\Hot Dish (2)\Hot Dish\.env` and add all 5 API credentials:

```env
# ==========================================
# EXTERNAL API INTEGRATIONS
# ==========================================

# STRIPE PAYMENT GATEWAY
STRIPE_PUBLIC_KEY=pk_test_YOUR_KEY
STRIPE_SECRET_KEY=sk_test_YOUR_KEY

# TWILIO SMS NOTIFICATIONS
TWILIO_ACCOUNT_SID=AC123456789
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_PHONE_NUMBER=+1234567890

# MAILGUN EMAIL SERVICE
MAILGUN_DOMAIN=sandboxXXX.mailgun.org
MAILGUN_SECRET=key-YOUR_KEY

# GOOGLE MAPS API
GOOGLE_MAPS_API_KEY=AIzaSy_YOUR_KEY

# CLOUDINARY IMAGE STORAGE
CLOUDINARY_CLOUD_NAME=dxxxxx
CLOUDINARY_API_KEY=123456789
CLOUDINARY_API_SECRET=abcdefg
```

---

### Step 4: Test APIs

#### Test Stripe
```bash
curl -X GET http://localhost:8000/api/payment/stripe-key \
  -H "Accept: application/json"
```

Expected: Returns your Stripe public key

#### Test Location
```bash
curl -X POST http://localhost:8000/api/location/calculate-delivery \
  -H "Content-Type: application/json" \
  -d '{
    "origin": "New York, NY",
    "destination": "Brooklyn, NY"
  }'
```

Expected: Distance and estimated delivery time

#### Test Authenticated Endpoints
```bash
# 1. Login to get token
TOKEN=$(curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }' | jq -r '.token')

# 2. Use token for payment
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

---

## 📊 What Each API Does

| API | Purpose | Use Case |
|-----|---------|----------|
| **Stripe** | Payment processing | Customer checkout |
| **Twilio** | SMS notifications | Order updates, OTP |
| **Mailgun** | Email service | Receipts, confirmations |
| **Google Maps** | Location services | Delivery tracking, distance |
| **Cloudinary** | Image storage | Menu photos, optimization |

---

## 🔗 Integration Points in Code

### Payment Flow
```
Customer Checkout
    ↓
POST /api/payment/stripe-key (get public key)
    ↓
Stripe.js processes card
    ↓
POST /api/payment/process (send token)
    ↓
StripePaymentService processes
    ↓
Mailgun sends confirmation email
    ↓
Twilio sends SMS notification
```

### Image Upload Flow
```
Admin uploads menu item image
    ↓
POST /api/image/upload
    ↓
CloudinaryImageService uploads
    ↓
Returns optimized URL
    ↓
Stored in database
```

### Delivery Tracking Flow
```
Customer enters delivery address
    ↓
POST /api/location/geocode (convert to coordinates)
    ↓
POST /api/location/calculate-delivery
    ↓
GoogleMapsService calculates distance
    ↓
Estimates delivery time
    ↓
Shows to customer
```

---

## 🎯 Free Tier Limits

| Service | Free Tier | Cost After |
|---------|-----------|------------|
| Stripe | 0% fees on transactions | 2.9% + $0.30 per transaction |
| Twilio | $15 credit (30 SMS) | $0.0075 per SMS |
| Mailgun | 5,000 emails/month | $0.50 per 1,000 emails |
| Google Maps | $200 credit/month | $0.005 - $0.015 per request |
| Cloudinary | 25 GB/month storage | Additional storage paid |

---

## ✅ Verification Checklist

After setup, verify:

- [ ] `.env` file has all 5 API keys
- [ ] No API keys committed to Git
- [ ] `/api/payment/stripe-key` returns public key
- [ ] `/api/location/calculate-delivery` returns distance
- [ ] User can upload images via API
- [ ] Payment test succeeds
- [ ] Email sent to admin
- [ ] SMS sent to phone

---

## 🆘 Common Issues & Fixes

### `.env` file not being read
```bash
php artisan config:cache
php artisan config:clear
```

### API key format invalid
- Stripe: Start with `pk_test_` or `sk_test_`
- Twilio: Account SID is 34 chars, starts with `AC`
- Mailgun: Domain includes `.mailgun.org`
- Google Maps: Starts with `AIzaSy_`
- Cloudinary: Cloud name is lowercase

### 401 Unauthorized errors
- Token missing: Add `Authorization: Bearer <token>` header
- Token expired: Login again to get new token
- Admin route: User must have `role = 'admin'`

### CORS/CSRF issues
- Ensure routes are in `api.php` (uses JSON)
- Web routes automatically have CSRF protection
- Localhost automatically allowed for CSRF

---

## 📚 Documentation

- **Full API Docs:** See [API_INTEGRATION.md](API_INTEGRATION.md)
- **Service Classes:** Located in `app/Services/`
- **Controllers:** Located in `app/Http/Controllers/Api/`
- **Routes:** Located in `routes/api.php`

---

**Setup Complete! 🎉**

Your Hot Dish app now has all 5 external APIs integrated and ready to use!
