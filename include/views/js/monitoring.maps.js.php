<script type="text/javascript">
	jQuery(document).ready(function(){
		// Map properties
		var width = <?php echo $this->data['map']['width'];?>;
		var height = <?php echo $this->data['map']['height'];?>;

		// Inject container into table
		var svg = d3.select('#svg-container').select('td').append('svg')
			.attr('width',width)
			.attr('height',height)
			.attr('viewBox','0,0,'+width+','+height);

		// Jsonize necessary data
		var nodes = <?php echo CJs::encodeJson($this->data['nodes']);?>;
		var links = <?php echo CJs::encodeJson($this->data['links']);?>;

		// Bind data to a forced layout
		var force = d3.layout.force()
			.nodes(nodes)
			.links(links)
			.start();

		var node = svg.selectAll(".node")
			.data(nodes)
			.enter().append("g")
			.attr("class", "node");

		//TODO: nodeByName function?
		var link = svg.selectAll("line")
			.data(links)
			.enter().append("line")
			.attr("class", "link")
			.attr("source", function(d) { return d.source })
			.attr("target", function(d) { return d.target })
			.attr("x1", function(d) { return d.source.x; })
			.attr("y1", function(d) { return d.source.y; })
			.attr("x2", function(d) { return d.target.x; })
			.attr("y2", function(d) { return d.target.y; })
			.style("stroke", function(d) { return '#'+d.color;});

		//TODO: What's the point of reference for Zabbix icons?
		// Use an external raster image to symbolize nodes
		node.append("image")
			.attr("x", function(d) { return d.x-8; })
			.attr("y", function(d) { return d.y-8; })
			.attr("width", 16)
			.attr("height", 16)
			.attr("xlink:href", "https://github.com/favicon.ico");

		// Node labels
		node.append("text")
			.attr("x", function(d) { return d.x; })
			.attr("y", function(d) { return d.y; })
			.attr("dx", 0)
			.attr("dy", 35)
			.style("text-anchor", "middle")
			.text(function(d) { return d.label });
	})
</script>
