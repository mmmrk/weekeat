var tab_container = {
	set_vars: function () {
		this.$container = $('.tab_container');
		this.$tabs = this.$container.find('.tab');
		this.$tab_content = this.$container.find('.tab_content');
		this.$nav_buttons = this.$container.find('.tab_container_footer .button');
		this.first = true;
		this.last = false;
	},
	navigate: function (tab) {
		var previous = this.$tabs.filter('.active').add(this.$tab_content.filter('.active')),
			index = tab.index(),
			next = this.$tabs.eq(index).add(this.$tab_content.eq(index)),
			new_first = index === 0,
			new_last = index === (this.$tabs.length - 1);

		previous.removeClass('active');
		next.addClass('active');

		this.footer_nav_toggle(new_first, new_last);
	},
	footer_nav_toggle: function (new_first, new_last) {
		if (new_first !== this.first) {
			this.$nav_buttons.filter('.prev').prop('disabled', new_first);
			this.first = new_first;
		}

		if (new_last !== this.last) {
			this.$nav_buttons.filter('.next').prop('disabled', new_last);
			this.$nav_buttons.filter('.confirm').prop('disabled', !new_last);
			this.last = new_last;
		}
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

		this.$container.on('click', '.next, .prev', function (e) {
			e.preventDefault();

			console.log('button click!');

			if ($(e.target).is('.prev'))
				self.$tabs.filter('.active').prev().trigger('click');
			if ($(e.target).is('.next'))
				self.$tabs.filter('.active').next().trigger('click');
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
},
meal_form = {
	set_vars: function () {
		this.$dish_id = $('#dish_id_value');
		this.$dish_list = $('#dish_list');
	},
	select_dish_from_list: function (id) {
		var previous = this.$dish_id.val();
		this.$dish_id.val((previous != id) ? id : '');
	},
	init: function () {
		this.set_vars();
		this.$dish_list.on('click', '[data-dish-id]', function () {
			meal_form.select_dish_from_list($(this).data('dish-id'));
			$(this).toggleClass('selected').siblings('.selected').removeClass('selected');
		});
	}
};

$(function () {
	tab_container.init();
	meal_form.init();
});
