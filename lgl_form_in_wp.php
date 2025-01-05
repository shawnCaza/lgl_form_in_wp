<?php
/*
Plugin Name: LGL Form Embedder
Description: Finds the short code pattern [lgl_iframe_id=xxxxxxx] and replaces it with an lgl iframe embed for a given form ID. Any query string values are passed along to the iframe. Alternatively, the short-code pattern [lgl_js_id=xxxxxxx] can be used to add the js embed code. Query strings are not passed to the js embed code. In the content, the pattern [lgl_field=qs_param] is replaced with the value of the corresponding query string ('qs_param') value.
Version: 1.0
Author: Shawn Caza
github Plugin URI: https://github.com/shawnCaza/lgl_form_in_wp
*/

// Include the settings page + TODO: consider overiding default values or api integration features in the future
// include(plugin_dir_path(__FILE__) . 'settings.php');

function lgl_form_in_wp($content)
{
    // Get the query string
    $query = $_SERVER['QUERY_STRING'];
    $params = get_query_params($query);

    if (valid_query_string($params, $content)) {

        //remove lgl required fields from the content
        $content = preg_replace('/\[lgl_required=([\w\s,]+)\]/', '', $content);

        // Replace [lgl_iframe_id=xxxxxxx] with lgl iframe for the form. Query string values are passed along to the iframe.
        $content = add_lgl_iframe($content, $query);

        // Replace [lgl_js_id] with the lgl javascript. Does not pass query string values (Seemed like the lgl JS that adds the iframe dynamically changes the string to html entities/url encoded).
        $content = add_lgl_js($content);

        // Replace [lgl_field=field_xx] with a matching field value from the query string
        $content = replace_lgl_field($content, $params);
    } else {
        // If the query string is invalid, return an error message
        $content = "<p class='lgl-form-in-wp-error>Oops! Please verify that you have the correct URL.</p>";
    }

    return $content;
}

function get_query_params($query)
{
    $params = array();
    parse_str($query, $params);
    return $params;
}

function valid_query_string($params, $content)
{
    //  parse the [[lgl_required=field_53,field_50]] shortcode pattern in the content
    $pattern = '/\[lgl_required=([\w\s,]+)\]/';
    $required_fields = array();

    preg_match($pattern, $content, $required_fields);

    //if there are no required fields, return true
    if (empty($required_fields)) {
        return true;
    }

    //extract the required fields from the shortcode
    $required_fields = explode(',', $required_fields[1]);

    //check if all required fields are present in the query string
    foreach ($required_fields as $field) {
        if (!array_key_exists($field, $params) || empty($params[$field])) {
            return false;
        }
    }

    return true;
}


function add_lgl_iframe($content, $query)
{
    $pattern = '/\[lgl_iframe_id=([\w\s=]+)\]/';

    $replacement = function ($matches) use ($query) {

        $match_parts = explode(' ', $matches[1]);
        $form_id = $match_parts[0];
        $params = parse_iframe_params($match_parts);

        return "<iframe onload='window.parent.scrollTo(0,0)' id='{$params['id']}' height='{$params['height']}px' allowTransparency='{$params['allowTransparency']}' allow='payment' frameborder='{$params['frameborder']}' style='{$params['style']}' src='https://secure.lglforms.com/form_engine/s/{$form_id}?{$query}'><a href='https://secure.lglforms.com/form_engine/s/{$form_id}?{$query}'>Fill out the Form here!</a></iframe>";
    };

    $content = preg_replace_callback($pattern, $replacement, $content);

    return $content;
}

function parse_iframe_params($match_parts)
{
    $params = iframe_paramaters();

    // Checks for any parameters that may have been definedd within the shortcode
    foreach ($match_parts as $part) {
        if (strpos($part, '=') === false) {
            continue;
        }
        $key_value = explode('=', $part);
        $key = $key_value[0];
        $value = $key_value[1];
        if (array_key_exists($key, $params)) {
            $params[$key] = $value;
        }
    }
    return $params;
}

function iframe_paramaters()
{
    // Defines default parameters for the iframe
    return array(
        "id" => 'lgl_form_iframe',
        "height" => 600,
        "allowTransparency" => 'true',
        "allow" => 'payment',
        "frameborder" => 0,
        "style" => 'width:100%;border:none;'
    );
}


function add_lgl_js($content)
{
    $pattern = '/\[lgl_js_id=([\w\s=]+)\]/';

    $replacement = function ($matches) {

        $match_parts = explode(' ', $matches[1]);
        $form_id = $match_parts[0];

        return "<script type='text/javascript' src='https://secure.lglforms.com/form_engine/s/{$form_id}.js'></script><noscript><a href='https://secure.lglforms.com/form_engine/s/{$form_id}'>Fill out the form here!</a><br/></noscript>";
    };

    $content = preg_replace_callback($pattern, $replacement, $content);

    return $content;
}

function replace_lgl_field($content, $params)
{
    $pattern = '/\[lgl_field=(\w+)\]/';

    $replacement = function ($matches) use ($params) {
        $field = $matches[1];
        return isset($params[$field]) ? $params[$field] : '';
    };

    $content = preg_replace_callback($pattern, $replacement, $content);

    return $content;
}

// Hook the function to the 'the_content' filter
add_filter('the_content', 'lgl_form_in_wp');
