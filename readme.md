USC Localist for WordPress
==========================

<!-- MarkdownTOC -->

- Plugin Usage
- API Options
- Custom API options
- Customizer
- Notes

<!-- /MarkdownTOC -->

## Plugin Usage

This is a WordPress Plugin that uses the shortcode `[localist-calender]` to get events from the [usc-calendar].


## API Options

Please reference the [localist-api-docs] for a full reference of current supported options.

## Custom API options

In addion to the attributes from the [localist-api-docs], the following custom attributes can be used:

<table>
	<thead>
		<td><strong>Attribute</strong></td>
		<td><strong>Accepted Values</strong></td>
	</thead>
	<tbody>
		<tr>
			<td><code>get</code></td>
			<td>The <code>type</code> of API data to get: <code>events</code>, <code>event</code></td>
		</tr>
		<tr>
			<td><code>cache</code></td>
			<td>The amount of time to store the API results in the site.  This will help performance of the page. <br><br>Default: <code>1 hour</code></td>
		</tr>
		<tr>
			<td><code>template</code></td>
			<td>Enter the <code>slug</code> of the Event Templates post to use for the structure of the returned API data.</td>
		</tr>
		<tr>
			<td><code>href</code></td>
			<td>Enter the link to the events detail page.  This will output the <code>href</code> base and attache the event id to the end.  You can specify a global setting for this using the <a href="#customize">Customize</a> options.</td>
		</tr>
	</tbody>
</table>

## Customizer

This plugin uses the WordPress Customizer to set global calendar settings for the following items.

<table>
	<thead>
		<td><strong>Option</strong></td>
		<td><strong>Output</strong></td>
	</thead>
	<tr>
		<td><code>no</code></td>
		<td>Monday, Tuesday, Wednesday</td>
	</tr>
	<tr>
		<td><code>yes</code></td>
		<td>Monday - Wednesday</td>
	</tr>
</table>


### Event Details Page

Choose a page where the events link to an event details page.

On the selected page, you must use the shortcode: 

	[localist-calendar get="event"]

Or, to display events and the event detail with one shortcode:

	[localist-calendar get="events" is_events_page="true"]

If you leave the dropdown blank, the event links will go to the event detail page on the [usc-calendar].


## Notes


### Departments

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

[id]


[customize]: [Customize][] 'Customize'
[localist-api-docs]: http://www.localist.com/doc/api 'Localist API'
[usc-calendar]: https://calendar.usc.edu "USC Calendar"