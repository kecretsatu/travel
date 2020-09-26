
$.fn.buildGraph = function(type, title, data, xkey, ykeys, labels, color){
	if(type == "bar"){
		Morris.Bar({
			element: this,
			barSizeRatio:0.4,
			barGap:1,
			data: data,
			xkey: xkey,
			ykeys: ykeys,
			resize: true,
			labels: labels,
			barColors: function (row, series, type) {
				return color;
			}
		});
	}
	if(type == "line"){
		Morris.Line({
			element: this,
			data: data,
			xkey: xkey,
			ykeys: ykeys,
			labels: labels,
			parseTime: false,
			lineColors: color,
			pointStrokeColors: color,
			pointFillColors: color
		});
	}
	if(type == "area"){
		Morris.Area({
			element: this,
			data: data,
			xkey: xkey,
			ykeys: ykeys,
			labels: labels,
			parseTime: false,
			lineColors: color,
			pointStrokeColors: color,
			pointFillColors: color
		});
	}
}