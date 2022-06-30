import { useBlockProps } from '@wordpress/block-editor';
import { RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
export default function save( { attributes } ) {
	const { heading, content, showHeading, showParagraph } = attributes;
	const blockProps = useBlockProps.save;
	return <div { ...blockProps }>
		{showHeading && <RichText.Content tagName="h1" value={ heading} />}
		{showParagraph && <RichText.Content tagName="p" value={ content} />}
	</div>;
}