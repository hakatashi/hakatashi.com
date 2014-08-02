$(document).ready(function () {
	var width = $(document).width();
	var height = $(document).height();
	var field = SVG('field');
	var rect1 = field.rect(width, width).attr({
		fill: '#f06'
	}).center(width / 2, height / 2);
	var rect2 = field.rect(width, width).attr({
		fill: '#fff'
	}).center(width / 2, height / 2);
	var rect3 = field.rect(width, width).attr({
		fill: '#f06'
	}).center(width / 2, height / 2);
	var rect4 = field.rect(width, width).attr({
		fill: '#fff'
	}).center(width / 2, height / 2);

	rect1.animate(2000, SVG.easing.tailIn, 1300).attr({
		width: 100,
		height: 100,
		x: (width - 100) / 2,
		y: (height - 100) / 2,
	});
	rect2.animate(2000, SVG.easing.tailIn, 1200).attr({
		width: 100,
		height: 100,
		x: (width - 100) / 2,
		y: (height - 100) / 2,
	});
	rect3.animate(2000, SVG.easing.tailIn, 1100).attr({
		width: 100,
		height: 100,
		x: (width - 100) / 2,
		y: (height - 100) / 2,
	});
	rect4.animate(2000, SVG.easing.tailIn, 1000).attr({
		width: 100,
		height: 100,
		x: (width - 100) / 2,
		y: (height - 100) / 2,
	});
});

SVG.easing = {
	tailIn: function (position) {
		if (position < 0.059) {
			return BezierEasing(0.182, 0.680, 0.495, 0.861)(position / 0.059) * 0.866;
		} else {
			return BezierEasing(0.027, 0.759, 0.064, 1)((position - 0.059) / 0.941) * 0.134 + 0.866;
		}
	}
};
