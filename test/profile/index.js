var circlePath = function(cx, cy, r, direction) {
	var path = '';

	path += 'M';
	path += cx;
	path += ',';
	path += cy - r;

	path += 'a';
	path += r; // rx
	path += ',';
	path += r; // ry
	path += ',';
	path += '0'; // x-axis-rotation
	path += ',';
	path += '0'; // large-arc-flag
	path += ',';
	path += direction ? '1' : '0'; // sweep-flag
	path += ',';
	path += '0'; // dx
	path += ',';
	path += r * 2; // dy

	path += 'a';
	path += r; // rx
	path += ',';
	path += r; // ry
	path += ',';
	path += '0'; // x-axis-rotation
	path += ',';
	path += '0'; // large-arc-flag
	path += ',';
	path += direction ? '1' : '0'; // sweep-flag
	path += ',';
	path += '0'; // dx
	path += ',';
	path += -r * 2; // dy

	path += 'z';

	return path;
};

Snap.plugin(function (Snap, Element, Paper, global, Fragment) {
	Element.prototype.animateOnPath = function (path, duration, easing, callback) {
		var element = this;
		Snap.animate(0, path.getTotalLength(), function (value) {
			var point = path.getPointAtLength(value);
			element.attr({
				cx: point.x,
				cy: point.y
			});
		}, duration, easing, callback);
	}
});

$(document).ready(function() {
	var svg = Snap('#svg');

	var faces = [];
	var masks = [];

	(16).times(function (index) {
		var filename = (index % 8 + 1).pad(2) + '.jpg';
		var face = svg.image(filename, 0, 0, 640, 640);
		faces.push(face);

		var circle = svg.circle(0, 0, 60).attr({
			fill: 'white'
		});

		var mask = svg.group(circle);
		masks.push(mask);

		face.attr({
			mask: mask
		});

		var pathCircle = svg.path(circlePath(320, 320, 15 * index + 30, Math.random() > 0.5)).attr({
			visibility: 'hidden'
		});

		var durationCircle = 6000 * Math.random() + 4000;

		var animateCircle = function () {
			circle.animateOnPath(pathCircle, durationCircle, mina.linear, animateCircle);
		};
		animateCircle();
	});
});
