/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import './index.scss';
/**
 * Edit function for the Hero block
 *
 * @param {Object} props               Block props.
 * @param {Object} props.attributes    Block attributes.
 * @param {Function} props.setAttributes Function to set block attributes.
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {

	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<h2>{__('Other Characters', 'cool-kids-network')}</h2>

			<ServerSideRender
				block="cool-kids-network/character-list"
				attributes={attributes}
			/>
		</div>
	);

}