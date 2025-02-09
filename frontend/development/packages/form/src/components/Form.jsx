import { __ } from '@wordpress/i18n';
import React from 'react';

export const Form = ({ attributes, onSubmit = undefined }) => {
    const { submitButtonText } = attributes;
    return (
        <form
            className="contact-form"
            data-form-id="front-it-contact"
            onSubmit={onSubmit}
        >
            <div className="form-group">
                <label htmlFor="name">{__('Name', 'front-it')}</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder={__('Enter your name', 'front-it')}
                    className="form-control"
                    required
                />
            </div>

            <div className="form-group">
                <label htmlFor="email">{__('Email', 'front-it')}</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder={__('Enter your email', 'front-it')}
                    className="form-control"
                    required
                />
            </div>

            <div className="form-group">
                <label htmlFor="phone">{__('Phone', 'front-it')}</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    placeholder={__('Enter your phone number', 'front-it')}
                    className="form-control"
                />
            </div>

            <div className="form-group">
                <label htmlFor="message">{__('Message', 'front-it')}</label>
                <textarea
                    id="message"
                    name="message"
                    placeholder={__('Enter your message', 'front-it')}
                    className="form-control"
                    rows="4"
                    required
                />
            </div>

            <button type="submit" className="submit-button">
                {submitButtonText || __('Submit', 'front-it')}
            </button>
        </form>
    );
};
