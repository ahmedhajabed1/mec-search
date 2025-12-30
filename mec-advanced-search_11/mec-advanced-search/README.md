# MEC Advanced Search v3.0.0

Advanced search functionality for Modern Events Calendar with Eventbrite-style search bar, browser geolocation, and comprehensive filters.

## Author
**Ahmed Haj Abed**

## Features

### Search Bar (Eventbrite-style)
- Clean, modern design matching Eventbrite's search interface
- Two-field layout: Search events + Location
- Search icon and location pin icons
- Auto-detect user location via browser geolocation
- Location suggestions with autocomplete
- Event suggestions with preview
- Inline or Popup modal mode

### Geolocation
- Automatic city detection using browser's Geolocation API
- Reverse geocoding via OpenStreetMap Nominatim (free, no API key)
- US state abbreviations (Florida → FL)
- Click location icon to refresh/detect location
- Graceful fallback to manual entry

### Search Results Page with Filters
- Filter dropdowns for:
  - Categories
  - Labels
  - Organizers/Teachers
  - Tags
- AJAX-powered filtering (no page reload)
- Horizontal or vertical filter layout
- Clear all filters button
- Responsive grid layout (1-6 columns)
- Pagination

### Elementor Widgets

#### MEC Event Search Widget
- **Content Tab:**
  - Display Mode (Inline/Popup)
  - Results Page URL
  - Enable Geolocation toggle
  - Show Suggestions toggle
  - Placeholder texts
  - Trigger button settings (popup mode)
  - Popup settings

- **Style Tab (Desktop & Mobile):**
  - Trigger Button: Typography, colors, border, radius, padding
  - Search Bar: Background, border, radius, shadow, padding, min-height, max-width
  - Input Fields: Text color, placeholder color, typography, padding
  - Icons: Search icon color/size, location icon color/size
  - Divider: Color, width, height, margin
  - Search Button: Size, icon size, colors, border radius, hover states
  - Popup: Backdrop color, content background, border radius, padding, title styles

#### MEC Search Results Widget
- **Content Tab:**
  - Search bar visibility and settings
  - Filter toggles (Category, Label, Organizer, Tag)
  - Filter labels customization
  - Layout (Grid/List)
  - Columns (1-6, responsive)
  - Events per page
  - Pagination toggle
  - No results text

- **Style Tab (Desktop & Mobile):**
  - Search Bar: Background, border, radius, margin
  - Filters: Background, padding, margin, gap, dropdown styles, clear button
  - Results Grid: Gap, padding
  - Event Cards: Background, border, radius, shadow, padding, title styles, meta styles
  - Pagination: Alignment, colors
  - No Results: Color, typography, alignment

## Installation

1. Upload the `mec-advanced-search` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **MEC Search** in the admin menu to configure settings
4. Set your Search Results Page URL

## Usage

### With Elementor
1. Edit your header template
2. Add the **MEC Event Search** widget
3. Configure as inline or popup mode
4. Style using the comprehensive controls

5. Create a Search Results page
6. Add the **MEC Search Results** widget
7. Configure filters and layout
8. Style as needed

### With Shortcodes

**Search Bar (inline):**
```
[mec_advanced_search mode="inline" enable_geolocation="true"]
```

**Search Bar (popup):**
```
[mec_advanced_search mode="popup" trigger_text="Find Events" popup_title="Search Events"]
```

**Search Results with Filters:**
```
[mec_search_results 
    columns="3" 
    per_page="12" 
    show_filters="true"
    show_category_filter="true"
    show_label_filter="true"
    show_organizer_filter="true"
    show_tag_filter="true"
]
```

## Changelog

### 3.0.0
- Complete redesign with Eventbrite-style search bar
- Added browser geolocation with auto-detect
- Added reverse geocoding via OpenStreetMap
- Separated search bar and results into two widgets
- Added comprehensive filter system (Category, Label, Organizer, Tag)
- AJAX-powered filtering
- Full responsive design
- Complete Elementor style controls for desktop and mobile
- Popup/modal mode for header search

### 2.0.0
- Pre-indexing system
- Tag-based filtering
- Rich preview dropdowns
- Mobile popup experience

### 1.0.0
- Initial release

## Requirements

- WordPress 5.8+
- PHP 7.4+
- Modern Events Calendar (MEC) plugin
- Elementor (recommended, not required)

## License

GPL v2 or later
