<?php
if (!is_admin()) {
    print 'Direct access not allowed.';
    exit;
}
?>
<style>
    .notice {
        display: none;
    }

    .wcmp-landing-page table:not(.wcmp-player-settings) td {
        width: 50%;
    }

    .wcmp-landing-page a {
        box-shadow: none !important;
        outline: none !important;
    }

    .wcmp-main-button,
    .wcmp-secondary-button {
        text-transform: capitalize;
        width: 260px;
        display: inline-block;
        text-decoration: none !important;
        color: white !important;
        height: 46px;
        line-height: 46px;
        font-size: 18px;
        border-radius: 5px;
        background: #2271b1;
        box-shadow: none !important;
        outline: none !important;
    }

    .wcmp-secondary-button {
        background: white;
        border: 1px solid #b2b5ba;
        color: #454647 !important
    }

    .wcmp-main-button:hover {
        background: #135e96;
    }

    .wcmp-secondary-button:hover {
        background: #f0f2f5;
    }

    .wcmp-why-upgrade {
        color: white;
    }

    /* Layout y contenedores */
    .wcmp-container-centered {
        margin: 0 auto;
        max-width: 720px;
        padding-top: 100px;
    }

    .wcmp-inner-no-padding {
        padding: 0;
    }

    .wcmp-section-padding {
        padding: 20px;
    }

    .wcmp-header-padding {
        padding: 70px 20px 0 20px;
    }

    /* Alineación y texto */
    .wcmp-text-center {
        text-align: center;
    }

    .wcmp-text-right {
        text-align: right;
    }

    .wcmp-title-center {
        text-align: center;
    }

    /* Productos y estadísticas */
    .wcmp-products-grid {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 10px;
    }

    .wcmp-product-card {
        flex-grow: 1;
        border: 2px solid #a46498;
        border-radius: 15px;
        padding: 20px;
        flex: 0 0 calc(50% - 5px);
        box-sizing: border-box;
    }

    .wcmp-product-card-warning {
        border: 2px solid #e0153a;
        background-color: #e0153a;
        color: white;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .wcmp-product-card-secondary {
        border-color: #e6e6e6;
    }

    .wcmp-product-title {
        margin-top: 0;
        margin-bottom: 20px;
        text-align: center;
    }

    .wcmp-warning-box-message {
        font-size: 14px;
        font-weight: 600;
        margin: 0;
    }

    .wcmp-warning-button {
        margin-top: 10px;
        margin-bottom: 0;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
        display: block;
        border-radius: 15px;
        text-decoration: none !important;
        background: white;
        padding: 15px;
        color: #e0153a;
        border: 2px solid #e0153a;
    }

    .wcmp-warning-button:hover {
        border-color: white;
        color: white;
        background-color: #e0153a;
        font-size: 16px;
        margin-top: 8px;
    }

    .wcmp-product-number {
        text-align: center;
        font-size: 32px;
        color: #a46498;
        font-weight: bold;
    }

    .wcmp-product-description {
        text-align: center;
        font-size: 14px;
        color: rgba(0, 0, 0, .5);
        margin-top: 10px;
        font-weight: 600;
    }

    /* Tablas y formularios */
    .wcmp-settings-table {
        width: 100%;
        margin-top: 20px;
        border: 0;
    }

    .wcmp-settings-td-label {
        vertical-align: top;
        white-space: nowrap;
        padding-top: 10px;
        font-weight: 600;
    }

    .wcmp-settings-td-content {
        width: 100%;
        padding-left: 20px;
    }

    .wcmp-button-table {
        width: 100%;
        margin-top: 30px;
        border: 0;
    }

    .wcmp-button-td {
        border: 0;
        text-align: center;
    }

    /* Botón circular */
    .wcmp-circular-button {
        width: 200px;
        height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        background: #a46498;
        border-radius: 50%;
    }

    .wcmp-circular-icon {
        font-size: 38px;
    }

    .wcmp-circular-text {
        font-size: 18px;
        font-weight: 600;
    }

    /* Sección de upgrade */
    .wcmp-upgrade-section {
        background: #000000;
    }

    .wcmp-upgrade-inner {
        padding: 0;
        background: #000000;
    }

    .wcmp-upgrade-title {
        text-align: left;
        color: white;
    }

    .wcmp-upgrade-subtitle {
        font-size: 90%;
        font-weight: 300;
    }

    .wcmp-price-container {
        text-align: center;
    }

    .wcmp-price-label {
        font-size: 22px;
        display: inline-block;
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0 10px;
    }

    .wcmp-price-amount {
        font-size: 32px;
        text-align: center;
        margin-top: 20px;
    }

    .wcmp-price-lifetime {
        font-size: 14px;
        text-align: center;
        color: rgba(255, 255, 255, .5);
    }

    .wcmp-upgrade-button-auto {
        width: auto;
        padding-left: 20px;
        padding-right: 20px;
        margin-top: 30px;
    }

    /* Párrafos específicos */
    .wcmp-paragraph {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 16px;
    }

    .wcmp-paragraph-large {
        margin-bottom: 40px;
    }

    .wcmp-paragraph-bold {
        text-align: left;
        font-size: 16px;
        font-weight: 600;
        margin-top: 20px;
    }

    /* Logo flotante */
    .wcmp-floating-logo {
        display: inline-block;
        position: absolute;
        left: calc(50% - 50px);
        top: -50px;
        border-radius: 50%;
        overflow: hidden;
        width: 100px;
        height: 100px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border: 3px solid white;
        box-shadow: 0px 0px 3px rgba(0, 0, 0, .5);
    }

    /* Contenedor centrado vertical */
    .wcmp-vertical-center {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Enlace sin decoración */
    .wcmp-link-no-decoration {
        text-decoration: none;
        color: white;
    }

    /* Tabla de botones */
    .wcmp-button-tr {
        border: 0;
    }

    @media screen AND (min-width:710px) {
        .wcmp-landing-page-advanced-columns {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 20px;
        }

        .wcmp-landing-page-advanced-col1 {
            max-width: 35%;
        }

        .wcmp-landing-page-advanced-col2 {
            max-width: 35%;
        }

        .wcmp-landing-page-advanced-col3 {
            padding-top: 30px;
            flex-grow: 1;
        }
    }

    .wcmp-landing-page-advanced-columns ul {
        list-style-type: none;
    }

    .wcmp-landing-page-advanced-columns ul li {
        position: relative;
        margin-bottom: 15px;
    }

    .wcmp-landing-page-advanced-columns ul li::before {
        content: "\2713";
        display: inline-block;
        color: yellow;
        margin-right: 10px;
    }
</style>
<div class="wrap wcmp-container-centered">
    <div class="postbox wcmp-landing-page">
        <div class="inside wcmp-inner-no-padding">
            <div style="display:inline-block;position:absolute;left:calc(50% - 50px);top:-50px;border-radius:50%;overflow:hidden;width:100px;height:100px;background-size:cover;
  background-position:center;
  background-repeat:no-repeat;border:3px solid white;box-shadow: 0px 0px 3px rgba(0, 0, 0, .5);background:url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIj4KICA8ZyBzdHlsZT0iIiB0cmFuc2Zvcm09Im1hdHJpeCgwLjY2ODMyNywgMCwgMCwgMC42NjgzMjcsIDAsIDApIj4KICAgIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiBzdHlsZT0iZmlsbDogcmdiKDE1NSwgOTIsIDE0Myk7IiBpZD0ib2JqZWN0LTAiLz4KICA8L2c+CiAgPHBhdGggZmlsbD0icmdiKDkwLDU2LDg3KSIgZD0iTSA0Ny4zMDUgMTUuOTcgQyA0Ny42ODEgMTUuOTI5IDQ4LjU1NCAxNS45NDMgNDguOTY1IDE1Ljk0NCBDIDUxLjY5MSAxNS45NDQgNTQuNDA3IDE2LjIzNyA1Ny4wNDggMTYuODE3IEMgNjYuMjkgMTguODA0IDc0LjI0MyAyMy44NzcgNzkuMTY2IDMwLjkyNiBDIDgyLjA3NiAzNS4xMjQgODMuOTQgMzkuOTgyIDg0LjM4NiA0NC44NzIgQyA4NC40NDcgNDUuNTQ3IDg0LjU3MiA0Ni41OCA4NC40MDIgNDcuMjIxIEMgODQuMjQ2IDQ3LjgwOCA4My44MTQgNDguMTM4IDgzLjI0NCA0OC40MjcgQyA4My4yMDQgNDkuMTY1IDgzLjIyNCA0OS45MzkgODMuMjIxIDUwLjY4MSBDIDgzLjIxOCA1MS40NTggODMuMjAzIDUyLjIzOCA4My4yMzcgNTMuMDEzIEMgODQuNTY5IDUzLjU2NiA4NC41OTUgNTQuNDgzIDg0LjU3NiA1NS42MiBDIDg0LjU2OCA1Ni4wOTUgODQuNTY2IDU2LjU4NyA4NC41NjUgNTcuMDYzIEMgODQuNTU1IDU4LjU4NiA4NC41NTkgNjAuMTExIDg0LjU3NSA2MS42MzUgQyA4NC41ODEgNjIuMTc4IDg0LjU5OSA2Mi41MDcgODQuMzE3IDYzLjAwMyBDIDgzLjYwMSA2NC4yNjIgODEuNTQ0IDY0LjU0NSA4MC40MTMgNjMuNTQ4IEMgNzkuNjc4IDYyLjkwMSA3OS43NzYgNjIuMTQ0IDc5Ljc4NiA2MS4zMTEgQyA3OS40NjEgNjEuMjk5IDc5LjEyNiA2MS4zMDMgNzguOCA2MS4zMDQgTCA3OC44MDYgNjMuODggQyA3OC44MDUgNjUuNzYgNzguNzg5IDY3LjAzMSA3Ny4xNTggNjguNTM0IEMgNzYuMDYzIDY5LjUzOSA3NC41NTMgNzAuMTI5IDcyLjk1NyA3MC4xNzYgQyA3MC4xOTUgNzAuMjM2IDY4LjU3NCA2OC44NjUgNjguNjAxIDY2LjQ2MSBDIDY4LjYxNyA2NC45NTMgNjguNjA3IDYzLjQxOCA2OC42MTEgNjEuOTA2IEwgNjguNjE1IDU0LjY4OCBMIDY4LjYxIDUxLjMwOCBDIDY4LjYxIDUwLjc5NSA2OC41NjMgNDkuODA3IDY4LjY3NCA0OS4zMzIgQyA2OS40NTcgNDYuMDMgNzQuOTAxIDQ2LjMwMiA3Ny4wOTggNDguMjY2IEMgNzguOTU0IDQ5LjkyMyA3OC44MiA1MS4yNTQgNzguODA4IDUzLjM2NiBMIDc4LjgwMiA1Ni40NDMgQyA3OS4wOTIgNTYuNDIxIDc5LjQ5NSA1Ni40MzQgNzkuNzk1IDU2LjQzNCBDIDc5Ljc4MyA1NS4wOTEgNzkuNDgxIDUzLjgxNSA4MC45ODUgNTMuMDAxIEwgODAuOTgxIDQ4LjQxOCBDIDgwLjUwMyA0OC4xMzQgODAuMDkxIDQ3Ljc2NSA3OS45MzggNDcuMjY2IEMgNzkuNzQ2IDQ2LjYzOCA3OS43NTEgNDUuNTAyIDc5LjY5NCA0NC44MDcgQyA3OS42MzggNDQuMTI0IDc5LjUyMyA0My40NDUgNzkuNDA0IDQyLjc2OCBDIDc4LjUzNiAzNy44NjMgNzUuOTQzIDMyLjg0MSA3Mi4xMiAyOS4xMjEgQyA2Ni4zMDcgMjMuNDY1IDU4LjgzMiAyMC4yNzMgNTAuMDkxIDE5LjgyOSBDIDQ4LjM0MyAxOS43MjggNDYuMzA4IDE5Ljg1MiA0NC41NzIgMjAuMDUyIEMgMzkuNTY3IDIwLjYzIDM1LjE1NiAyMi4xODIgMzAuOTk4IDI0LjY0IEMgMjQuMzAyIDI4LjY3NCAxOS42ODIgMzQuNzk5IDE4LjExNCA0MS43MjMgQyAxNy44OTcgNDIuNjI3IDE3Ljc0OCA0My41NDIgMTcuNjY3IDQ0LjQ2MiBDIDE3LjYxMSA0NS4xOTMgMTcuNTc3IDQ1LjkzIDE3LjUxMSA0Ni42NiBDIDE3LjQyOCA0Ny41NzIgMTcuMiA0Ny44OSAxNi4zMzUgNDguNCBDIDE2LjM2OCA0OC44MjcgMTYuMzQ4IDQ5LjQ3NSAxNi4zNDkgNDkuOTE5IEMgMTYuMzQzIDUwLjk0MiAxNi4zNSA1MS45NjYgMTYuMzY4IDUyLjk4OSBDIDE3Ljg2MSA1My45MzEgMTcuNTYyIDU0Ljk4NyAxNy41NTMgNTYuNDI0IEMgMTcuODkxIDU2LjQzNCAxOC4yNCA1Ni40MzQgMTguNTc5IDU2LjQzOCBDIDE4LjUyOCA1NC42NjcgMTguNTY1IDUyLjgxNiAxOC42MDYgNTEuMDQzIEMgMTguNjEgNTAuODgzIDE4LjY5NiA1MC41NzUgMTguNzQ1IDUwLjQxOCBDIDE4Ljk2OCA0OS43MDcgMTkuNDMzIDQ5LjAzNyAyMC4wMTQgNDguNTAxIEMgMjEuMTgxIDQ3LjM4NSAyMi43NSA0Ni44NjQgMjQuNDcgNDYuODM1IEMgMjUuNjc1IDQ2LjgwMSAyNi43NCA0Ni45NzUgMjcuNjQgNDcuNzE5IEMgMjguNTAxIDQ4LjQzMSAyOC43MDMgNDkuMzY3IDI4LjcwNiA1MC4zNTggQyAyOC43MTEgNTEuNzggMjguNzEyIDUzLjIwNiAyOC43MTIgNTQuNjI5IEwgMjguNzExIDYxLjk5IEwgMjguNzAxIDY1LjE0NiBDIDI4LjcwMSA2NS44NjkgMjguNzUyIDY2LjcyNSAyOC42MjIgNjcuNDI5IEMgMjguNDM2IDY4LjM1OSAyNy43MiA2OS4zNzcgMjYuNjk3IDY5Ljc4MSBDIDI0LjQ3NSA3MC42NTUgMjEuODYzIDY5Ljk2OCAyMC4yMjcgNjguNTMyIEMgMTkuMjY4IDY3LjY3OSAxOC42NzkgNjYuNTY3IDE4LjU1OCA2NS4zODIgQyAxOC41MTQgNjQuOTM2IDE4LjUzIDY0LjI2MyAxOC41MjggNjMuNzk3IEMgMTguNTI0IDYyLjk2MiAxOC41MjggNjIuMTI4IDE4LjU0MSA2MS4yOTQgQyAxOC4yMTMgNjEuMjkgMTcuODgzIDYxLjI5IDE3LjU1NCA2MS4yOTIgQyAxNy41NjEgNjEuNzc0IDE3LjU4IDYyLjIyMiAxNy40NDkgNjIuNjkyIEMgMTYuOTU2IDY0LjQ0OSAxMy45ODYgNjQuNTk4IDEzLjA1NiA2My4wOCBDIDEyLjkwNCA2Mi44MjYgMTIuODEgNjIuNTQ5IDEyLjc4MSA2Mi4yNjIgQyAxMi43MjcgNjEuNzc5IDEyLjc5IDYxLjIzIDEyLjc4MiA2MC43MzggQyAxMi43NSA1OC44MDcgMTIuNzgyIDU2Ljg3NiAxMi43NDEgNTQuOTQ2IEMgMTIuNzIxIDU0LjA2MyAxMy4yMzkgNTMuNDA0IDE0LjEzNiA1Mi45OTggQyAxNC4xNCA1MS41MSAxNC4xNzUgNDkuODk0IDE0LjEyNyA0OC40MTcgQyAxMy45MTcgNDguMzIyIDEzLjc4OCA0OC4yNjQgMTMuNjA2IDQ4LjEyNSBDIDEzLjE0MiA0Ny43NzUgMTIuODY4IDQ3LjI3NSAxMi44NDggNDYuNzQ0IEMgMTIuODA2IDQ1LjgzNCAxMi45ODcgNDQuNDg1IDEzLjExIDQzLjU3NSBDIDE0LjA0NiAzNy4wMzUgMTcuMyAzMC45MDQgMjIuNDI3IDI2LjAyNyBDIDI4LjgzOCAxOS45NDMgMzcuNzkzIDE2LjMyMyA0Ny4zMDUgMTUuOTcgWiIgc3R5bGU9InN0cm9rZS13aWR0aDogMC41cHg7IHRyYW5zZm9ybS1vcmlnaW46IDQ4LjY1OXB4IDQzLjA1OXB4IDBweDsiIGlkPSJvYmplY3QtMSIgdHJhbnNmb3JtPSJtYXRyaXgoMC45OTk5OTQsIC0wLjAwMzMxOCwgMC4wMDMzMTgsIDAuOTk5OTk0LCAwLCAwKSIvPgogIDxwYXRoIGZpbGw9IiNmZmYiIGQ9Ik0gMjMuODI1IDM1Ljk3OSBDIDI0LjUzMSAzNS4wMiAyNS41OSAzNC41MTYgMjcuMDAzIDM0LjQxNiBDIDI5LjU3NiAzNC4yMTQgMzEuMDM5IDM1LjQyNSAzMS4zOTMgMzguMDQ4IEMgMzIuOTU3IDQ4LjU5MyAzNC42NzMgNTcuNTI1IDM2LjQ4OSA2NC44NDEgTCA0Ny41MzkgNDMuODAxIEMgNDguNTQ4IDQxLjg4MyA0OS44MSA0MC44NzQgNTEuMzIzIDQwLjc3MyBDIDUzLjU0NCA0MC42MjIgNTQuOTA2IDQyLjAzNCA1NS40NjEgNDUuMDExIEMgNTYuNzIyIDUxLjcyMiA1OC4zMzYgNTcuNDIzIDYwLjI1NCA2Mi4yNjcgQyA2MS41NjYgNDkuNDUxIDYzLjc4NyA0MC4yMTggNjYuOTE1IDM0LjUxNiBDIDY3LjY3MSAzMy4xMDQgNjguNzgyIDMyLjM5NyA3MC4yNDUgMzIuMjk2IEMgNzEuNDA1IDMyLjE5NSA3Mi40NjUgMzIuNTQ4IDczLjQyNCAzMy4zMDUgQyA3NC4zODMgMzQuMDYyIDc0Ljg4NyAzNS4wMiA3NC45ODggMzYuMTgxIEMgNzUuMDM4IDM3LjA4OSA3NC44ODcgMzcuODQ3IDc0LjQ4MyAzOC42MDMgQyA3Mi41MTYgNDIuMjM2IDcwLjkwMSA0OC4zNDEgNjkuNTg5IDU2LjgxOCBDIDY4LjMyOCA2NS4wNDIgNjcuODczIDcxLjQ1IDY4LjE3NiA3Ni4wNDIgQyA2OC4yNzggNzcuMzAzIDY4LjA3NiA3OC40MTMgNjcuNTcxIDc5LjM3MiBDIDY2Ljk2NSA4MC40ODIgNjYuMDU3IDgxLjA4OCA2NC44OTcgODEuMTg5IEMgNjMuNTg1IDgxLjI4OSA2Mi4yMjIgODAuNjg0IDYwLjkxMSA3OS4zMjIgQyA1Ni4yMTggNzQuNTI5IDUyLjQ4NCA2Ny4zNjQgNDkuNzYgNTcuODI3IEMgNDYuNDc5IDY0LjI4NSA0NC4wNTggNjkuMTMgNDIuNDk0IDcyLjM1OCBDIDM5LjUxOCA3OC4wNiAzNi45OTUgODAuOTg3IDM0Ljg3NSA4MS4xMzggQyAzMy41MTMgODEuMjM5IDMyLjM1MiA4MC4wNzggMzEuMzQzIDc3LjY1NyBDIDI4Ljc3IDcxLjA0NiAyNS45OTUgNTguMjgxIDIzLjAxOCAzOS4zNiBDIDIyLjgxNiAzOC4wNDggMjMuMTE5IDM2Ljg4NyAyMy44MjUgMzUuOTc5IEwgMjMuODI1IDM1Ljk3OSBaIiBzdHlsZT0iIiBpZD0ib2JqZWN0LTIiLz4KPC9zdmc+');"></div>

            <div class="wcmp-text-right" style="padding-right:20px;"><a href="options-general.php?page=music-player-for-woocommerce-settings&close-landing-page=1"><?php esc_html_e('close the landing page', 'music-player-for-woocommerce'); ?></a></div>

            <div class="wcmp-header-padding">
                <h2 class="wcmp-title-center"><?php esc_html_e('Welcome to the Music Player for WooCommerce', 'music-player-for-woocommerce'); ?></h2>
                <hr>
            </div>
            <?php
            $product_counts = wp_count_posts('product');

            $total_products = array_sum((array) $product_counts);
            $downloadable_products = 0;

            global $wpdb;
            // Get IDs of downloadable products
            $product_ids = $wpdb->get_col("
                    SELECT post_id
                    FROM {$wpdb->postmeta}
                    WHERE meta_key = '_downloadable'
                    AND meta_value = 'yes'
                ");
            if (!empty($product_ids)) {
                $audio_ext = ['mp3', 'wav', 'ogg', 'm4a', 'flac', 'aac'];
                foreach ($product_ids as $product_id) {
                    $files = get_post_meta($product_id, '_downloadable_files', true);
                    if (empty($files) || ! is_array($files)) continue;
                    foreach ($files as $file) {
                        if (empty($file['file'])) continue;
                        $ext = strtolower(pathinfo($file['file'], PATHINFO_EXTENSION));
                        if (in_array($ext, $audio_ext, true)) {
                            $downloadable_products++;
                            break;
                        }
                    }
                }
            }
            ?>
            <div class="wcmp-section-padding">
                <?php
                if (empty($updated_settings)): // This variable come from the plugin settings page.
                ?>
                    <p class="wcmp-paragraph"><?php esc_html_e('You are currently using the free version of Music Player for WooCommerce, which allows audio players only on downloadable products that include at least one audio file.', 'music-player-for-woocommerce'); ?></p>
                    <p class="wcmp-paragraph"><?php esc_html_e('Upgrade to the commercial version to unlock full flexibility: enable audio players on any WooCommerce product, whether downloadable or physical, and present your audio content exactly where it drives the most value.', 'music-player-for-woocommerce'); ?></p>
                    <p class="wcmp-paragraph wcmp-paragraph-large"><?php esc_html_e('Upgrade to the commercial version to unlock full flexibility: enable audio players on any WooCommerce product, whether downloadable or physical, and present your audio content exactly where it drives the most value.', 'music-player-for-woocommerce'); ?></p>

                    <div class="wcmp-products-grid">
                        <?php if (0 == $downloadable_products): ?>
                            <div class="wcmp-product-card wcmp-product-card-warning">
                                <p class="wcmp-warning-box-message"><?php esc_html_e('You currently have no downloadable products in your store. Please note that the free version of our plugin only supports downloadable products that include at least one audio file.', 'music-player-for-woocommerce'); ?></p>
                                <a href="http://localhost/wp54/wp-admin/edit.php?post_type=product" class="wcmp-warning-button">Go to your products list</a>
                            </div>
                        <?php else: ?>
                            <div class="wcmp-product-card">
                                <h3 class="wcmp-product-title"><?php esc_html_e('Downloadable Products', 'music-player-for-woocommerce'); ?></h3>
                                <div class="wcmp-product-number"><?php print esc_html($downloadable_products); ?></div>
                                <div class="wcmp-product-description"><?php esc_html_e('Compatible with the Music Player for WooCommerce Free', 'music-player-for-woocommerce'); ?></div>
                            </div>
                        <?php endif; ?>
                        <div class="wcmp-product-card wcmp-product-card-secondary">
                            <h3 class="wcmp-product-title"><?php esc_html_e('Other Products', 'music-player-for-woocommerce'); ?></h3>
                            <div class="wcmp-product-number" style="color: inherit;"><?php print esc_html($total_products - $downloadable_products); ?></div>
                            <div class="wcmp-product-description"><a href="https://wcmp.dwbooster.com/download" target="_blank"><?php esc_html_e('Compatible with the Music Player for WooCommerce Pro', 'music-player-for-woocommerce'); ?></a></div>
                        </div>
                    </div>
                    <p class="wcmp-paragraph-bold"><?php esc_html_e('Using this dialog, you can automatically enable the audio player for all downloadable products that contain one or more associated audio files.', 'music-player-for-woocommerce'); ?></p>
                    <form method="post" action="options-general.php?page=music-player-for-woocommerce-settings">
                        <?php wp_nonce_field('wcmp_updating_plugin_settings_landing_page', 'wcmp_nonce'); ?>
                        <table border="0" class="wcmp-settings-table wcmp-player-settings">
                            <tr>
                                <td class="wcmp-settings-td-label"><?php esc_html_e('Select a player skin:', 'music-player-for-woocommerce'); ?></td>
                                <td class="wcmp-settings-td-content">
                                    <table border="0" class="wcmp-settings-table wcmp-player-settings">
                                        <tr>
                                            <td><input aria-label="<?php esc_attr_e('Skin 1', 'music-player-for-woocommerce'); ?>" id="skin1" name="_wcmp_player_layout" type="radio" value="mejs-classic" checked /></td>
                                            <td class="wcmp-settings-td-content"><label for="skin1"><img alt="<?php esc_attr_e('Skin 1', 'music-player-for-woocommerce'); ?>" src="<?php print esc_url(WCMP_PLUGIN_URL); ?>/views/assets/skin1.png" /></label></td>
                                        </tr>

                                        <tr>
                                            <td><input aria-label="<?php esc_attr_e('Skin 2', 'music-player-for-woocommerce'); ?>" id="skin2" name="_wcmp_player_layout" type="radio" value="mejs-ted" /></td>
                                            <td class="wcmp-settings-td-content"><label for="skin2"><img alt="<?php esc_attr_e('Skin 2', 'music-player-for-woocommerce'); ?>" src="<?php print esc_url(WCMP_PLUGIN_URL); ?>/views/assets/skin2.png" /></label></td>
                                        </tr>

                                        <tr>
                                            <td><input aria-label="<?php esc_attr_e('Skin 3', 'music-player-for-woocommerce'); ?>" id="skin3" name="_wcmp_player_layout" type="radio" value="mejs-wmp" /></td>
                                            <td class="wcmp-settings-td-content"><label for="skin3"><img alt="<?php esc_attr_e('Skin 3', 'music-player-for-woocommerce'); ?>" src="<?php print esc_url(WCMP_PLUGIN_URL); ?>/views/assets/skin3.png" /></label></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="wcmp-settings-td-label"><?php esc_html_e('Other options:', 'music-player-for-woocommerce'); ?></td>
                                <td class="wcmp-settings-td-content">
                                    <label><input aria-label="<?php esc_attr_e('Visualizer control', 'music-player-for-woocommerce'); ?>" type="checkbox" name="_wcmp_visualizer" value="ok" checked />
                                        <?php esc_html_e('Display the visualizer component over the player.', 'music-player-for-woocommerce'); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="wcmp-settings-td-content">
                                    <label><input aria-label="<?php esc_attr_e('Display the player title', 'music-player-for-woocommerce'); ?>" type="checkbox" id="_wcmp_player_title" name="_wcmp_player_title" checked /> <?php esc_html_e('Display the player\'s title', 'music-player-for-woocommerce'); ?></label>
                                </td>
                            </tr>
                        </table>
                        <table border="0" class="wcmp-button-table">
                            <tr class="wcmp-button-tr">
                                <td class="wcmp-button-td">
                                    <a class="wcmp-main-button" href="javascript:void(0);" onclick="this.closest('form').submit();"><?php esc_html_e('Enabling the audio player', 'music-player-for-woocommerce'); ?></a>
                                </td>
                                <td class="wcmp-button-td">
                                    <a href="<?php print esc_attr(admin_url('edit.php?post_type=product')); ?>" class="wcmp-secondary-button"><?php esc_html_e('Go to your products list', 'music-player-for-woocommerce'); ?></a>
                                </td>
                            </tr>
                        </table>
                    </form>
                <?php
                else:
                ?>
                    <div class="wcmp-vertical-center">
                        <a href="<?php print esc_attr(wc_get_page_permalink('shop')); ?>" class="wcmp-link-no-decoration">
                            <div class="wcmp-circular-button">
                                <div class="wcmp-circular-icon">
                                    <?php
                                    esc_html_e('Thanks!!!', 'music-player-for-woocommerce');
                                    ?>
                                </div>
                                <div class="wcmp-circular-text">
                                    <?php esc_html_e('Visit your store', 'music-player-for-woocommerce'); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>

    <div class="postbox wcmp-landing-page wcmp-upgrade-section">
        <div class="inside wcmp-upgrade-inner">
            <div class="wcmp-section-padding wcmp-why-upgrade">
                <h2 class="wcmp-upgrade-title">
                    <?php
                    esc_html_e('Why Upgrade?', 'music-player-for-woocommerce');
                    ?>
                    <span class="wcmp-upgrade-subtitle">
                        <?php
                        esc_html_e('(One-time purchase, lifetime access plugin updates)', 'music-player-for-woocommerce');
                        ?>
                    </span>
                </h2>
                <div class="wcmp-landing-page-advanced-columns">
                    <ul class="wcmp-landing-page-advanced-col1">
                        <li><b><?php esc_html_e('Display players on non-downloadable products', 'music-player-for-woocommerce'); ?></b>: <?php esc_html_e('Allow audio players to be enabled on non-downloadable products.', 'music-player-for-woocommerce'); ?></li>
                        <li><b><?php esc_html_e('Protect the audio files', 'music-player-for-woocommerce'); ?></b>: <?php esc_html_e('Generate new demo audio files to ensure that unauthorized access only exposes demo versions, not the full sales audio.', 'music-player-for-woocommerce'); ?></li>
                        <li><b><?php esc_html_e('Audio watermark', 'music-player-for-woocommerce'); ?></b>: <?php esc_html_e('Allows you to apply audio watermarks to demo files.', 'music-player-for-woocommerce'); ?></li>
                    </ul>
                    <ul class="wcmp-landing-page-advanced-col2">
                        <li><b><?php esc_html_e('Demo specific audio files', 'music-player-for-woocommerce'); ?></b>: <?php esc_html_e('Assign specific audio files as demo versions, keeping them separate from the original audio files.', 'music-player-for-woocommerce'); ?></li>
                        <li><b><?php esc_html_e('Allow accessing purchased files', 'music-player-for-woocommerce'); ?></b>: <?php esc_html_e('Allows verified buyers to listen to the original audio files rather than demo versions.', 'music-player-for-woocommerce'); ?></li>
                    </ul>
                    <div class="wcmp-landing-page-advanced-col3">
                        <div class="wcmp-price-container"><span class="wcmp-price-label">PRO</span></div>
                        <div class="wcmp-price-amount">€30.00</div>
                        <div class="wcmp-price-lifetime"><?php esc_html_e('LIFETIME', 'music-player-for-woocommerce'); ?></div>
                        <div class="wcmp-price-container"><a href="https://wcmp.dwbooster.com/download" target="_blank" class="wcmp-secondary-button wcmp-upgrade-button-auto"><?php esc_html_e('UPGRADE!', 'music-player-for-woocommerce'); ?></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>