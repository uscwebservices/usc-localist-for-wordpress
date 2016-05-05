USC Localist for WordPress
==========================


## 1.2.0

- add span tag classes around template items with data-date-type="datetime-start-end"


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

Bug: Deactivation bug fix.

## 1.0.1 

User documentation updates on _Settings_ menu.

## 1.0.0

Initial Plugin Release

