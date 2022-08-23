import { useBlockProps } from '@wordpress/block-editor';
import { RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const {
		header_title, header_subtitle, image,
		showTitle, showSubtitle, showHeader, showImage, showRow,
		row_one, showRowOne, showRowOneImage, showRowOneText,
		row_two, showRowTwo, showRowTwoImage, showRowTwoText,
	} = attributes;
	return (
		<div { ...useBlockProps.save() } >
			{ showHeader &&
				<div className="wp-block-themeisle-blocks-header"
					style={{
						backgroundImage: showImage ? `url(${ image.url })` : 'none',
						backgroundSize: 'cover',
						backgroundRepeat: 'no-repeat',
						backgroundAttachment: 'fixed',
						height: '45vh',
					}}
				>
					<div className="wp-block-themeisle-blocks-header__title"
						style={{
						textAlign: header_title.align,
						color: header_title.color,
					}}>
						{ showTitle && 
							<RichText.Content tagName="h2" value={ header_title.text } 
								style={{
									paddingTop: '1.5em',
								}}
							/>
						}
					</div>
					<div className="wp-block-themeisle-blocks-header__subtitle" style={{ textAlign: header_title.align }}>
						{ showSubtitle &&
							<RichText.Content tagName="small" value={ header_subtitle.text } 
								style={{
									color: header_subtitle.color,
								}}
							/>
						}
					</div>
				</div>
			}
			{ showRow &&
				<div className="wp-block-themeisle-blocks-blocks-body">
					{ showRowOne &&
						<div className="wp-block-themeisle-blocks-blocks-body__row-one" style={{ backgroundColor: row_one.back }} >
							<table>
								<tbody>
									<tr>
										{ showRowOneImage &&
											<td>
												<img src={ row_one.url } alt={ row_one.alt } style={{ width: '90%' }} />
											</td>
										}
										{ showRowOneText &&
											<td style={{ padding: '1.5rem' }}>
												<RichText.Content tagName="p" value={ row_one.text } style={{ color: row_one.color }} />
											</td>
										}
									</tr>
								</tbody>
							</table>
						</div>
					}
					{ showRowTwo &&
						<div className="wp-block-themeisle-blocks-blocks-body__row-two" style={{ backgroundColor: row_two.back }} >
						<table>
							<tbody>
								<tr>
									{ showRowTwoText &&
										<td style={{ padding: '1.5rem' }}>
											<RichText.Content tagName="p" value={ row_two.text } style={{ color: row_two.color }} />
										</td>
									}
									{ showRowTwoImage &&
										<td>
											<img src={ row_two.url } alt={ row_two.alt } style={{ width: '90%' }} />
										</td>
									}
								</tr>
							</tbody>
						</table>
					</div>
					}
				</div>
			}
		</div>
	);
}
