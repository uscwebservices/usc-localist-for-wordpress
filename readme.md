USC Localist for WordPress
==========================

<!-- MarkdownTOC -->

- Plugin Usage
- API Options
- Custom shortcode API options
- Customizer
- Templates
- Notes

<!-- /MarkdownTOC -->

## Plugin Usage

This is a WordPress Plugin that uses the shortcode `[localist-calender]` to get events from the [usc-calendar].


## API Options

Please reference the [localist-api-docs] for a full reference of current supported options.

## Custom shortcode API options

In addion to the attributes from the [localist-api-docs], the following custom attributes can be used.

Parameter | Type | Options | Default | Description
----------|------|---------|---------|------------
`get` | string | `events` `event` | `events` | The `type` of API to call.
`cache` | integer | |  1 hour | The amount of time (in seconds) to store the API results in the site. This will help performance of the page.
`date_range` | boolean | `true` `false` | `false` | Displays `first_date` - `last_date` on events if dates differ else the next single instance will display. (see note below)
`details_page` | string | | | Enter the link to the events detail page. Global setting available in the [Customizer](#customizer) options. Please see `is_events_page`. |  |
`is_events_page` | boolean | `true` `false`  | `false` | Uses the same page for details page.
`paginate` | string | `next` `numeric` |  | Show the pagination on multiple events api.
`paginate_offset` | numeric | | `3` | The amount of numbers to show before and after the current page.
`paginate_numeric_separator` | string |  | ` ... ` | The separator used betwen first, last and the offset page start/end. 
| | | | Example: 1 ... 21 **22** 23 ... 84
`template_multiple` | string | post-slug |  | Use the slug of the post type _Event Templates_ to use for the structure of the returned API data for a list of events. Defaults to list view.
`template_single` | string | post-slug |  | Use the slug of the post type _Event Templates_ to use for the structure of the returned API data for a list of events. Defaults to list view.


+ ***Note:*** the shortcode attribute `date-range` will only show on multiple events list.  Single event details will list all instances of dates after current date.

## Customizer

This plugin uses the WordPress Customizer to set global calendar settings for the following items.


Option | Output
-------|-------
`no` | 1/1/2016, 2/1/2016, 3/1/2016
`yes` | 1/1/2016 - 3/1/2016


### Event Details Page

Choose a page where the events link to an event details page.

On the selected page, you must use the shortcode: 

	[localist-calendar get="event"]

Or, to display events and the event detail with one shortcode:

	[localist-calendar get="events" is_events_page="true"]

If you leave the dropdown blank, the event links will go to the event detail page on the [usc-calendar].


## Templates

### Data Fields

To use the data from the API, you can add the data attribute `data-field` to any HTML element and use the mapped dot syntax path to the data.  The `data-field` will start at the individual `event` level.


Parameter | Type | Options | Default | Description
----------|------|---------|---------|------------
`data-field` | string | | | The dot syntax mapping to the API for the desired value.

Example:

	event: {
		title: "Reshaping Tradition: Contemporary Ceramics from East Asia",
		geo: {
			street: "46 North Los Robles Avenue",
			city: "Pasadena",
			state: "CA",
			country: "US",
			zip: "91101"
		}
	}

Using the sample data:

	<address data-field="geo.city"></address>

Output:

	<address data-field="geo.city">Pasadena</address>


### Links

To set a link from the API data, you can add the data attribute `data-link` to an `a` tag and use the mapped dot syntax path to the data.  You can use this in conjunction with the `data-field`.

Parameter | Type | Options | Default | Description
----------|------|---------|---------|------------
`data-link` | string | string | | The dot syntax mapping to the url.
| | `map` | | Automatically sets link to `location_name`.
| | `detail` | | Automatically sets link to event detail page.

***Note:*** The `data-link="map"` function will set the link to the three letter code at the end of the location name. Leavey Library (LVL) will link to the UPC map for <em>LVL</em>.  Any three letter codes for HSC will link to the HSC map.  IF there is no three letter code, the link will go to the UPC maps with a query parameter of the `location_name`.


### Dates

To set a date ore time setting from the API data, you can add the data attribute `data-date-type` to any HTML tag.  This will automatically map the data to the `first_date`, `last_date`, or event instance(s) depending on the options chosen.

Parameter | Type | Options | Default | Description
----------|------|---------|---------|------------
`data-date-type` | string | `date` | `date` | Returns the date of the selection. Use with `data-format-date`.
 | | `time` | | Returns the time of the selection. Use with `data-format-time`.
 | | `datetime` | | Returns the date and time of the selection. Use with `data-format-date` and `data-format-time`.
 `data-date-instance` | string | `start` | `start` | Use the start of the event instance for the date/time output.
 | | `end` | | Use the end of the event instance for the date/time output.
 | | `datetime-start-end` | | Uses the start and end of the event instance for the date/time output.
 `data-format-time` | string | PHP Data | `g:i a` | Set the time output format using [PHP Date][php-date].
 `data-format-date` | string | PHP Data | `m/d/Y` | Set the date output format using [PHP Date][php-date].
 `data-separator` | string | | | Used between date/time instances for single events.



### Photos

The data node `photo_url` will replace the `src` with the url of the photo.

To set a photo url from the API data, you can add the data attribute `data-photo` to an `img` tag and use the mapped dot syntax path to the data.  Use this in conjunction with the `data-format` (below) to change the image size returned.

Parameter | Type | Options | Default | Description
----------|------|---------|---------|------------
`data-photo` | string | `photo_url` | | The dot syntax mapping to the photo attribute.
`data-format` | string | See list in [Photo Format](#photo-format) | `huge` | Choose from the available photo sizes to set the `src`.


#### Photo Format

Using `data-format` with `data-photo`, you can set the size of the images to be returned from the following list:

Option | Returned Size (px)
-------|-------------------
tiny | 20x20
small | 50x50
medium | 80x80
big | 200x150
big_300 | 300×225


## Notes


### Events

#### Event Departments

Once a department, or group of departments, is chosen, events from the department(s) will be selected and then the other search parameters applied.  This is pertinent to `keyword` and `type` searches with a setting of `match=any`.

**Example**

We have a _History_ department `group_id=1` with 20 events and 10 tagged with the keyword _History_.  In all of USC events, we have 30 events tagged with the keyword _History_.

Searching for the following elements separately:

	[localist-calendar get='events' group_id='1'] produces 20 results

	[localist-calendar get='events' keyword='History'] produces 30 results

If we combined the two searches above with match `any`, we might expect to get 50 results:

	[localist-calendar get='events' group_id='1' keyword='History' match='any']

However, since the events for the department _History_ are gathered first and then the filters applied, we would only get 10 events in return and not 50.


[php-date]: http://php.net/manual/en/function.date.php 'PHP Date'
[localist-api-docs]: http://www.localist.com/doc/api 'Localist API'
[usc-calendar]: https://calendar.usc.edu "USC Calendar"