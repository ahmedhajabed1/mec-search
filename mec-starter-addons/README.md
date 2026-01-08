# MEC Starter Addons v5.3.0

Advanced Elementor widgets and features for Modern Events Calendar including search, organizer profiles, and more.

## Author
**Ahmed Haj Abed**

## What's New in v4.0.0

### Redesigned Event Cards
- Price badge (top-left pill) from MEC cost field
- Date/Time bar at image bottom: "Fri, Nov 28 | 6:00 PM EST"
- Tag badge on the right side of date bar
- Title, Location, "Hosted by [Organizer]" below image
- Cream background with rounded corners

### Category Tabs
- Horizontal pill-style tabs auto-generated from MEC categories
- AJAX filtering without page reload
- Active state styling with customizable colors

### Updated Filters
- Streamlined single horizontal row
- Organizer filter
- Tag filter
- Sort filter (Date Asc/Desc, Price High/Low, Title A-Z/Z-A)
- Removed: Category filter, Label filter, Clear button

### NEW: Featured Events Widget
- Same card design as search results
- Mark events as "Featured" in MEC event editor
- Star toggle in the sidebar meta box
- Full Elementor style controls

### NEW: Teachers/Organizers Widget
- Profile image with location bar overlay
- Heart icon (toggleable, functional later)
- Name and tagline display
- Purple border styling
- Links to filtered events page

### New Organizer Fields
- City
- State (abbreviation)
- Tagline/Description

## Elementor Widgets

### 1. MEC Event Search
Header search bar widget (unchanged from v3.0)
- Inline or popup mode
- Geolocation auto-detect
- Full style controls

### 2. MEC Search Results
Results page with all new features:
- Optional search bar
- Category tabs (auto-generated)
- Filters in single row
- Redesigned event cards
- Full column and style controls

### 3. MEC Featured Events
Display featured events only:
- Same card design
- Configurable columns
- Full style controls

### 4. MEC Teachers/Organizers
Organizer grid with:
- Profile images
- Location bar with heart icon
- Name and tagline
- Purple border styling

## Installation

1. Upload the `mec-advanced-search` folder to `/wp-content/plugins/`
2. Activate the plugin
3. Go to **MEC Search** in admin menu to configure

## Marking Events as Featured

1. Edit any MEC event
2. Look for "Featured Event" meta box in sidebar
3. Check the box to mark as featured
4. Save the event

## Adding Organizer Info

1. Go to MEC Settings → Organizers
2. Edit an organizer
3. Fill in: City, State, Tagline
4. Add a thumbnail image
5. Save

## Shortcodes

### Search Bar
```
[mec_advanced_search mode="inline" enable_geolocation="true"]
```

### Search Results
```
[mec_search_results columns="4" per_page="12" show_category_tabs="true" show_filters="true"]
```

### Featured Events
```
[mec_featured_events columns="4" per_page="8"]
```

### Organizers Grid
```
[mec_organizers_grid columns="4" per_page="8" show_heart="true"]
```

## Requirements

- WordPress 5.8+
- PHP 7.4+
- Modern Events Calendar (MEC) plugin
- Elementor (recommended)

## Changelog

### 4.0.0
- Complete event card redesign with price badge, date bar, tag badge
- Added category tabs with AJAX filtering
- Streamlined filters to single row
- Added sorting options
- NEW: Featured Events widget with star toggle
- NEW: Teachers/Organizers widget
- Added organizer custom fields (City, State, Tagline)
- Full Elementor style controls for all elements

### 3.0.0
- Eventbrite-style search bar
- Geolocation with reverse geocoding
- Popup modal mode

## License

GPL v2 or later
