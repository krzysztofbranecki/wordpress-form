import { useBlockProps } from '@wordpress/block-editor';
import React from 'react';

export default function Save({ attributes }) {
    const blockProps = useBlockProps.save();

    return (
        <div {...blockProps}>
            <p className="test">Saved block goes here</p>
        </div>
    );
}
