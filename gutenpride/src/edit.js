import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { SelectControl, ToggleControl, Panel, PanelBody, TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import apiFetch from '@wordpress/api-fetch';

export default function Edit( { props, attributes } ) {
	const { heading, showHeading } = attributes;
	const blockProps = useBlockProps();
	if( !props.attributes.contactform ) {
		apiFetch({
			url: '/wp-json/wp/v2/contactform'
		}).then( contactform => {
			props.setAttributes( {
				contactform: contactform.map( post => {
					return {
						value: post.id,
						label: post.title.rendered
					}
				})
			})
		});
	}
	if( !props.attributes.contactform) {
		return 'Loading...';
	}
	if( props.attributes.contactform.length === 0 && props.attributes.contactform) {
		return 'No contact forms found';
	}
	const postType = useSelect( 
		( select ) => select( 'core/editor' ).
		getCurrentPostType() 
	);
	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );
	const metaFieldValue = meta.myguten_meta_block_field;
	const updateMetaField = ( newValue ) => {
		setMeta( { ...meta, myguten_meta_block_field: newValue } );
	};
	// const output = <div className={ blockProps.className }>
	// 	<h2 dangerouslySetInnerHTML={{__html:post.title.rendered}}></h2>
	// 	</div>;
	return (
		<div { ...blockProps }>
			{showHeading && 
				<RichText
					tagName="h1"
					value={ heading }
					onChange={ ( heading ) => {
						props.setAttributes( { heading } )
					} }
					placeholder={ __( 'Write Heading...' ) } 
				/>				
			}
			<TextControl
				label = "Meta Field"
				value = { metaFieldValue }
				onChange = { updateMetaField }
			/>
			<InspectorControls>
				<Panel>
					<PanelBody title={ __( 'Heading Toggle' ) } initialOpen={ true }>
						<ToggleControl
							label={ __( 'Show Heading' ) }
							checked={ showHeading }
							onChange={ () => props.setAttributes( { showHeading: !showHeading } ) }
						/>
					</PanelBody>
				</Panel>
				<Panel>
                    <PanelBody title="Contact Form" initialOpen={ true }>
                        <SelectControl
                            label="Select Contact Form"
                            value={ props.attributes.selectedPost }
                            options={ props.attributes.contactform}
                            onChange={ ( value ) => {
                                props.setAttributes( {
                                    selectedPost: value
                                } ) }
                            }
                        />
                    </PanelBody>
                </Panel>
			</InspectorControls>
		</div>
	);
}