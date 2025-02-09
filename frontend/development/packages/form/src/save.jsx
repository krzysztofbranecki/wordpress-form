import { useBlockProps } from '@wordpress/block-editor';
import React from 'react';
import { Form } from './components/Form.jsx';
import { Title } from './components/Title.jsx';

export default function save({ attributes }) {
    const blockProps = useBlockProps.save();
    const { formTitle } = attributes;

    return (
        <div {...blockProps} className="front-it-form">
            <Title formTitle={formTitle} />
            <Form attributes={attributes} />
        </div>
    );
}
