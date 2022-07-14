import { registerBlockType } from '@wordpress/blocks'; 
import data from './block.json';

import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { SelectControl, Panel, PanelBody, Card, CardBody } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
 
registerBlockType( data.name, {
    "title": data.title,
    edit: function( props ) {
        if( !props.attributes.contactform ) {
            apiFetch({
                url: '/wp-json/wp/v2/contactform'
            }).then( contactform => {
                props.setAttributes( {
                    contactform: contactform.map( post => {
                        return {
                            value: post.id,
                            label: post.title.rendered,
                            //return meta field name
                            meta: post.meta.map( meta => {
                                return {
                                    value: meta.id,
                                    label: meta.title.rendered
                                }
                            })
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
        //console meta values
        const meta = props.attributes.contactform[0].meta;
        console.log(meta);
        return (
            <div>
                <Card>
                    <CardBody>

                    </CardBody>
                </Card>
                <InspectorControls>
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
    },
    save: function() {
        return (null);
    },
} );