<?php
/**
 * Functions related to default widgets
 */

add_filter( 'get_calendar', 'landkit_widget_calendar', 10 );
/**
 * Widget calendar
 *
 * @param string $output output of calender widget.
 */
function landkit_widget_calendar( $output ) {
	return str_replace( 'class="wp-calendar-table"', 'class="table table-bordered table-sm text-center font-size-sm wp-calendar-table"', $output );
}
