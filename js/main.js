var tab_container = {
	set_vars: function (container) {
		this.$container = container;
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
	init: function (container) {
		var self = this;

		if (!container.length)
			return;

		this.set_vars(container);

		this.$container.on('click', '.tab', function (e) {
			e.preventDefault();

			if (!$(this).is('.active'))
				self.navigate($(this));
		});

		this.$container.on('click', '.next, .prev', function (e) {
			e.preventDefault();

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
	set_vars: function (form) {
		this.$form = form;
		this.$dish_id = $('#dish_id_value');
		this.$dish_list = $('#dish_list');
		this.$previews = $(
			'<li class="meal_item preview">'+
			'	<span class="meal">Adding meal</span>'+
			'	<h4 class="dish"></h4>'+
			'	<p class="dish_tags"></p>'+
			'	<p class="dish_description"></p>'+
			'</li>'
		).appendTo('.meal_list').add('.meal_preview');
		this.$input_methods = this.$form.find('.input_method');
	},
	update_previews: function () {
		var source,
			source_data = {},
			target = {};

		target.name = this.$previews.find('.dish');
		target.tags = this.$previews.find('.dish_tags');
		target.description = this.$previews.find('.dish_description');

		source = this.$input_methods.filter('.active_part');
		if (source.is('.content_part_1')) {
			var sources = source.find('.selected').children();
			source_data.name = sources.eq(1).text();
			source_data.tags = sources.eq(4).text();
			source_data.description = sources.eq(1).find('.hidden_description').val();
		}
		else {
			var sources = source.find('.input_group');
			source_data.name = sources.eq(0).find('input').val();
			source_data.tags = (function () {
				var output = ''
				sources.eq(4).find(':checked').each(function (index) {
					var label = $(this).next().text();
					output += (index) ? ', ' + label : label;
				});
				return output;
			}());
			source_data.description = sources.eq(1).find('textarea').val();
		}

		target.name.text(source_data.name);
		target.tags.text(source_data.tags);
		target.description.text(source_data.description);
	},
	select_dish_from_list: function (id) {
		var previousVal = this.$dish_id.val(),
			newVal = (previousVal != id) ? id : '';
		this.$dish_id.val(newVal);
		this.update_previews();
	},
	init: function (form) {
		if (!form.length)
			return;
		this.set_vars(form);
		this.$dish_list.on('click', '[data-dish-id]', function () {
			$(this).toggleClass('selected').siblings('.selected').removeClass('selected');
			meal_form.select_dish_from_list($(this).data('dish-id'));
		});
		this.$input_methods.filter('.content_part_2').on('change', function () {
			meal_form.update_previews();
		});
	}
},
input_suggestions = {
	init: function (input_group) {
		if (!input_group.length)
			return;
		var input = input_group.find(':input'),
			suggestions = input_group.find('.suggestion_list');
		input.on('focus blur', function (event) {
			var state = event.type === 'focus';
			setTimeout(function () {
				suggestions.toggle(state);
			}, (state) ? 0 : 200);
		});
		suggestions.on('click', function (event) {
			input.val(event.target.textContent);
		});
	}
},
shopping_list = {
	init: function (lists) {
		if (lists.length) {
			lists.on('click', function (event) {
				if (event.target.nodeName === 'LI')
					return;
				$(this).toggleClass('expanded');
			});
		}
	}
};

$(function () {
	tab_container.init($('.tab_container'));
	meal_form.init($('#add_meal_form'));
	input_suggestions.init($('.with_suggestions'));
	shopping_list.init($('.meal_shopping_list'));
});
