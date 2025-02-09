import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import React from 'react';
import './editor.scss';

export default function Edit(props) {
    const blockProps = useBlockProps();

    return [
        <div key="render" {...blockProps} className="front-it-form">
            <ServerSideRender block="front-it/entries-list" />
        </div>,
    ];
}
