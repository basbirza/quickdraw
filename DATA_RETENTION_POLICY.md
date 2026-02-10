# Data Retention Policy
**Quickdraw Pressing Co.**
**Effective Date:** February 10, 2026
**GDPR Article 5(1)(e) Compliance**

---

## Purpose

This policy defines how long Quickdraw Pressing Co. retains personal data and the criteria used to determine retention periods. We comply with GDPR requirements to not keep data longer than necessary.

---

## Data Retention Periods

### 1. Order Data
**Retention Period:** 7 years from order date
**Legal Basis:** Dutch tax law requires retention of financial records for 7 years
**Data Included:**
- Customer name (encrypted)
- Email address (encrypted)
- Billing/shipping addresses (encrypted)
- Order details and items
- Payment information

**After 7 years:**
- Order records are permanently deleted
- Financial summaries retained for accounting (anonymized)

---

### 2. Newsletter Subscriptions
**Retention Period:** Until unsubscribe + 30 days
**Legal Basis:** Consent (GDPR Article 6(1)(a))
**Data Included:**
- Email address (encrypted)
- Subscription date
- Subscription source

**Upon Unsubscribe:**
- Status changed to 'unsubscribed'
- Retained for 30 days to prevent accidental resubscription
- Automatically deleted after 30 days

**Automated Deletion:**
```php
// Scheduled task runs daily
NewsletterSubscriber::where('status', 'unsubscribed')
    ->where('unsubscribed_at', '<', now()->subDays(30))
    ->delete();
```

---

### 3. User Accounts (If Implemented)
**Retention Period:** Until account deletion requested
**Legal Basis:** Contract performance (GDPR Article 6(1)(b))
**Inactive Accounts:** Deleted after 3 years of inactivity (with 30-day notice)

---

### 4. Cookie Consent Records
**Retention Period:** 1 year from last interaction
**Legal Basis:** Legal obligation (GDPR Article 6(1)(c))
**Data Included:**
- Consent status
- Timestamp
- Cookie policy version accepted

---

### 5. Session Data
**Retention Period:** Until session expires (2 hours)
**Legal Basis:** Legitimate interest (GDPR Article 6(1)(f))
**Automatic Deletion:** Sessions cleared on logout or expiration

---

### 6. Server Logs
**Retention Period:** 30 days
**Legal Basis:** Legitimate interest for security
**Data Included:**
- Request timestamps
- Error logs
- Security events

**Note:** Logs do NOT include IP addresses or personal identifiers

---

### 7. Payment Transaction Records
**Retention Period:** 7 years (part of order records)
**Legal Basis:** Dutch financial record-keeping requirements
**Data Included:**
- Transaction ID
- Payment method
- Payment status
- Amount

**Note:** No credit card numbers stored (handled by Stripe)

---

## Data Deletion Process

### Automatic Deletion (Scheduled Tasks)
```bash
# Run daily via Laravel scheduler
php artisan schedule:run

# Deletes:
# - Expired sessions
# - Old unsubscribed newsletter emails (30+ days)
# - Old server logs (30+ days)
# - Order records (7+ years old)
```

### Manual Deletion (GDPR Right to Erasure)
**Process:**
1. Customer submits deletion request via API or email
2. Verify customer identity
3. Delete newsletter data immediately
4. Anonymize order data (cannot delete due to tax law)
5. Confirm deletion within 30 days

**Exceptions:**
- Order data anonymized but retained for 7 years (legal requirement)
- Financial records retained for accounting

---

## Review & Updates

This policy is reviewed annually and updated as needed to reflect:
- Changes in legal requirements
- Changes in data processing activities
- Feedback from Data Protection Authority

**Last Reviewed:** February 10, 2026
**Next Review:** February 2027

---

## Contact

Data Protection Officer: privacy@quickdrawpressing.co

For questions about data retention or to request data deletion, contact us at the email above.

---

**Compliance:** This policy complies with GDPR Article 5(1)(e) - Storage Limitation Principle
