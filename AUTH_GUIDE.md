# Authentication & Authorization Guide

This document outlines how authentication, roles, and login mechanisms are structured in the Travel Application, incorporating **Sanctum (API)**, **Socialite (Google/Facebook)**, and **Spatie (Role-Based Access Control)**.

## 1. System Roles
Using `spatie/laravel-permission`, the application handles the following roles:
- `Admin`: Full access to the dashboard and system settings.
- `Service Provider`: Can manage their own agencies/hotels/packages.
- `Customer`: Can browse and book services.
- `Technician`: Has access to specific jobs via the Technician App.

*Roles should be assigned at the time of user registration or explicitly by an Admin.*

## 2. API Authentication (Laravel Sanctum)

For mobile and frontend frameworks (React/Vue/Next.js) communicating with this Laravel backend, token-based authentication is used.

### Login Flow (API)
**Endpoint:** `POST /api/login`

**Payload:**
```json
{
    "email": "user@example.com",
    "password": "password123",
    "device_name": "iphone-13" 
}
```

**Response:** Returns a Bearer Token along with a `UserResource` containing eager-loaded roles.
```json
{
    "token": "1|abc123token...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "role": "Customer"
    }
}
```
*Frontend must attach this token to `Authorization: Bearer {token}` headers for protected routes.*

## 3. Social Authentication (Laravel Socialite)

Integration provides easy onboarding for Customers.

### Social Login Flow
1. **Redirect to Provider:** `GET /auth/{provider}/redirect` (e.g., `provider = google`)
2. **Provider Callback:** `GET /auth/{provider}/callback`
    - Application automatically finds or creates the user in `SocialiteController`.
    - Automatically assigns the `Customer` role.
    - Generates a Sanctum Token (if API logic is requested) or logs the user in via Session.

## 4. Middleware & Route Protection

Routes are protected by roles/permissions.
```php
// API routes protected by Sanctum
Route::middleware('auth:sanctum')->group(function () {
    
    // Role-specific routes
    Route::middleware(['role:Admin'])->group(function () {
        // Admin only endpoints
    });

    Route::middleware(['role:Technician'])->group(function () {
        // Technician endpoints
    });
});
```

## 5. Security Practices Implemented
- **Form Requests:** `StoreUserRequest` and `LoginRequest` validate all incoming parameters to prevent tampering.
- **Rate Limiting:** Protects `/login` endpoints to prevent brute-force attacks (`RateLimiter`).
- **Resource Transformation:** The `UserResource` strips sensitive parameters (like passwords) before sending the model over the API.
