import { useBlockProps } from '@wordpress/block-editor';
import React from 'react';
import Controls from './controls';
import './editor.scss';
import { Form } from './components/Form.jsx';
import { Title } from './components/Title.jsx';

export default function Edit(props) {
    const blockProps = useBlockProps();
    const { attributes } = props;
    const { formTitle } = attributes;

    return [
        <Controls key="controls" {...props} />,
        <div key="render" {...blockProps} className="front-it-form">
            <Title formTitle={formTitle} />
            <Form
                attributes={attributes}
                onSubmit={(e) => e.preventDefault()}
            />
        </div>,
    ];
}
