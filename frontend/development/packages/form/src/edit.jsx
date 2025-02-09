import { useBlockProps } from '@wordpress/block-editor';
import React from 'react';
import Controls from './controls';
import './editor.scss';

export default function Edit(props) {
    const blockProps = useBlockProps();
    const { attributes, setAttributes, isSelected } = props;
    return [
        <div id="test" key="render" {...blockProps}>
            <Controls {...props} />
            <p className="test">Block goes here</p>
        </div>,
    ];
}
