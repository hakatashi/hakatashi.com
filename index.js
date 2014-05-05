Number.prototype.toRadians = function () {
    return this * (Math.PI / 180);
}

Number.prototype.mod = function (n) {
    return ((this % n) + n) % n;
}

$(document).ready(function () {
    var viewBox = {
        height: 720,
        width: 1080
    };
    var paper = Raphael("wrap");
    paper.setViewBox(0, 0, viewBox.width, viewBox.height, true);

    // http://stackoverflow.com/questions/11176396/
    var svg = document.querySelector("svg");
    svg.removeAttribute("width");
    svg.removeAttribute("height");

    paper.customAttributes.sector = function (centerX, centerY, outerRadius, innerRadius, startingAngle, angle) {
        var path = [];

        // confirm that...
        // * 0 <= startingAngle < 360
        // * 0 <= angle <= 360
        if (Math.abs(angle) >= 360) angle = 360;

        startingAngle = startingAngle.mod(360);

        if (angle >= 0) {
            var endingAngle = startingAngle + angle;
        } else {
            var endingAngle = startingAngle;
            startingAngle += angle;

            if (startingAngle < 0) {
                startingAngle += 360;
                endingAngle += 360;
            }
        }

        var outer = {
            start: {
                x: centerX + outerRadius * Math.sin(startingAngle.toRadians()),
                y: centerY - outerRadius * Math.cos(startingAngle.toRadians())
            },
            top: {
                x: centerX,
                y: centerY - outerRadius
            },
            bottom: {
                x: centerX,
                y: centerY + outerRadius
            },
            end: {
                x: centerX + outerRadius * Math.sin(endingAngle.toRadians()),
                y: centerY - outerRadius * Math.cos(endingAngle.toRadians())
            }
        };

        var inner = {
            start: {
                x: centerX + innerRadius * Math.sin(startingAngle.toRadians()),
                y: centerY - innerRadius * Math.cos(startingAngle.toRadians())
            },
            top: {
                x: centerX,
                y: centerY - innerRadius
            },
            bottom: {
                x: centerX,
                y: centerY + innerRadius
            },
            end: {
                x: centerX + innerRadius * Math.sin(endingAngle.toRadians()),
                y: centerY - innerRadius * Math.cos(endingAngle.toRadians())
            }
        };

        // draw outer.start => outer.end
        path.push(['M', outer.start.x, outer.start.y]);
        if (startingAngle < 180 && 180 < endingAngle) {
            path.push(['A', outerRadius, outerRadius, 0, 0, 1, outer.bottom.x, outer.bottom.y]);
        }
        if (startingAngle < 360 && 360 < endingAngle) {
            path.push(['A', outerRadius, outerRadius, 0, 0, 1, outer.top.x, outer.top.y]);
        }
        if (startingAngle < 540 && 540 < endingAngle) {
            path.push(['A', outerRadius, outerRadius, 0, 0, 1, outer.bottom.x, outer.bottom.y]);
        }
        path.push(['A', outerRadius, outerRadius, 0, 0, 1, outer.end.x, outer.end.y]);

        // draw outer.end => inner.end
        path.push(['L', inner.end.x, inner.end.y]);
            
        // draw inner.end => inner.start
        if (startingAngle < 540 && 540 < endingAngle) {
            path.push(['A', innerRadius, innerRadius, 0, 0, 0, inner.bottom.x, inner.bottom.y]);
        }
        if (startingAngle < 360 && 360 < endingAngle) {
            path.push(['A', innerRadius, innerRadius, 0, 0, 0, inner.top.x, inner.top.y]);
        }
        if (startingAngle < 180 && 180 < endingAngle) {
            path.push(['A', innerRadius, innerRadius, 0, 0, 0, inner.bottom.x, inner.bottom.y]);
        }
        path.push(['A', innerRadius, innerRadius, 0, 0, 0, inner.start.x, inner.start.y]);

        // draw inner.start => outer.start
        path.push(['Z']);

        return {path: path};
    };

    var animation = Raphael.animation({
        sector: [540, 360, 150, 0, 0, 360]
    }, 1000, 'elastic');

    paper.path().attr({
        'stroke-width': 0,
        fill: 'black',
        sector: [540, 360, 0, 0, 0, 0]
    }).animate(animation.delay(500));
});