# lgl_form_in_wp

Embeds a [Little Green Light](https://www.littlegreenlight.com/) Form in a WordPress page.

## Usage

1. Install the plugin
2. Add shortcode to a page or post

## Iframe shortcode

Add the shortcode `[lgl_iframe_id=xxxxx]` to a page or post where you want the form to appear. Replace `xxxxx` with the form ID from Little Green Light.

- Any query strings sent to the WordPress page will be passed to the LGL Iframe. This can be used to pre-fill fields in the form. See this article on [pre-filling forms](https://help.littlegreenlight.com/article/224-advanced-pass-values-into-a-form-via-email) for more information. If certain fields are required to be passed for the form to function correctly, use the `lgl_required` to verify their presence.

In addition to the form ID, you can optionally specify iframe parameters. Parameters are separated by spaces and are in the form `parameter=value`.

Example: `[lgl_iframe_id=xxxxx height=800 allowTransparency=false]`

Available parameters are:

- `id` - The id of the iframe element. Default is `lgl_form_iframe`.
- `height` - The height of the iframe. Default is 600.
- `allowTransparency` - Allow transparency in the iframe. Default is `true`.
- `allow` - Allow payment in the iframe. Default is `payment`.
- `frameborder` - The border of the iframe. Default is 0.
- `style` - The style of the iframe. Default is `width:100%;border:none;`.

## Required fields shortcode

Add the shortcode `[lgl_required=xxxxx]` to a page or post where you want to verify the presence of certain query string fields. Replace `xxxxx` with the query string field names. Separate multiple fields with commas. If any of the fields are missing, only an error message will be displayed to the user rather than post content.

## Javascript shortcode

Add the shortcode `[lgl_js_id=xxxxx]` to a page or post where you want the form to appear. Replace `xxxxx` with the form ID from Little Green Light. Note: Query strings are not passed to the form with this shortcode.

## Query string shortcode

In addition to passing query strings to the iframe, for use in the LGL form, you can also use query strings in the WP content itself.

The shortcode for using query strings in the WP content is `[lgl_field=xxxxx]`. Replace `xxxxx` with the query string field name. When the page is rendered, this shortcode will be replaced with the value of the query string field.

Example url: `https://example.com/page/?field_50=John`

Example shortcode: `[lgl_field=field_50]`

Rendered output: `John`
