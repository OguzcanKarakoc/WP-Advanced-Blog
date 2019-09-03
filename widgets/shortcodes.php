<?php
//[foobar]
function foobar_func($atts)
{
    $atts = shortcode_atts(array(
        'foo' => 'something',
        'bar' => 'something else',
        'id' => 2,
    ), $atts);
    return get_the_title($atts['id']);
}

function excerpt($atts)
{
    $atts = shortcode_atts(array(
        'foo' => 'something',
        'bar' => 'something else',
        'id' => 2,

    ), $atts);
    return get_the_excerpt($atts['id']);
}

function permalink($atts)
{
    $atts = shortcode_atts(array(
        'foo' => 'something',
        'bar' => 'something else',
        'id' => 2,
    ), $atts);
    return get_permalink($atts['id']);
}
