/*
Template Name: Clivax - Admin & Dashboard Template
Author: Codebucks

File: Round slider Init Js File
*/

// Slider Types

$("#default-slider").roundSlider({
    radius: 80,
    sliderType: "default",
    value: 42,
    
});

$("#min-range").roundSlider({
    radius: 80,
    sliderType: "min-range",
    value: 46
});

$("#range").roundSlider({
    radius: 80,
    sliderType: "range",
    value: 54
});


// Slider circle shapes

$("#quarter-top-left").roundSlider({
    radius: 80,
    circleShape: "quarter-top-left",
    sliderType: "min-range",
    showTooltip: false,
    value: 50
});

$("#half-top").roundSlider({
    radius: 80,
    circleShape: "half-top",
    sliderType: "min-range",
    showTooltip: false,
    value: 50
});


// example

$("#square-roundslider").roundSlider({
    radius: 80,
    width: 14,
    handleSize: "24,12",
    handleShape: "square",
    sliderType: "min-range",
    value: 38
});

$("#handleshape-dot").roundSlider({
    radius: 80,
    width: 8,
    handleSize: "+16",
    handleShape: "dot",
    sliderType: "min-range",
    value: 65
});

$("#border-roundslider").roundSlider({
    radius: 80,
    width: 10,
    handleSize: "+10",
    sliderType: "range",
    value: "10,60"
});


$("#outer-border").roundSlider({
    radius: 80,
    width: 14,
    handleSize: "+0",
    sliderType: "range",
    value: "5,55"
});

$("#outer-border-dot").roundSlider({
    radius: 80,
    width: 0,
    handleSize: 16,
    handleShape: "square",
    value: 60
});


$("#handle-arrow").roundSlider({
    sliderType: "min-range",
    radius: 105,
    width: 16,
    value: 75,
    handleSize: 0,
    handleShape: "square",
    circleShape: "half-top",
    showTooltip: false,
});

$("#handle-arrow-dashed").roundSlider({
    sliderType: "min-range",
    radius: 105,
    width: 16,
    value: 75,
    handleSize: 0,
    handleShape: "square",
    circleShape: "half-top",
    showTooltip: false,
});