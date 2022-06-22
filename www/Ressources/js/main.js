$(document).ready(function() {
	// S'il existe des éléments avec la classe .slider
	if ($('.slider').length) {
		$('.slider').each(function(index) {
			sliderInit($(this));
		});
		
		// Ajout d'un autoplay sur le premier slider
		var premierSlider = $('.slider')[0];
		var interval = setInterval(function(){
			next($(premierSlider));
		}, 4000);
	}

});

function sliderInit(element) {
	let container = $('<div/>');
	container.addClass('slides-container');
	container.html(element.html());
	element.html(container);
	container.find('img').addClass('slide');

	let nav = $('<nav/>')
	.append('<button class="prev"></button>')
	.append('<button class="next"></button>');
	element.append(nav);

	element.attr('data-currentSlide', 0);
	element.find('.prev').click(function() {
		prev(element);
	});
	element.find('.next').click(function() {
		next(element);
	});
}

function prev(slider) {
	let attrValue = Number(slider.attr('data-currentSlide'));

	slider.attr('data-currentSlide', attrValue-1);

	slide(slider);
}

function next(slider) {
	let attrValue = Number(slider.attr('data-currentSlide'));

	slider.attr('data-currentSlide', attrValue+1);

	slide(slider);
}

function slide(slider) {
	let attrValue = Number(slider.attr('data-currentSlide'))
	let leftValue = attrValue * -100;
	let container = slider.children('.slides-container');
	var imageTotal = container.children('img').length;

	disableNav(slider);
	// Si on dépasse la dernière image :
	//	- cloner la première image et mettre le clone à la fin du container
	//	- écouter la fin de la transition css :
	//		- enlever la transition du container
	// 		- rembobiner le container vers la première image
	// 		- supprimer le clone
	// 		- remettre la transition du container
	// let slidesCount = slider.find('img').length();
	if (attrValue == imageTotal) {
		let clone = container.children('img:first-child').clone();
		container.append(clone);

		container.on('transitionend', function() {
			container.off('transitionend');

			let transition = container.css('transition');

			container.css('transition', 'none');
			container.css('left', 0);
			container.children('img:last-child').remove();
			slider.attr('data-currentSlide', 0);
			setTimeout(function() {
				container.css('transition', transition);
			}, 20);
		});
	} else if (attrValue == -1) {
		let clone = container.children('img:last-child').clone();
		clone.css({
			'position': 'absolute',
			'top': 0,
			'left': 0,
			'transform': 'translateX(-100%)'
		});
		container.prepend(clone);
		container.on('transitionend', function() {
			container.off('transitionend');
			container.css('transition', 'none');
			container.css('left', (imageTotal-1)*-100 + '%');
			container.children('img:first-child').remove();
			slider.attr('data-currentSlide', imageTotal-1);
			setTimeout(function() {
				container.css('transition', 'left 1s');
			}, 20);
		});
	}

	container.css('left', leftValue+'%');
	container.on('transitionend', function(){
		$(this).off('transitionend');
		enableNav(slider);
	});
}

function enableNav(slider) {
	slider.find("nav button").removeAttr("disabled");
}

function disableNav(slider) {
	slider.find("nav button").attr("disabled", "disabled");
}
