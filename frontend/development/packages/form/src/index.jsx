import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';
import './style.scss';

registerBlockType('front-it/form', {
    description: __(
        'Add a customizable contact form to your page.',
        'front-it',
    ),
    icon: 'email',
    attributes: {
        formTitle: {
            type: 'string',
            default: '',
        },
        submitButtonText: {
            type: 'string',
            default: '',
        },
    },
    edit,
});
