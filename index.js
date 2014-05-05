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

    paper.circle(
        540,
        360,
        200
    ).attr({
        fill: "black",
        opacity: 0.5
    });
});