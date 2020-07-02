'use strict';

{
    document.addEventListener("DOMContentLoaded", function () {

    $.getJSON("http://192.168.1.181/php/storage.php", function(result){
        var pi = result.storage[0].Use.substring(0, result.storage[0].Use.length - 1) / 100;
        var drive1 = result.storage[1].Use.substring(0, result.storage[1].Use.length - 1) / 100;

        /* Update Nav Bar */
        var mem = (result.memory[0].used.substring(0, result.memory[0].used.length - 2) * 100) / result.memory[0].total.substring(0, result.memory[0].total.length - 2);
        $('#piInfo').html('<p>CPU Temp: '+ result.cpu_temp +'º<br>Mem Used: '+result.memory[0].used.substring(0, result.memory[0].used.length - 1)+' ('+mem.toFixed(0)+'%)</p>');

        var container = d3.select('.discrete-bar-chart__container');

        if (container[0][0]) {
            (function () {
                var colors = ['#7726d3', '#00bcd4', '#03A9F4'];
                var data = [{
                    key: "Cumulative Return",
                    values: [{
                        "label": "Drive 1",
                        "value": drive1
                    }, {
                        "label": "Drive 2",
                        "value": 0.31
                    }, {
                        "label": "pi",
                        "value": pi
                    }]
                }];
                nv.addGraph(function () {
                    var chart = nv.models.discreteBarChart().x(function (d) {
                        return d.label;
                    }).y(function (d) {
                        return d.value;
                    }).yDomain([0, 1]).color(colors).margin({ "left": 60, "right": 30, "top": 10, "bottom": 10 }).showValues(true).showLegend(false).rectClass('bar').valueFormat(d3.format("%"));

                    chart.tooltip.enabled(true).hideDelay(0).headerEnabled(false).contentGenerator(function (d) {
                        if (d === null) {
                            return '';
                        }
                        d3.selectAll('.nvtooltip').classed('mdl-tooltip', true);
                        return d.data.label;
                    });

                    chart.yAxis.showMaxMin(false).ticks(10).tickFormat(d3.format("%"));

                    container.append('svg').datum(data).transition().duration(1200).call(chart);

                    nv.utils.windowResize(chart.update);

                    var color = d3.scale.ordinal().range(colors);
                    var legend = container.append('div').attr('class', 'legend').selectAll('.legend__item').data(data[0].values).enter().append('div').attr('class', 'legend__item');

                    legend.append('div').attr('class', 'legend__mark pull-left').style('background-color', function (d) {
                        return color(d.label);
                    });

                    legend.append('div').attr('class', 'legend__text').text(function (d) {
                        return d.label;
                    });

                    return chart;
                });
            })();
        }
    });

});
}