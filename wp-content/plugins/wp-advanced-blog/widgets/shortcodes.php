<?php
//[foobar]
function foobar_func( $atts ){
    $a = shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
        'wp-ab-postid' => '',
    ), $atts );
    return "foo and {$a['wp-ab-postid']}";
}
