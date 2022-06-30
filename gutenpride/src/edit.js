import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { RichText, InspectorControls } from '@wordpress/block-editor';
import { ToggleControl } from '@wordpress/components';
import { Panel, PanelBody } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, content, showHeading, showParagraph } = attributes;
	const blockProps = useBlockProps();
	
	return (
		<div { ...blockProps }>
			{showHeading && 
				<RichText
					tagName="h1"
					value={ heading }
					onChange={ ( heading ) => {
						setAttributes( { heading } )
					} }
					placeholder={ __( 'Write Heading...' ) } 
				/>				
			}
			{showParagraph && 
				<RichText
					tagName="p"
					value={ content }
					onChange={ ( content ) => {
						setAttributes( { content } )
					} }
					placeholder={ __( 'Write Content...' ) }
				/>
			}
			<InspectorControls>
				<Panel>
					<PanelBody title={ __( 'Heading Toggle' ) } initialOpen={ true }>
						<ToggleControl
							label={ __( 'Show Heading' ) }
							checked={ showHeading }
							onChange={ () => setAttributes( { showHeading: !showHeading } ) }
						/>
					</PanelBody>
					<PanelBody title={ __( 'Body Toggle' ) } initialOpen={ true }>
						<ToggleControl
							label={ __( 'Show Paragraph' ) }
							checked={ showParagraph }
							onChange={ () => setAttributes( { showParagraph: !showParagraph } ) }
						/>
					</PanelBody>
				</Panel>
			</InspectorControls>
		</div>
	);
}