# MEC Organizer Manager v1.2.1

Complete organizer management system for Modern Events Calendar with multi-step host registration, user account management, and Elementor integration.

## Features

### Multi-Step Host Registration
- **Step 1**: Account info (name, email, phone, location, password, reCAPTCHA)
- **Step 2**: Phone verification (placeholder for future API integration)
- **Step 3**: Business info (optional - name, address, EIN)
- **Step 4**: Profile setup (website, social links, bio, fun facts)
- **Step 5**: Thank you confirmation

### Admin Dashboard
- **Settings Tab**: Configure auto-create, user roles, registration options, reCAPTCHA
- **User Linking Tab**: Manually link/unlink organizers to WordPress users
- **Registrations Tab**: Review, approve, or reject pending registrations
- **Help Tab**: Shortcodes and widget documentation

### User Account Management
- Custom "Event Organizer" role with limited permissions
- Organizers can only see/edit their own events
- Auto-assign organizer when creating events
- Profile sync between organizer and user account

### Google reCAPTCHA v2
- Enable/disable in settings
- Site key and secret key configuration
- Protects registration form from spam

### Elementor Widgets (8)
1. **Host Registration Form** ⭐ NEW - Multi-step registration with full styling
2. **Organizer Profile** - Photo, name, location, share button
3. **Organizer Name** - Name with optional icon
4. **Organizer Bio** - Biography section
5. **Organizer Fun Fact** - Decorative fun fact box
6. **Organizer Offerings** - List of services
7. **Organizer Social** - Instagram, X, Facebook, TikTok only
8. **Organizer Events** - Event grid for organizer

### Host Registration Form Widget
Full customization in Elementor:
- **Logo**: Use site logo or upload custom, adjustable size
- **Steps**: Toggle phone verification, business info, and profile steps
- **Content**: Customize all labels, placeholders, hints, button text
- **Container**: Background color, accent bar color, padding, border radius, shadow, max width
- **Title**: Color, typography, decorative lines on/off
- **Labels**: Color, typography, hint text color
- **Inputs**: Background, text color, border color, focus color, border radius, padding
- **Buttons**: Primary/secondary colors, hover states, border radius, typography

### Dynamic Tags (4)
- Organizer Name
- Organizer Bio
- Organizer Image
- Organizer Field (any custom field)

### Shortcodes

| Shortcode | Description |
|-----------|-------------|
| `[mecom_host_registration]` | Multi-step registration form (recommended) |
| `[mecom_login_form]` | Simple login form |
| `[mecom_register_form]` | Basic registration form |
| `[mecom_organizer_dashboard]` | Dashboard for logged-in hosts |

### Welcome Email
- Branded HTML email template
- Includes site logo
- Shows login credentials
- Direct link to login page

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu
3. Go to **MEC → Organizer Manager** to configure settings
4. **Using Elementor**: Edit a page, search for "Host Registration Form" widget
5. **Using Shortcode**: Add `[mecom_host_registration]` to any page

## Configuration

### Enable reCAPTCHA
1. Go to [Google reCAPTCHA](https://www.google.com/recaptcha/admin)
2. Create a new site with reCAPTCHA v2 "I'm not a robot"
3. Copy Site Key and Secret Key
4. Paste into Settings → Google reCAPTCHA section
5. Set "Enable reCAPTCHA" to Yes

### Approve Registrations
1. When hosts register, they appear in the "Registrations" tab
2. Click "Details" to view full submission
3. Click "Approve" to create user + organizer + send welcome email
4. Or click "Reject" to decline

### Manual User Linking
1. Go to "User Linking" tab
2. Find the organizer you want to link
3. Select a user from dropdown and click "Link"
4. Or click "Create User" to auto-generate a user

## Changelog

### v1.2.1
- Added **Host Registration Form** Elementor widget with full customization
- Widget includes style controls for container, title, labels, inputs, and buttons
- Toggle steps on/off directly in Elementor
- Customize all text labels and placeholders
- Live preview in Elementor editor

### v1.2.0
- Added multi-step host registration form
- Added Google reCAPTCHA v2 integration
- Added Registrations tab for pending approvals
- Added branded welcome email template
- Added phone number field with country code
- Added business info fields (optional)
- Added profile setup fields (website, social, bio, fun facts)
- Added admin notification for new registrations
- Improved admin UI with 4 tabs

### v1.1.0
- Added tabbed admin interface
- Added User Linking tab with visual table
- Added manual link/unlink/create user actions
- Limited social links to 4 platforms only
- Auto-create user default changed to OFF

### v1.0.0
- Initial release
- User account creation and linking
- Elementor widgets and dynamic tags
- Profile pages at /teacher/[slug]/
- Event filtering for organizers

## Requirements

- WordPress 5.8+
- PHP 7.4+
- Modern Events Calendar (MEC)
- Elementor (recommended)

## Support

For issues or feature requests, contact the developer.
