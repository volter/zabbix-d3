jQuery(document).ready(function() {
    //d3.select("#graph_cont1").append("svg").attr("width", 50).attr("height", 50).append("circle").attr("cx", 25).attr("cy", 25).attr("r", 25).style("fill", "purple");

    // define dimensions of graph

    var m = [80, 80, 80, 80]; // margins
    var result = [];
    //var w = graphData["fullSizeX"]; // width
    //var h = graphData["fullSizeY"]; // height
    //for(var i in graphData)
    //	result.push(graphData[i]);


    var lineData = [];
    for (var i in graphData['data']) {
        for (x in graphData['data'][i][0]['avg']) {
            var xvalue = graphData['data'][i][0]['avg'][x];
            var yvalue = graphData['data'][i][0]['clock'][x];
            lineData.push({'x': xvalue, 'y': yvalue});
        }
//        console.log(i);
        //data = graphData['data'][i][0]['avg'];
        // var x = d3.scale.linear().domain([0, data.length]).range([0, w]);
        //var y = d3.scale.linear().domain([d3.min(data), d3.max(data)]).range([h, 0]);

    }
    console.log(lineData);

    /*result.forEach(function(itemArray){
     console.log(itemArray);
     });
     */
    // create a line function that can convert data[] into x and y points

    /*  var line = d3.svg.line()
     .x(function(d) {
     return d.x;
     })
     .y(function(d) {
     return d.y;
     })
     .interpolate("linear");
     
     // Add an SVG element with the desired dimensions and margin.
     var graph = d3.select("#graph_cont1").append("svg:svg")
     .attr("width", 800)
     .attr("height", 600);
     
     var lineGraph = graph.append("path")
     .attr("d", line(data))
     .attr("stroke", "blue")
     .attr("stroke-width", 2)
     .attr("fill", "none");
     console.log("Done"); 
     */

    var lineFunction = d3.svg.line()
            .x(function(d) {
                return d.x;
            })
            .y(function(d) {
                return d.y;
            })
            .interpolate("linear");

//The SVG Container
    var svgContainer = d3.select("#graph_cont1").append("svg")
            .attr("width", 400)
            .attr("height", 400);

//The line SVG Path we draw
    var lineGraph = svgContainer.append("path")
            .attr("d", lineFunction(lineData))
            .attr("stroke", "blue")
            .attr("stroke-width", 2)
            .attr("fill", "none");
    console.log("Done");
});