import { BlockControls, InspectorControls } from '@wordpress/block-editor';
import { Toolbar, PanelBody } from '@wordpress/components';
import React from 'react';

const Controls = (props) => {
    const { attributes, setAttributes } = props;

    const changeValue = (attribute, value) => {
        setAttributes({ [attribute]: value });
    };
    return (
        <>
            <BlockControls>
                <Toolbar label="Options">
                    {/* Here you can add options to the block Toolbar */}
                </Toolbar>
            </BlockControls>
            <InspectorControls>
                <PanelBody title="Sidebar title">
                    {/* Here is some options in sidebar. */}
                </PanelBody>
            </InspectorControls>
        </>
    );
};

export default Controls;
