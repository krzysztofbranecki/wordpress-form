import { BlockControls, InspectorControls } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { Toolbar, PanelBody, TextControl } from '@wordpress/components';
import React from 'react';

const Controls = (props) => {
    const { attributes, setAttributes } = props;
    const { formTitle, showPhone, showMessage, submitButtonText } = attributes;

    return (
        <>
            <BlockControls>
                <Toolbar label="Options">
                    {/* Block toolbar options if needed */}
                </Toolbar>
            </BlockControls>
            <InspectorControls>
                <PanelBody title={__('Form Settings', 'front-it')}>
                    <TextControl
                        label={__('Form Title', 'front-it')}
                        value={formTitle}
                        onChange={(value) =>
                            setAttributes({ formTitle: value })
                        }
                    />
                    <TextControl
                        label={__('Submit Button Text', 'front-it')}
                        value={submitButtonText}
                        onChange={(value) =>
                            setAttributes({ submitButtonText: value })
                        }
                    />
                </PanelBody>
            </InspectorControls>
        </>
    );
};

export default Controls;
