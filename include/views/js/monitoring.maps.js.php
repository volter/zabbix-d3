<script type="text/javascript">
	jQuery(document).ready(function(){
		// Map properties
		var width = <?php echo $this->data['map']['width'];?>;
		var height = <?php echo $this->data['map']['height'];?>;

		// Inject container into table
        // TODO: Background color needs to be tested across implementations
		var svg = d3.select('#svg-container').select('td').append('svg')
			.attr('width',width)
			.attr('height',height)
			.attr('viewBox','0,0,'+width+','+height)
            .style('background-color','white');

		svg.call(d3.behavior.zoom().on("zoom", redraw));

		function redraw() {
			d3.select("#viewport").attr("transform",
				"translate(" + d3.event.translate + ")" + " scale(" + d3.event.scale + ")");
		}

		// Jsonize necessary data
		var nodes = <?php echo CJs::encodeJson($this->data['nodes']);?>;
		var links = <?php echo CJs::encodeJson($this->data['links']);?>;

		// Bind data to a forced layout
		var force = d3.layout.force()
			.nodes(nodes)
			.links(links)
			.start();

		// Create a group comprising the whole graph for zooming and paning
		var viewport = svg.append("g")
			.attr("id", "viewport");

		var link = viewport.selectAll("line")
			.data(links)
			.enter().append("g");

		var node = viewport.selectAll(".node")
			.data(nodes)
			.enter().append("g")
			.classed("node", true);

		// Apply line-style
		link.append("line")
			.attr("class", function(d) {
				var styleClass;
				switch(d.drawtype)
				{
                case "<?php echo MAP_LINK_DRAWTYPE_LINE ?>":
					styleClass = 'normal';
					break;
                case "<?php echo MAP_LINK_DRAWTYPE_BOLD_LINE ?>":
					styleClass = 'bold';
					break;
                case "<?php echo MAP_LINK_DRAWTYPE_DOT ?>":
					styleClass = 'dot';
					break;
                case "<?php echo MAP_LINK_DRAWTYPE_DASHED_LINE ?>":
					styleClass = 'dashed';
					break;
				}
				return 'link ' + styleClass;
			})
			.attr("source", function(d) { return d.source })
			.attr("target", function(d) { return d.target })
			.attr("x1", function(d) { return d.source.x; })
			.attr("y1", function(d) { return d.source.y; })
			.attr("x2", function(d) { return d.target.x; })
			.attr("y2", function(d) { return d.target.y; })
			.style("stroke", function(d) { return '#'+d.color;});

        // White label outline
		link.append("text")
			.attr("text-anchor", "middle")
			.attr("alignment-baseline", "central")
			.style("fill", function(d) { return '#'+d.color;})
			.attr("x", function(d) { return ((Number(d.source.x) + Number(d.target.x))/2); })
			.attr("y", function(d) { return ((Number(d.source.y) + Number(d.target.y))/2); })
            .classed("shadow", true)
			.text( function(d) { return d.label });

        // Actual link label
		link.append("text")
			.attr("text-anchor", "middle")
			.attr("alignment-baseline", "central")
			.style("fill", function(d) { return '#'+d.color;})
			.attr("x", function(d) { return ((Number(d.source.x) + Number(d.target.x))/2); })
			.attr("y", function(d) { return ((Number(d.source.y) + Number(d.target.y))/2); })
			.text( function(d) { return d.label });


		// Use an external raster image to symbolize nodes
        //node.append("image")
        node.append("image").each(getIcon);

        // Es wäre vernünftig, das Schema zu erweitern auf eine Wunschgröße
		function getIcon(selection) {
            var node = this;
			var img = new Image();
			img.src = "imgstore.php?iconid=" + selection.info.iconid;
            img.onload = function() {
                width = img.naturalWidth;
                height = img.naturalHeight;

                //Wenn man das irgendwo dranhängt, kann man die Funktionen vielleicht außerhalb machen
                d3.select(node)
                    .attr("width", width)
                    .attr("height", height)
                    .attr("x", function(selection) { return selection.x - width / 2; })
                    .attr("y", function(selection) { return selection.y - height / 2; })
                    .attr("xlink:href", this.src);
            }
		}

		function click() {
				var data = this.__data__.menu;
				switch (data.type) {
					case 'host':
						data = getMenuPopupHost(data);
						break;

					case 'trigger':
						data = getMenuPopupTrigger(data);
						break;

					case 'history':
						data = getMenuPopupHistory(data);
						break;

					case 'map':
						data = getMenuPopupMap(data);
						break;
				}

				this.menuPopup(data, data.hostid);
				return false;
		}


		// Node labels
		node.append("text")
			.attr("x", function(d) { return d.x; })
			.attr("y", function(d) { return d.y; })
			.attr("dx", 0)
			.attr("dy", 30)
			.style("text-anchor", "middle")
            .classed("shadow", true)
			.text(function(d) { return d.label });

		node.append("text")
			.attr("x", function(d) { return d.x; })
			.attr("y", function(d) { return d.y; })
			.attr("dx", 0)
			.attr("dy", 30)
			.style("text-anchor", "middle")
			.text(function(d) { return d.label });


	})
</script>
