import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import edit from './edit';
import './style.scss';

registerBlockType('front-it/entries-list', {
    description: __(
        'Display form entries list (admin only)',
        'front-it',
    ),
    icon: 'list-view',
    "attributes": {
        "entriesPerPage": {
            "type": "number",
            "default": 10
        }
    },
    edit,
});
