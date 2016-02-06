USC Localist for WordPress
==========================

<!-- MarkdownTOC -->

- Plugin Usage
- API Options

<!-- /MarkdownTOC -->

## Plugin Usage

This is a WordPress Plugin that uses the shortcode `[localist-calender]` to get events from the [usc-calendar].


## API Options

Please reference the [localist-api-docs] for a full reference of current supported options.

### Custom API options

### Notes

#### Departments

Once a department, or group of departments, is chosen, events from the department(s) will be selected and then the other search parameters applied.  This is pertinent to `keyword` and `type` searches with a setting of `match=any`.

**Example**

We have a 'History' department (group_id=1) with 20 events and 10 tagged with the keyword 'History'.  In all of USC events, we have 30 events tagged with the keyword 'History'.

Searching for the following elements separately:

	[localist-calendar group_id='1'] produces 20 results

	[localist-calendar keyword='History'] produces 30 results

If we combined the two searches above with match 'any', we might expect to get 50 results:

	[localist-calendar group_id='1' keyword='History' match='any']

However, since the events for the department 'History' are gathered first and then the filters applied, we would only get 10 events in return and not 50.

Please see the [localist-api-docs] for the latest information.



[localist-api-docs]: http://www.localist.com/doc/api 'Localist API'
[usc-calendar]: https://calendar.usc.edu 'USC Calendar'