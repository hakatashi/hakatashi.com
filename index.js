Number.prototype.toRadians = function () {
    return this * (Math.PI / 180);
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

        startingAngle %= 360;

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
                x: centerX + outerRadius * Math.cos(startingAngle.toRadians()),
                y: centerY - outerRadius * Math.sin(startingAngle.toRadians())
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
                x: centerX + outerRadius * Math.cos(endingAngle.toRadians()),
                y: centerY - outerRadius * Math.sin(endingAngle.toRadians())
            }
        };

        var inner = {
            start: {
                x: centerX + innerRadius * Math.cos(startingAngle.toRadians()),
                y: centerY - innerRadius * Math.sin(startingAngle.toRadians())
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
                x: centerX + innerRadius * Math.cos(endingAngle.toRadians()),
                y: centerY - innerRadius * Math.sin(endingAngle.toRadians())
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

    paper.path().attr({
        stroke: 'black',
        'stroke-width': 1,
        sector: [100, 100, 100, 80, 30, -180]
    });
});