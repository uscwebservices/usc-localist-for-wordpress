# USC Localist for WordPress

## 1.4.8

__Bug:__

- #14: PHP bug: converted input to `int` for `date` object.

## 1.4.7

__Bug:__

- Added `set_UTC` method for date/time conversions as `object` and not `string`.
- Added missing parameter to `datetime-start-end` shortcode option introduced in `1.4.6`.

## 1.4.6

- Changes for Class `USC_Localist_For_WordPress_Dates`
  - allow UTC format for `fix_date` method
  - deprecate `set_PST()` method
- Use `utc` for API call

## 1.4.5

- Class updates for proper `WordPress` naming.
- Imporoved date/time handling for timezone support.
  - Make references to `date_fix` method.
  - Add `set_PST` method to convert all date/time to `America/Los_Angeles`

## 1.4.4

__Bug:__

- Remove `date_default_timezone_set` for WP 5.3

## 1.4.3

- Add composer support.

## 1.4.2

__Bug:__

- Simple HTML DOM upgraded to version 1.8.1 which fixes template error in PHP 7.2

## 1.4.1

- Version Releases changed to `changelog.md`.

__Bug:__

- Pagination bug setting values to `intval` for comparison check.

## 1.4.0

- Localist Calendar Widget:
  - Runs shortcode option through text area.
- `data-field` allow simple array output as comma delimited string.


__Bug:__

- HTTPS link updates.

## 1.3.1

__Bug:__

- Transient name build on subsequent pages and individual event ID.

## 1.3.0

- WordPress Coding Standards applied (Note: some cyclomatic complexity reductions still needed).
- Remove errant option for `datetime-start-end` as option to `data-date-instance` under `dates` section in instructions.
- If custom template post type is not found, an error message is displayed.
- Tabbed navigation for instructions settings page.

__Bug:__

	- Transient cache string reduced in length to prevent timeout value from being dropped and storing never expiring transient values.
	- Template path to external URL support with fallback to defaults if response code is 300 or greater.

## 1.2.2

- __Bug:__

  - Fix for template output error

## 1.2.1

- Added data-separator-date-time-multiple separator for start/end with data-separator-date-time for start

__Bug:__

- 'page' shortcode parameter now sets api to that page but allows override with pagination
- Fix for checking geo variables enclosures

## 1.2.0

- add span tag classes around template items with separators, date, time and datetime-start-end options
- add date range separator template option with 'data-separator-range': default ' - '

__Bug:__

- Fix for null end time output

## 1.1.8

- add <p class="no-events-message"> tag wrapper to no events messaging.

## 1.1.7

- return numeric pagination results if only greater than 1 page of results
- remove rich text editor for custom template type 'event-templates'
- add no events messaging option
- add date and time separtor options to templates for datetime-start-end output
- links that are not 'map' or 'details' change to a <span> and remove the href attribute if the link value is empty
- map link fallback order:
	1. USC Maps (3 letter code)
	2. Google Maps: Street, City, State
	3. Google Maps: Latitude, Longitude
	4. USC Maps (UPC with query)
	5. Google Maps: Address

## 1.1.6

- set timezone to Los Angeles for strtotime functions

## 1.1.5

- allow cache to be '0' and not set transient

## 1.1.4

- Note about setting cache to '0'

## 1.1.3

- Spelling fix for 'calendar' in documentation.

## 1.1.2

- Pagination fix for lists with 2 results.

## 1.1.1

- Documentation updates for date/time.
- Single simple date for events list template.

## 1.1.0

- Return event data as ob_get_clean instead of echo
- Events list default as <article> structure

## 1.0.2

__Bug:__

- Deactivation bug fix.

## 1.0.1

- User documentation updates on _Settings_ menu.

## 1.0.0

- Initial Plugin Release
