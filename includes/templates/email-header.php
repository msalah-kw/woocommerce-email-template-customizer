<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo esc_html( get_bloginfo( 'name', 'display' ) ); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--[if !mso]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <![endif]-->

	<?php do_action( 'viwec_email_header' ); ?>


    <!--[if (mso 16)]>
    <style type="text/css">
        span {
            vertical-align: middle;
        }
    </style>
    <![endif]-->

    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->


    <!--[if mso | IE]>
    <style type="text/css">
        table {
            font-family: Helvetica, Arial, sans-serif;
        }
    </style>
    <![endif]-->

    <style type="text/css">
        <?php echo ( apply_filters('viwec_after_render_style','') );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        @media screen and (max-width: <?php echo esc_attr($responsive);?>px) {
        <?php  echo ( apply_filters('viwec_mobile_render_style','') );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            img {
                padding-bottom: 10px;
            }
            .viwec-responsive{
                display: inline-block !important;
            }
            td.viwec-responsive.viwec-product-responsive *,
            .viwec-responsive, .viwec-responsive table, .viwec-button-responsive {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 100% !important;
            }

            .viwec-no-full-width-on-mobile table,
            table.viwec-no-full-width-on-mobile {
                min-width: 0 !important;
                width: auto !important;
            }

            td.viwec-responsive tbody,
            td.viwec-responsive tbody  tr,
            td.viwec-responsive tbody  tr td {
                display: inline-block !important;
                width: 100% !important;
            }
            td.viwec-responsive .viwec-no-full-width-on-mobile table td,
            td.viwec-responsive .viwec-no-full-width-on-mobile table * td,
            td.viwec-responsive table.viwec-no-full-width-on-mobile td,
            td.viwec-responsive table.viwec-no-full-width-on-mobile * td{
                width: auto !important;
            }

            td.viwec-responsive .viwec-no-full-width-on-mobile table,
            td.viwec-responsive table.viwec-no-full-width-on-mobile,
            td.viwec-responsive table.html_wc_hook .html_wc_hook_default table,
            td.viwec-responsive table.html_wc_hook .html_wc_hook_default * table {
                display: table !important;
            }
            td.viwec-responsive .viwec-no-full-width-on-mobile table tbody,
            td.viwec-responsive .viwec-no-full-width-on-mobile table * tbody,
            td.viwec-responsive table.viwec-no-full-width-on-mobile tbody,
            td.viwec-responsive table.viwec-no-full-width-on-mobile * tbody,
            td.viwec-responsive table.html_wc_hook .html_wc_hook_default tbody,
            td.viwec-responsive table.html_wc_hook .html_wc_hook_default * tbody {
                display: table-row-group !important;
            }
            td.viwec-responsive .viwec-no-full-width-on-mobile table tr,
            td.viwec-responsive .viwec-no-full-width-on-mobile table * tr,
            td.viwec-responsive table.viwec-no-full-width-on-mobile tr,
            td.viwec-responsive table.viwec-no-full-width-on-mobile * tr,
            td.viwec-responsive table.html_wc_hook .html_wc_hook_default tr,
            td.viwec-responsive table.html_wc_hook .html_wc_hook_default * tr {
                display: table-row !important;
            }

            td.viwec-responsive .viwec-no-full-width-on-mobile table td,
            td.viwec-responsive .viwec-no-full-width-on-mobile table * td,
            td.viwec-responsive table.viwec-no-full-width-on-mobile td,
            td.viwec-responsive table.viwec-no-full-width-on-mobile * td,
            td.viwec-responsive table.html_wc_hook .html_wc_hook_default td,
            td.viwec-responsive table.html_wc_hook .html_wc_hook_default * td {
                display: table-cell !important;
            }

            .viwec-responsive-padding {
                padding: 0 !important;
            }

            .viwec-mobile-hidden {
                display: none !important;
            }

            .viwec-responsive-center, .viwec-responsive-center p, .viwec-center-on-mobile p {
                text-align: center !important;
            }

            .viwec-mobile-50,
            table.viwec-responsive tbody  tr td.viwec-mobile-50 {
                width: 50% !important;
                min-width: 50% !important;
                max-width: 50% !important;
            }
            .viwec-mobile-50 .woocommerce-Price-amount {
                margin-right: 1px;
            }

            #viwec-responsive-min-width {
                min-width: 100% !important;
            }
        }
        #viwec-responsive-wrap {
            box-sizing:border-box;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        #viwec-responsive-wrap *{
            color:inherit;
            box-sizing: border-box;
        }
        #viwec-responsive-min-width {
            min-width: <?php echo esc_attr($width);?>px;
        }
        #viwec-responsive-wrap #body_content {
            width: 100% !important;
        }
        #viwec-responsive-wrap #outlook a {
            padding: 0;
        }

        #viwec-responsive-wrap a {
            text-decoration: none;
            word-break: break-word;
        }

        #viwec-responsive-wrap td {
            overflow: hidden;
        }

        td.viwec-row {
            background-repeat: no-repeat;
            background-size: cover;
            background-position: top;
        }
        td.viwec-responsive *{
            max-width: 100% ;
        }

        div.viwec-responsive {
            display: inline-block;
        }

        #viwec-responsive-wrap img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
            vertical-align: middle;
            background-color: transparent;
            max-width: 100%;
        }
        #viwec-responsive-wrap p {
            display: block;
            margin: 0;
            line-height: inherit;
            /*font-size: inherit;*/
        }

        #viwec-responsive-wrap small {
            display: block;
            font-size: 13px;
        }

        #viwec-transferred-content small {
            display: inline;
        }

        #viwec-transferred-content td {
            vertical-align: top;
        }

    </style>
</head>

<body vlink="#FFFFFF" <?php echo $direction == 'rtl' ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

