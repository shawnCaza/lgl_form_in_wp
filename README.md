# lgl_form_in_wp

Embeds a [Little Green Light](https://www.littlegreenlight.com/) Form in a WordPress page.

## Usage

1. Install the plugin
2. Add shortcode to a page or post

### Iframe shortcode

Add the shortcode `[lgl_iframe_id=xxxxx]` to a page or post where you want the form to appear. Replace `xxxxx` with the form ID from Little Green Light.

- Any query strings sent to the WordPress page will be passed to the LGL Iframe. This can be used to pre-fill fields in the form. See this article on [pre-filling forms](https://help.littlegreenlight.com/article/224-advanced-pass-values-into-a-form-via-email) for more information.

In addition to the form ID, you can optionally specify iframe parameters. Parameters are separated by spaces and are in the form `parameter=value`.

Example: `[lgl_iframe_id=xxxxx height=800 allowTransparency=false]`

Available parameters are:

- `id` - The id of the iframe element. Default is `lgl_form_iframe`.
- `height` - The height of the iframe. Default is 600.
- `allowTransparency` - Allow transparency in the iframe. Default is `true`.
- `allow` - Allow payment in the iframe. Default is `payment`.
- `frameborder` - The border of the iframe. Default is 0.
- `style` - The style of the iframe. Default is `width:100%;border:none;`.

### Javascript shortcode

Add the shortcode `[lgl_js_id=xxxxx]` to a page or post where you want the form to appear. Replace `xxxxx` with the form ID from Little Green Light. Note: Query strings are not passed to the form with this shortcode.

### Using query string fields within your WP page

In addition to passing query strings to the iframe, for use in the LGL form, you can also use query strings in the WP content itself.

The shortcode for using query strings in the WP content is `[lgl_field=xxxxx]`. Replace `xxxxx` with the query string field name. When the page is rendered, this shortcode will be replaced with the value of the query string field.
