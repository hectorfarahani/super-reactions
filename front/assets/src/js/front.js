const srea = {
	handleAction: function (e) {
		const {
			sreaPostId: id,
			sreaSlug: sreaSlug,
			nonce: n,
			reacted: currentReaction
		} = e.currentTarget.dataset;

		const $clickedButton = e.target.closest('.srea-button');
		const sreaAction = $clickedButton.dataset.sreaAction;
		const $parent = e.target.closest('.srea-template');

		if (!sreaAction || !id) {
			return false;
		}

		const counterElement = jQuery(e.target).siblings('.srea-template-count');
		const currentReactionElement = jQuery($parent)
			.find(`[data-srea-action="${currentReaction}"]`)
			.siblings('.srea-template-count');

		counterElement.html(srea.getLoader());
		$clickedButton.disabled = true;

		const data = {
			action: 'srea_handle_post_reactions',
			current: currentReaction,
			srea_action: sreaAction,
			slug: sreaSlug,
			post_id: id,
			n: n,
		};

		jQuery.post(
			SREA.ajaxurl,
			data,
			function (res) {
				if (res.success) {
					$parent.setAttribute('data-reacted', sreaAction);
					currentReactionElement.html(res.data.old_count);
					counterElement.html(res.data.count);
					$clickedButton.disabled = false;
				}
			}
		);

	},
	getLoader: function () {
		const loader = document.createElement('div');
		loader.className = 'srea-loader';
		return loader;

	}
};

window.addEventListener(
	'DOMContentLoaded',
	function () {

		const sreaElement = document.querySelectorAll('.srea-template');

		sreaElement.forEach(
			function (el, i) {
				el.addEventListener('click', srea.handleAction);
			}
		);

	}
);

