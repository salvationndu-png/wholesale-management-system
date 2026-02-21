# Testing Guide: Admin User Management

## Prerequisites
1. Database migration completed: `php artisan migrate`
2. At least one admin user exists (usertype = 1)
3. Server running: `php artisan serve`

## Test Scenarios

### 1. Access Control
**Test:** Only admins can access the page
- Login as admin (usertype = 1) → Should see "Manage Users" in sidebar
- Login as sales user (usertype = 0) → Should NOT see "Manage Users" in sidebar
- Try accessing `/admin/users` as sales user → Should be redirected/blocked

### 2. View Users Page
**Test:** Admin can view the management page
- Login as admin
- Click "Manage Users" in sidebar
- Should see:
  - 4 statistics cards (Total Users, Active Sales, Inactive, This Month)
  - Search box and filters
  - "Create User" button
  - Users table with all existing users

### 3. Create New User
**Test:** Admin can create a new user
- Click "Create User" button
- Fill in form:
  - Name: "Test Sales User"
  - Email: "testsales@lovehills.com"
  - Password: "password123"
  - Role: Sales User
  - Status: Active
- Click "Create User"
- Should see success toast
- New user should appear in table
- Statistics should update

### 4. Search Users
**Test:** Search functionality works
- Type in search box: "test"
- Table should filter to show only matching users
- Clear search → All users should reappear

### 5. Filter Users
**Test:** Filters work correctly
- Select "Admin" from role filter → Only admins shown
- Select "Sales" from role filter → Only sales users shown
- Select "Active" from status filter → Only active users shown
- Select "Inactive" from status filter → Only inactive users shown
- Combine filters → Should work together

### 6. Toggle User Status
**Test:** Admin can activate/deactivate users
- Find an active user
- Click "Deactivate" button
- Confirm the action
- User status should change to "Inactive"
- Button should now say "Activate"
- Statistics should update
- Click "Activate" → Status should change back

### 7. Reset Password
**Test:** Admin can reset user passwords
- Find any user
- Click "Reset" button
- Confirm the action
- Should see success toast with new password
- A prompt should show the new password
- Copy the password for testing
- Logout and try logging in as that user with new password

### 8. Delete User
**Test:** Admin can delete users
- Find a test user
- Click "Delete" button
- Confirm the action
- User should disappear from table
- Statistics should update
- User should be removed from database

### 9. Self-Protection
**Test:** Admin cannot modify themselves
- Try to deactivate your own account → Should show error
- Try to delete your own account → Should show error

### 10. Statistics Accuracy
**Test:** Statistics are accurate
- Count users manually in table
- Compare with "Total Users" stat
- Count active sales users
- Compare with "Active Sales" stat
- Verify "Inactive" count
- Check "This Month" count (users created this month)

## Expected Behaviors

### Success Messages
- "User created successfully"
- "User status updated"
- "Password reset! New password: [password]"
- "User deleted successfully"

### Error Messages
- "Cannot change your own status"
- "Cannot delete your own account"
- Email validation errors
- Required field errors

### UI Behaviors
- Modal opens/closes smoothly
- Toast notifications appear and disappear
- Table updates without page refresh
- Search has 300ms debounce
- Filters apply immediately
- Confirmations for destructive actions

## Database Verification

After testing, verify in database:
```sql
-- Check users table
SELECT id, name, email, usertype, status, created_by FROM users;

-- Verify status field exists
DESCRIBE users;

-- Check created_by relationships
SELECT u1.name as user, u2.name as created_by 
FROM users u1 
LEFT JOIN users u2 ON u1.created_by = u2.id;
```

## Common Issues & Solutions

### Issue: "Manage Users" link not showing
**Solution:** Ensure logged-in user has `usertype = 1`

### Issue: 404 on `/admin/users`
**Solution:** Check routes are registered, clear route cache: `php artisan route:clear`

### Issue: Statistics showing 0
**Solution:** Check database connection, verify users exist

### Issue: Modal not opening
**Solution:** Check browser console for JS errors, verify admin-users.js is loaded

### Issue: CSRF token error
**Solution:** Refresh page, check meta tag exists in HTML

### Issue: Password reset not working
**Solution:** Check Str::random() is available, verify database update

## Performance Testing

### Load Testing
- Create 50+ users
- Test search performance
- Test filter performance
- Verify pagination (if implemented)

### Browser Testing
- Chrome
- Firefox
- Safari
- Edge
- Mobile browsers

## Security Testing

### Authorization
- Try accessing routes without login → Should redirect
- Try accessing as sales user → Should be blocked
- Try modifying own status via API → Should fail

### Input Validation
- Try creating user with existing email → Should fail
- Try weak password → Should fail (if validation added)
- Try SQL injection in search → Should be safe
- Try XSS in name field → Should be escaped

## Cleanup After Testing

```sql
-- Delete test users
DELETE FROM users WHERE email LIKE '%test%';

-- Or reset to original state
-- Restore database backup
```

## Success Criteria

✅ All routes accessible by admin only
✅ Users can be created, viewed, updated, deleted
✅ Search and filters work correctly
✅ Statistics are accurate
✅ Self-protection works (can't modify own account)
✅ Toast notifications appear
✅ No console errors
✅ Responsive design works on mobile
✅ All confirmations work
✅ Password reset generates valid password
