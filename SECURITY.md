# Security and API Usage

## Overview
Hot Dish uses Laravel 12 with Jetstream and Sanctum to protect routes and to secure its API surface.

## Security controls
- Passwords are hashed by Laravel's authentication system.
- Web routes that manage the backend are protected by Jetstream session authentication and the custom `admin` middleware.
- The API uses Sanctum bearer tokens for authenticated access.
- All state-changing form submissions rely on Laravel CSRF protection.
- Input validation is enforced on admin and customer routes.

## Role restrictions
- Customers can access the customer-facing pages and their own account area.
- Administrators can access backend management pages and authenticated admin APIs.
- Non-admin users attempting to reach admin-only routes receive a `403` response on API endpoints and are redirected on web routes.

## API access
- Public endpoints: `/api/menu` and `/api/menu/{id}`
- Protected customer endpoints: `/api/customer/cart`, `/api/customer/orders`
- Protected admin endpoint: `/api/admin/summary`

### Example bearer token usage
```bash
curl -H "Authorization: Bearer YOUR_SANCTUM_TOKEN" http://localhost:8000/api/customer/cart
```

## Additional recommendations
- Rotate admin credentials after deployment.
- Keep `.env` values secret and do not commit them.
- Use HTTPS in production.
- Regularly back up the database and review API tokens.
