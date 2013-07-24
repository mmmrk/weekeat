var tabbed_container = {
	set_vars: function () {
		this.$container = $('.tab_container');
		this.$tabs = this.$container.find('.tab');
		this.$tab_content = this.$container.find('.tab_content');
	},
	navigate: function (tab) {
		var previous = this.$tabs.filter('.active').add(this.$tab_content.filter('.active')),
			index = tab.index(),
			next = this.$tabs.eq(index).add(this.$tab_content.eq(index));

		previous.removeClass('active');
		next.addClass('active');
		
	},
	part_toggle: function (toggler) {
		var query = toggler.data('part').toString().split('.'),
			target = this.$tab_content.eq(query[0] - 1).find('.content_part_' + query[1]);

		target.addClass('active_part').siblings('.active_part').removeClass('active_part');
	},
	init: function () {
		var self = this;

		this.set_vars();

		this.$container.on('click', '.tab', function (e) {
			e.preventDefault();

			if (!$(this).is('.active'))
				self.navigate($(this));
		});

		this.$container.on('click', '.tab_nav_link', function (e) {
			e.preventDefault();

			var query = $(this).attr('href');

			self.$tabs.find('a').filter('[href=' + query + ']').trigger('click');
		});

		this.$container.on('click', '.content_part_toggle', function (e) {
			e.preventDefault();

			self.part_toggle($(this));
		});
	}
};

$(function () {
	tabbed_container.init();
});