/**
 * Hello World: Step 4
 *
 * Adding extra controls: built-in alignment toolbar.
 */
( function( blocks, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;
	var Editable = blocks.Editable;
	var children = blocks.source.children;
	var AlignmentToolbar = wp.blocks.AlignmentToolbar;
	var BlockControls = wp.blocks.BlockControls;
	var InspectorControls = wp.blocks.InspectorControls;

	blocks.registerBlockType( 'gutenberg-wpcloudy/wpcloudy', {
		title: __( 'Weather', 'wp-cloudy' ),
		icon: 'cloud',
		category: 'widgets',

		attributes: {
			content: {
				type: 'array',
				source: 'children',
				selector: 'p',
			},
		},

		edit: function( props ) {
			var content = props.attributes.content;
			var alignment = props.attributes.alignment;
			var focus = props.focus;

			function onChangeContent( newContent ) {
				props.setAttributes( { content: newContent } );
			}

			function onChangeAlignment( newAlignment ) {
				props.setAttributes( { alignment: newAlignment } );
			}

			return [
				!! focus && el(
					BlockControls,
					{ key: 'controls' },
					el(
						AlignmentToolbar,
						{
							value: alignment,
							onChange: onChangeAlignment
						}
					)
				),
				el(
					Editable,
					{
						key: 'editable',
						tagName: 'p',
						className: props.className,
						style: { textAlign: alignment },
						onChange: onChangeContent,
						value: content,
						focus: focus,
						onFocus: props.setFocus
					}
				)
			];
		},

		save: function( props ) {
			var saveProps = {};
			if ( props.attributes.alignment ) {
				saveProps.className =
					'gutenberg-examples-align-' + props.attributes.alignment;
			}
			return el(
				'p',
				saveProps,
				props.attributes.content
			);
		},
	} );
} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element
);