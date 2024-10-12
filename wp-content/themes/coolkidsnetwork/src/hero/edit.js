/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls, URLInput, BlockControls, AlignmentToolbar, withColors, PanelColorSettings, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { useSelect } from '@wordpress/data';

import './index.scss';
/**
 * Edit function for the Hero block
 *
 * @param {Object} props               Block props.
 * @param {Object} props.attributes    Block attributes.
 * @param {Function} props.setAttributes Function to set block attributes.
 * @return {WPElement} Element to render.
 */
function Edit({ attributes, setAttributes, backgroundColor, setBackgroundColor }) {
	const {
		title,
		description,
		content,
		alignment,
		loggedOutButtons,
		loggedInButtons,
		backgroundImage
	} = attributes;

	// Fetch the site logo
	const siteLogo = useSelect((select) => {
		const siteSettings = select('core').getEntityRecord('root', 'site');
		return siteSettings?.site_logo;
	}, []);

	// Set up block props with dynamic class and styles
	const blockProps = useBlockProps({
		className: `hero-block alignment-${alignment} alignfull`,
		style: {
			backgroundColor: backgroundColor.color,
			backgroundImage: backgroundImage ? `url(${backgroundImage})` : 'none',
			backgroundSize: 'cover',
			backgroundPosition: 'center',
			position: 'relative'
		}
	});

	const overlayStyle = {
		position: 'absolute',
		top: 0,
		left: 0,
		right: 0,
		bottom: 0,
		background: 'linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.8) 100%)',
		zIndex: 1
	};

	const contentStyle = {
		position: 'relative',
		zIndex: 2
	};

	const updateButtonAt = (index, buttonType, newProps) => {
		const updatedButtons = [...attributes[buttonType]];
		updatedButtons[index] = { ...updatedButtons[index], ...newProps };
		setAttributes({ [buttonType]: updatedButtons });
	};

	const addButton = (buttonType) => {
		if (attributes[buttonType].length < 2) {
			const updatedButtons = [...attributes[buttonType], { text: '', link: '', linkType: 'page' }];
			setAttributes({ [buttonType]: updatedButtons });
		}
	};

	const removeButton = (buttonType, index) => {
		const updatedButtons = attributes[buttonType].filter((_, i) => i !== index);
		setAttributes({ [buttonType]: updatedButtons });
	};

	const renderButtonControls = (buttonType, buttonTypeLabel) => (
		<PanelBody title={__(buttonTypeLabel, 'coolkidsnetwork')} initialOpen={false}>
			{attributes[buttonType].map((button, index) => (
				<div key={index}>
					<TextControl
						label={__(`Button ${index + 1} Text`, 'coolkidsnetwork')}
						value={button.text}
						onChange={(value) => updateButtonAt(index, buttonType, { text: value })}
					/>
					<SelectControl
						label={__('Link Type', 'coolkidsnetwork')}
						value={button.linkType}
						options={[
							{ label: 'Page', value: 'page' },
							{ label: 'Custom URL', value: 'custom' },
						]}
						onChange={(value) => updateButtonAt(index, buttonType, { linkType: value })}
					/>
					<URLInput
						label={__('Button Link', 'coolkidsnetwork')}
						value={button.link}
						onChange={(value) => updateButtonAt(index, buttonType, { link: value })}
						disableSuggestions={button.linkType === 'custom'}
					/>
					<Button isDestructive onClick={() => removeButton(buttonType, index)}>
						{__('Remove Button', 'coolkidsnetwork')}
					</Button>
				</div>
			))}
			{attributes[buttonType].length < 2 && (
				<Button isPrimary onClick={() => addButton(buttonType)}>
					{__('Add Button', 'coolkidsnetwork')}
				</Button>
			)}
		</PanelBody>
	);

	return (
		<>
			<InspectorControls>
				<PanelColorSettings
					title={__('Color settings', 'coolkidsnetwork')}
					colorSettings={[
						{
							value: backgroundColor.color,
							onChange: setBackgroundColor,
							label: __('Background color', 'coolkidsnetwork'),
						},
					]}
				/>
				<PanelBody title={__('Background Image', 'coolkidsnetwork')}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => setAttributes({ backgroundImage: media.url })}
							allowedTypes={['image']}
							value={backgroundImage}
							render={({ open }) => (
								<Button
									onClick={open}
									isPrimary={!backgroundImage}
								>
									{backgroundImage ? __('Change Background Image', 'coolkidsnetwork') : __('Add Background Image', 'coolkidsnetwork')}
								</Button>
							)}
						/>
					</MediaUploadCheck>
					{backgroundImage && (
						<Button
							onClick={() => setAttributes({ backgroundImage: undefined })}
							isDestructive
						>
							{__('Remove Background Image', 'coolkidsnetwork')}
						</Button>
					)}
				</PanelBody>
				{renderButtonControls('loggedOutButtons', 'Logged Out User Buttons')}
				{renderButtonControls('loggedInButtons', 'Logged In User Buttons')}
			</InspectorControls>
			<BlockControls>
				<AlignmentToolbar
					value={alignment}
					onChange={(newAlignment) => setAttributes({ alignment: newAlignment })}
				/>
			</BlockControls>
			<section {...blockProps}>
				<div style={overlayStyle}></div>
				<div style={contentStyle}>
					{siteLogo && (
						<div className="hero-logo">
							<img src={siteLogo} alt="Site Logo" />
						</div>
					)}
					<RichText
						tagName="h2"
						value={title}
						onChange={(newTitle) => setAttributes({ title: newTitle })}
						placeholder={__('Enter title here', 'coolkidsnetwork')}
					/>
					<RichText
						tagName="p"
						value={description}
						onChange={(newDescription) => setAttributes({ description: newDescription })}
						placeholder={__('Enter description here', 'coolkidsnetwork')}
					/>
					<RichText
						tagName="div"
						value={content}
						onChange={(newContent) => setAttributes({ content: newContent })}
						placeholder={__('Enter additional content here', 'coolkidsnetwork')}
					/>
					<div className="hero-buttons">
						{loggedOutButtons.map((button, index) => (
							<RichText key={index}
								tagName="a"
								className="button wp-block-button__link"
								value={button.text}
								onChange={(newText) => updateButtonAt(index, 'loggedOutButtons', { text: newText })}
								placeholder={__('Button text', 'coolkidsnetwork')}
								href={button.link}
							/>
						))}
					</div>
					<div className="hero-logged-in-buttons">
						{loggedInButtons.map((button, index) => (
							<RichText key={index}
								tagName="a"
								className={`button wp-block-button__link ${index === 1 ? 'secondary' : ''}`}
								value={button.text}
								onChange={(newText) => updateButtonAt(index, 'loggedInButtons', { text: newText })}
								placeholder={__('Button text', 'coolkidsnetwork')}
								href={button.link}
							/>
						))}
					</div>
				</div>
			</section>

			<ServerSideRender
				block="cool-kids-network/hero"
				attributes={attributes}
			/>
		</>
	);
}

export default withColors('backgroundColor')(Edit);