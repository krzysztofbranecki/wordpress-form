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
            default: __('Contact Us', 'front-it'),
        },
        submitButtonText: {
            type: 'string',
            default: __('Send Message', 'front-it'),
        },
    },
    edit,
});
