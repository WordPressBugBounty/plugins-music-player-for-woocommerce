( function( blocks, element ) {
	var el 					= element.createElement,
		InspectorControls 	= ('blockEditor' in wp) ? wp.blockEditor.InspectorControls : wp.editor.InspectorControls;

	/* Plugin Category */
	blocks.getCategories().push({slug: 'wcmp', title: 'WooCommerce Music Player'});

	/* ICONS */
	const iconWCMPP = el('img', { width: 20, height: 20, src:  "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbDpzcGFjZT0icHJlc2VydmUiIHN0eWxlPSJmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MiIgdmlld0JveD0iMCAwIDQ4IDQ4Ij48cGF0aCBkPSJNNDMuNSAxMC4wMTJ2MS44MjZjMCAuNTA0LS43MjcuOTEzLTEuNjIyLjkxM0g1LjY3MWMtLjg5NSAwLTEuNjIyLS40MDktMS42MjItLjkxM3YtMS44MjZjMC0uNTA1LjcyNy0uOTE0IDEuNjIyLS45MTRoMzYuMjA3Yy44OTUgMCAxLjYyMi40MDkgMS42MjIuOTE0IiBzdHlsZT0iZmlsbDojYTA2MzlhIiB0cmFuc2Zvcm09Im1hdHJpeCgxIDAgMCAxLjc3NTY2IDAgMy40NDMpIi8+PHBhdGggZD0iTTM3LjYwMSAxMi43NTFINi4xMTljLTEuMTQyIDAtMi4wNy0uNDA5LTIuMDctLjkxM3YtMS44MjZjMC0uNTA1LjkyOC0uOTE0IDIuMDctLjkxNGgzMS40ODJtNC43NjQgMy41NTVhNC4zIDQuMyAwIDAgMS0uOTM1LjA5OCIgc3R5bGU9ImZpbGw6I2EwNjM5YSIgdHJhbnNmb3JtPSJtYXRyaXgoLjc4MzUyIDAgMCAxLjc3NTY2IC44NzcgMTMuOTQzKSIvPjxwYXRoIGQ9Ik00My41IDEwLjAxMnYxLjgyNmMwIC41MDQtLjcyNy45MTMtMS42MjIuOTEzSDUuNjcxYy0uODk1IDAtMS42MjItLjQwOS0xLjYyMi0uOTEzdi0xLjgyNmMwLS41MDUuNzI3LS45MTQgMS42MjItLjkxNGgzNi4yMDdjLjg5NSAwIDEuNjIyLjQwOSAxLjYyMi45MTQiIHN0eWxlPSJmaWxsOiNhMDYzOWEiIHRyYW5zZm9ybT0ibWF0cml4KDEgMCAwIDEuNzc1NjYgMCAtNy4wNTcpIi8+PHBhdGggZD0ibTMxLjEzMSAyNy4zNCA3LjEzMiAxMi44MzNIMjR6IiBzdHlsZT0iZmlsbDojYTA2MzlhIiB0cmFuc2Zvcm09Im1hdHJpeCgwIDEgLS43MDEwNCAwIDYyLjY2NiA2LjA5OCkiLz48L3N2Zz4=" } );

	/* Sell Downloads Shortcode */
	blocks.registerBlockType( 'wcmp/woocommerce-music-player-playlist', {
		title: 'WooCommerce Music Player Playlist',
		icon: iconWCMPP,
		category: 'wcmp',
		customClassName: false,
		supports:{
			customClassName: false,
			className: false
		},
		attributes: {
			shortcode : {
				type : 'string',
				source : 'text',
				default: '[wcmp-playlist products_ids="*" controls="track" download_links="1"]'
			}
		},

		edit: function( props ) {
			var children = [], focus = props.isSelected;

			children.push(
				el('textarea',
					{
						key : 'wcmp_playlist_shortcode',
						value: props.attributes.shortcode,
						onChange: function(evt){
							props.setAttributes({shortcode: evt.target.value});
						},
						style: {width:"100%", resize: "vertical"}
					}
				)
			);

			children.push(
				el(
					'div', {className: 'wcmp-iframe-container', key:'wcmp_iframe_container', style:{position:'relative'}},
					el('div', {className: 'wcmp-iframe-overlay', key:'wcmp_iframe_overlay', style:{position:'absolute',top:0,right:0,bottom:0,left:0}}),
					el('iframe',
						{
							key: 'wcmp_iframe',
							src: wcmp_gutenberg_editor_config.url+encodeURIComponent(props.attributes.shortcode),
							height: 0,
							width: 500,
							scrolling: 'no'
						}
					)
				)
			);

			if(!!focus)
			{
				children.push(
					el(
						InspectorControls,
						{
							key : 'wcmp_playlist'
						},
                        el(
                            'div',
                            {
                                key: 'cp_inspector_container',
                                style:{paddingLeft:'15px',paddingRight:'15px'}
                            },
                            [
                                el(
                                    'b',
                                    {
                                        key: 'wcmp_inspector_help_main_attributes',
										style: { 'textTransform': 'uppercase' }
                                    },
									'Main playlist attributes',
									el(
										'hr',
										{
											key: 'wcmp_inspector_help_separator'
										}
									)
                                ),
                                el(
                                    'p',
                                    {
                                        key: 'wcmp_inspector_help_ids_attr'

                                    },
									el(
										'b',
										{
											key: 'wcmp_inspector_help_ids_attr_b'
										},
										'products_ids: '
									),
									wcmp_gutenberg_editor_config.ids_attr_description
                                ),
                                el(
                                    'p',
                                    {
                                        key: 'categories_attr_description_cat_attr'

                                    },
									el(
										'b',
										{
											key: 'categories_attr_description_cat_attr_b'
										},
										'product_categories: '
									),
									wcmp_gutenberg_editor_config.categories_attr_description
                                ),
                                el(
                                    'p',
                                    {
                                        key: 'tags_attr_description_tag_attr'

                                    },
									el(
										'b',
										{
											key: 'tags_attr_description_tag_attr_b'
										},
										'product_tags: '
									),
									wcmp_gutenberg_editor_config.tags_attr_description
                                ),
                                el(
                                    'p',
                                    {
                                        key   : 'wcmp_inspector_more_help',
                                        style : {fontWeight: 'bold'}
                                    },
                                    wcmp_gutenberg_editor_config.more_details
                                ),
                                el(
                                    'a',
                                    {
                                        key		: 'wcmp_inspector_help_link',
                                        href	: 'https://wcmp.dwbooster.com/documentation#playlist-shortcode',
                                        target	: '_blank',
										style   : {'marginBottom' : '20px', 'display' : 'block'}
                                    },
                                    'CLICK HERE'
                                ),
                            ]
                        )
					)
				);
			}
			return children;
		},

		save: function( props ) {
			return props.attributes.shortcode;
		}
	});
} )(
	window.wp.blocks,
	window.wp.element
);