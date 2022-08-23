import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { RichText, InspectorControls, MediaUpload, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import { Panel, PanelBody, Button, ToggleControl, ColorPalette } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const {
		header_title, header_subtitle, image,
		showHeader, showTitle, showSubtitle, showImage, showRow,
		row_one, showRowOne, showRowOneImage, showRowOneText,
		row_two, showRowTwo, showRowTwoImage, showRowTwoText,
	} = attributes; 	
	return (
		<div { ...useBlockProps() }>
			{ showHeader && 
				<div className="wp-block-themeisle-blocks-header" 
					style={{
						backgroundImage: showImage ? `url(${ image.url })` : 'none',
						backgroundSize: 'cover',
						backgroundRepeat: 'no-repeat',
						backgroundAttachment: 'fixed',
						height: '45vh',
					}}>
					<div className="wp-block-themeisle-blocks-header__title" 
						style={{
							textAlign: header_title.align,
							color: header_title.color,
						}}>
						{
							<BlockControls>
								<AlignmentToolbar
									value = { header_title.align }
									onChange = { ( align ) => {
										setAttributes( {
											header_title: {
												...header_title,
												align: align === undefined ? 'none' : align,
											}
										} );
									} }
									placeholder = { __( 'Align title' ) }
								/>
							</BlockControls>
						}
						{ showTitle && 
							<RichText
								tagName="h2"
								value={ header_title.text }
								onChange={ ( value ) => {
									setAttributes( {
										header_title: {
											...header_title,
											text: value,
										}
									} );
								} }
								style={{
									paddingTop: '1.5em',
								}}
								placeholder={ __( 'Top title', 'themeisle-companion' ) }
							/>
						}
					</div>
					<div className="wp-block-themeisle-blocks-header__subtitle" style= {{ textAlign: header_title.align }} >
						{ showSubtitle &&
							<RichText
								tagName="small"
								value={ header_subtitle.text }
								onChange={ ( value ) => {
									setAttributes( {
										header_subtitle: {
											...header_subtitle,
											text: value
										}
									} );
								} }
								style={{
									color: header_subtitle.color,
								}}
								placeholder={ __( 'Top subtitle', 'themeisle-companion' ) }
							/>
						}
					</div>
				</div> 
			}
			{ showRow &&
				<div className="wp-block-themeisle-blocks-body">
					{ showRowOne &&
						<div className="wp-block-themeisle-blocks-body__row-one" style={{ backgroundColor: row_one.back, height:'25vh'}}>
							<table>
								<tbody>
									<tr className='row-one'>
										{ showRowOneImage &&
											<td>
												{ 
													(row_one.url != 0) ? (
														<div>
															<img src={ row_one.url } alt={ row_one.alt } style={{ width: '90%' }} />
															<Button onClick={ () => {
																setAttributes( {
																	row_one: {
																		...row_one,
																		id: 0,
																		url: '',
																		alt: '',
																	}
																} );
															}
															} className="button button-primary" style={{ marginLeft: '5vh', marginTop: '1vh', marginBottom: '1vh' }} >
																{ __( 'Remove Image' ) }
															</Button>
															<MediaUpload
																onSelect={ ( media ) => {
																	setAttributes( {
																		row_one: {
																			...row_one,
																			id: media.id,
																			url: media.url,
																			alt: media.alt,
																		}
																	} );
																}}
																value={ row_one.id }
																render={ ( { open } ) => (
																	<Button onClick={ open } className="button" style={{ marginLeft: '5vh', marginTop: '1vh', marginBottom: '1vh' }}>
																		{ __( 'Change Image' ) }
																	</Button>
																) }
															/>
														</div>
													) : (
														<div>
															<MediaUpload
																onSelect={ ( media ) => {
																	setAttributes( {
																		row_one: {
																			...row_one,
																			id: media.id,
																			url: media.url,
																			alt: media.alt,
																		}
																	} );
																}}
																value={ row_one.id }
																render={ ( { open } ) => (
																	<Button onClick={ open } className="button">
																		{ __( 'Select Image' ) }
																	</Button>
																) }
															/>
														</div>
													)
												}
											</td>
										}
										{ showRowOneText &&
											<td style={{ padding: '1.5em' }}>
												<RichText
													placeholder='...Write Content'
													tagName="p"
													value={ row_one.text }
													onChange={ ( value ) => {
														setAttributes( {
															row_one: {
																...row_one,
																text: value,
															}
														} );
													} }
													style={{
														color: row_one.color,
													}}
												/>
											</td>
										}
									</tr>
								</tbody>
							</table>
						</div>
					}
					{ showRowTwo &&
						<div className="wp-block-themeisle-blocks-body__row-two" style={{ backgroundColor: row_two.back }}>
							<table>
								<tbody>
									<tr className='row-two'>
										{ showRowTwoText &&
											<td style={{ padding: '1.5em' }}>
												<RichText
													placeholder='...Write Content'
													tagName="p"
													value={ row_two.text }
													onChange={ ( value ) => {
														setAttributes( {
															row_two: {
																...row_two,
																text: value,
															}
														} );
													} }
													style={{
														color: row_two.color,
													}}
												/>
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
			<InspectorControls>
				<Panel>
					<PanelBody title={ __( 'Header Settings', 'themeisle-companion' ) } initialOpen={ false }>
						<ToggleControl
							label={ __( 'Show Header', 'themeisle-companion' ) }
							checked={ showHeader }
							onChange={ ( value ) => {
								setAttributes( {
									showHeader: value,
								} );
							} }
						/>
						<PanelBody title='Header Background' initialOpen={false}>
							<ToggleControl
								label={ __( 'Show Background Image', 'themeisle-companion' ) }
								checked={ showImage }
								onChange={ ( value ) => {
									setAttributes( {
										showImage: value
									} );
								} }
							/><hr></hr>
							<MediaUpload
								onSelect={ ( media ) => {
									setAttributes( {
										image: {
											id: media.id,
											url: media.url,
											alt: media.alt,
										}
									} );
								}}
								value={ image.id }
								render={ ( { open } ) => (
									<Button onClick={ open } className="button">
										{ __( 'Select Image' ) }
									</Button>
								) }
							/><hr></hr>
							{ image.id !=0 && 
								<Button onClick={ () => {
									setAttributes( {
										image: {
											id: 0,
											url: '',
											alt: '',
										}
									} );
								}
								} className="button button-primary">
									{ __( 'Remove Image' ) }
								</Button>
							}<hr></hr>
							{ image.url != '' &&
								<div className="wp-block-themeisle-blocks-header__image">
									<img src={ image.url } alt={ image.alt } />
								</div>
							}
						</PanelBody>
						<PanelBody title='Header Title' initialOpen={ false }>
							<ToggleControl
								label={ __( 'Show Title', 'themeisle-companion' ) }
								checked={ showTitle }
								onChange={ ( value ) => {
									setAttributes( {
										showTitle: value
									} );
								} }
							/>
							<ColorPalette
								colors={[
									{ name: 'Black', color: '#000' },
									{ name: 'White', color: '#fff' },
									{ name: 'Red', color: '#ff0000' },
									{ name: 'Green', color: '#00ff00' },
									{ name: 'Blue', color: '#0000ff' },
								]}
								value={ header_title.color }
								onChange={ ( value ) => {
									setAttributes( {
										header_title: {
											...header_title,
											color: value 
										} } 
									);
								} }
							/>
						</PanelBody>
						<PanelBody title='Header Subtitle' initialOpen={ false }>
							<ToggleControl
								label={ __( 'Show Subtitle', 'themeisle-companion' ) }
								checked={ showSubtitle }
								onChange={ ( value ) => {
									setAttributes( {
										showSubtitle: value
									} );
								} }
							/>
							<ColorPalette
								colors={[
									{ name: 'Black', color: '#000' },
									{ name: 'White', color: '#fff' },
									{ name: 'Red', color: '#ff0000' },
									{ name: 'Green', color: '#00ff00' },
									{ name: 'Blue', color: '#0000ff' },
								]}
								value={ header_subtitle.color }
								onChange={ ( value ) => {
									setAttributes( {
										header_subtitle: {
											...header_subtitle,
											color: value 
										} } 
									);
								} }
							/>
						</PanelBody>
					</PanelBody>
					<PanelBody title='Body Settings' initialOpen={ false }>
						<ToggleControl
							label={ __( 'Show Row', 'themeisle-companion' ) }
							checked={ showRow }
							onChange={ ( value ) => {
								setAttributes( {
									showRow: value
								} );
							} }
						/>
						<PanelBody title='Row One Settings' initialOpen={false}>
							<ToggleControl
								label={ __( 'Show Row One', 'themeisle-companion' ) }
								checked={ showRowOne }
								onChange={ ( value ) => {
									setAttributes( {
										showRowOne: value
									} );
								} }
							/>
							<ColorPalette
								value={ row_one.back }
								onChange={ ( value ) => {
									setAttributes( {
										row_one: {
											...row_one,
											back: value
										}
									} );
								} }
							/><hr></hr>
							<PanelBody title='Image Settings' initialOpen={false}>
								<ToggleControl
									label={ __( 'Show Image', 'themeisle-companion' ) }
									checked={ showRowOneImage }
									onChange={ ( value ) => {
										setAttributes( {
											showRowOneImage: value
										} );
									} }
								/>
								<hr></hr>
								{ row_one.url != '' &&
									<div className="wp-block-themeisle-blocks-body__image">
										<img src={ row_one.url } alt={ row_one.alt } />
									</div>
								}
							</PanelBody>
							<PanelBody title='Text Settings' initialOpen={false}>
								<ToggleControl
									label={ __( 'Show Text', 'themeisle-companion' ) }
									checked={ showRowOneText }
									onChange={ ( value ) => {
										setAttributes( {
											showRowOneText: value
										} );
									} }
								/>
								<ColorPalette
									value={ row_one.color }
									onChange={ ( value ) => {
										setAttributes( {
											row_one: {
												...row_one,
												color: value
											}
										} );
									} }
								/>
							</PanelBody>
						</PanelBody>
						<PanelBody title='Body Row Two Settings' initialOpen={false}>
							<ToggleControl
								label={ __( 'Show Row Two', 'themeisle-companion' ) }
								checked={ showRowTwo }
								onChange={ ( value ) => {
									setAttributes( {
										showRowTwo: value
									} );
								} }
							/>
							<ColorPalette
								value={ row_two.back }
								onChange={ ( value ) => {
									setAttributes( {
										row_two: {
											...row_two,
											back: value
										}
									} );
								} }
							/><hr></hr>
							<PanelBody title='Image Settings' initialOpen={false}>
								<ToggleControl
									label={ __( 'Show Image', 'themeisle-companion' ) }
									checked={ showRowTwoImage }
									onChange={ ( value ) => {
										setAttributes( {
											showRowTwoImage: value
										} );
									} }
								/>
								<hr></hr>
								{ row_two.url != '' &&
									<div className="wp-block-themeisle-blocks-body__image">
										<img src={ row_two.url } alt={ row_two.alt } />
									</div>
								}
							</PanelBody>
							<PanelBody title='Text Settings' initialOpen={false}>
								<ToggleControl
									label={ __( 'Show Text', 'themeisle-companion' ) }
									checked={ showRowTwoText }
									onChange={ ( value ) => {
										setAttributes( {
											showRowTwoText: value
										} );
									} }
								/>
								<ColorPalette
									value={ row_two.color }
									onChange={ ( value ) => {
										setAttributes( {
											row_two: {
												...row_two,
												color: value
											}
										} );
									} }
								/>
							</PanelBody>
						</PanelBody>
					</PanelBody>
				</Panel>
			</InspectorControls>
		</div>
	);
}
