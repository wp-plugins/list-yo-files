<?php
function FormatFileSize( $size )
{
	if ( strlen($size) <= 9 && strlen($size) >= 7 )
	{
		$size = number_format( $size / 1048576, 1 );
		return "$size MB";
	}
	elseif ( strlen( $size ) >= 10 )
	{
		$size = number_format( $size / 1073741824, 1 );
		return "$size GB";
	}
	else
	{
		$size = number_format( $size / 1024, 1 );
		return "$size KB";
	}
}

//
// Sort functions for List Yo' Files associative arrays
//
function ReverseFileSizeSort( $x, $y )
{
	return ( $x['size'] > $y['size'] );
}

function FileSizeSort( $x, $y )
{
	return ( $y['size'] > $x['size'] );
}

function ReverseDateSort( $x, $y )
{
	return ( $x['date'] > $y['date'] );
}

function DateSort( $x, $y )
{
	return ( $y['date'] > $x['date'] );
}

?>