import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, RichText } from '@wordpress/block-editor';
import { SelectControl, Panel, PanelBody } from '@wordpress/components';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import block from './block.json';

class mySelectPosts extends Component {
    static getInitialState(selectedPost) {
        return {
            metablocks: [],
            selectedPost: selectedPost,
            metablock: {},
        };
    }
    constructor( ) {
        super( ...arguments );
        this.state = this.constructor.getInitialState(this.props.attributes.selectedPost);
        this.getOptions = this.getOptions.bind(this);
        this.getOptions();
        this.onChangeSelectPost = this.onChangeSelectPost.bind(this);
    }
    onChangeSelectPost(value) {
        const metablock = this.state.metablocks.find( (item) => 
        {
            return item.id == parseInt( value ) 
        } );
        this.setState({ selectedPost: parseInt( value ), metablock });
        this.props.setAttributes({ 
            selectedPost: parseInt( value ),
            postid: value,
            title: metablock.title.rendered,
            content: metablock.content.rendered,
        });
    }
    getOptions() {
        return apiFetch({
            path: '/wp/v2/metablocks',
        }).then(metablocks => {
            if(metablocks && 0 !== this.state.selectedPost) {
                const metablock = metablocks.find((item) => {item.id == this.state.selectedPost});
                this.setState({ metablock, metablocks});
            } else {
                this.setState({ metablocks });
            }
        });
    }
    render() {
        let options = [{value:0, label: __('Select a post')}];
        let output = ( 'Loading...' );
        this.props.className += 'Loading';
        if(this.state.metablocks.length > 0) {
            const loading = __('We have %d posts.');
            output = loading.replace ( '%d', this.state.metablocks.length );
            this.state.metablocks.forEach((metablock) => {
                options.push({
                    value: metablock.id, 
                    label: metablock.title.rendered
                });
            });
        } else {
            output = ( 'No posts found.' );
        }
        if(this.state.metablock.hasOwnProperty('title')) {
            output = <div className="metablock">
                    <h2 dangerouslySetInnerHTML={{ __html: this.state.metablock.title.rendered }}></h2>
                    <p dangerouslySetInnerHTML={ { __html: this.state.metablock.content.rendered } }></p>
                </div>;
            this.props.className += 'Loaded';
        } else {
            this.props.className += 'Nothing to load.';
        }
        return [
            !!this.props.isSelected && (
                <div>
                    <InspectorControls key="inspector">
                        <Panel>
                            <PanelBody title={ __( 'Select a post' ) }>
                                <SelectControl
                                    onChange={this.onChangeSelectPost}
                                    value={this.props.attributes.selectedPost}
                                    label={ __('Select Post') }
                                    options={options}
                                />
                            </PanelBody>
                        </Panel>
                    </InspectorControls>
                </div>
            ), 
            <div className={this.props.className}>
                <RichText
                    tagName="h4"
                    value={"Click me to edit."}
                />
                {output}
            </div>
        ]
    }
}

registerBlockType( block.name, {
    title: block.title,
	edit: mySelectPosts,
	save: function(){
        return null;
    },
} );